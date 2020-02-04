<?php

namespace Rubix\ML\Backends;

use Amp\Loop;
use Rubix\ML\Deferred;
use Amp\Parallel\Worker\Task;
use Rubix\ML\Other\Helpers\CPU;
use Amp\Parallel\Worker\DefaultPool;
use Amp\Parallel\Worker\CallableTask;
use InvalidArgumentException;
use Generator;
use Closure;

use function Amp\call;
use function Amp\Promise\all;

/**
 * Amp
 *
 * Amp Parallel is a multiprocessing subsystem that requires no extensions. It uses a
 * non-blocking concurrency framework that implements coroutines using PHP generator
 * functions under the hood.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Amp implements Backend
{
    /**
     * The worker pool.
     *
     * @var \Amp\Parallel\Worker\Pool
     */
    protected $pool;

    /**
     * The queue of coroutines to be processed in parallel.
     *
     * @var \Amp\Promise[]
     */
    protected $queue = [
        //
    ];

    /**
     * The memoized results of the last parallel computation.
     *
     * @var mixed[]
     */
    protected $results;

    /**
     * @param int|null $workers
     * @throws \InvalidArgumentException
     */
    public function __construct(?int $workers = null)
    {
        $workers = $workers ?? CPU::cores();

        if ($workers < 1) {
            throw new InvalidArgumentException('Number of workers'
                . " must be greater than 0, $workers given.");
        }

        $this->pool = new DefaultPool($workers);
    }

    /**
     * Return the number of background worker processes.
     *
     * @return int
     */
    public function workers() : int
    {
        return $this->pool->getMaxSize();
    }

    /**
     * Queue up a deferred computation for backend processing.
     *
     * @param \Rubix\ML\Deferred $deferred
     * @param \Closure|null $after
     * @throws \InvalidArgumentException
     */
    public function enqueue(Deferred $deferred, ?Closure $after = null) : void
    {
        $task = new CallableTask($deferred, []);

        $coroutine = call([$this, 'coroutine'], $task, $after);

        $this->queue[] = $coroutine;
    }

    /**
     * Create a coroutine for a particular task.
     *
     * @param \Amp\Parallel\Worker\Task $task
     * @param \Closure|null $after
     * @return \Generator<\Amp\Promise>
     */
    public function coroutine(Task $task, ?Closure $after = null) : Generator
    {
        $result = yield $this->pool->enqueue($task);

        if ($after) {
            $after($result);
        }

        return $result;
    }

    /**
     * Process the queue and return the results.
     *
     * @return mixed[]
     */
    public function process() : array
    {
        Loop::run([$this, 'gather']);

        $this->queue = [];

        return $this->results;
    }

    /**
     * Gather and memoize the results from the worker pool.
     *
     * @return \Generator<\Amp\Promise>
     */
    public function gather() : Generator
    {
        $this->results = yield all($this->queue);
    }

    /**
     * Flush the queue and clear the memoized results.
     */
    public function flush() : void
    {
        $this->queue = [];
        
        unset($this->results);
    }
}
