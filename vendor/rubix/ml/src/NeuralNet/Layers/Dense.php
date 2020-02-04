<?php

namespace Rubix\ML\NeuralNet\Layers;

use Tensor\Matrix;
use Rubix\ML\Deferred;
use Rubix\ML\NeuralNet\Initializers\He;
use Rubix\ML\NeuralNet\Optimizers\Optimizer;
use Rubix\ML\NeuralNet\Initializers\Constant;
use Rubix\ML\NeuralNet\Parameters\MatrixParam;
use Rubix\ML\NeuralNet\Parameters\VectorParam;
use Rubix\ML\NeuralNet\Initializers\Initializer;
use InvalidArgumentException;
use RuntimeException;
use Generator;

/**
 * Dense
 *
 * Dense (or *fully connected*) hidden layers are layers of neurons that connect to each node
 * in the previous layer by a parameterized synapse. They perform a linear transformation on
 * their input and are usually followed by an Activation layer. The majority of the trainable
 * parameters in a standard feed forward neural network are contained within Dense hidden layers.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Dense implements Hidden, Parametric
{
    /**
     * The number of nodes in the layer.
     *
     * @var int
     */
    protected $neurons;

    /**
     * Should the layer include a bias parameter?
     *
     * @var bool
     */
    protected $bias;

    /**
     * The weight initializer.
     *
     * @var \Rubix\ML\NeuralNet\Initializers\Initializer
     */
    protected $weightInitializer;

    /**
     * The bias initializer.
     *
     * @var \Rubix\ML\NeuralNet\Initializers\Initializer
     */
    protected $biasInitializer;

    /**
     * The weights.
     *
     * @var \Rubix\ML\NeuralNet\Parameters\Parameter|null
     */
    protected $weights;

    /**
     * The biases.
     *
     * @var \Rubix\ML\NeuralNet\Parameters\Parameter|null
     */
    protected $biases;

    /**
     * The memoized inputs to the layer.
     *
     * @var \Tensor\Matrix|null
     */
    protected $input;

    /**
     * @param int $neurons
     * @param bool $bias
     * @param \Rubix\ML\NeuralNet\Initializers\Initializer|null $weightInitializer
     * @param \Rubix\ML\NeuralNet\Initializers\Initializer|null $biasInitializer
     * @throws \InvalidArgumentException
     */
    public function __construct(
        int $neurons,
        bool $bias = true,
        ?Initializer $weightInitializer = null,
        ?Initializer $biasInitializer = null
    ) {
        if ($neurons < 1) {
            throw new InvalidArgumentException('The number of neurons cannot be'
                . ' less than 1.');
        }

        $this->neurons = $neurons;
        $this->bias = $bias;
        $this->weightInitializer = $weightInitializer ?? new He();
        $this->biasInitializer = $biasInitializer ?? new Constant(0.0);
    }

    /**
     * Return the width of the layer.
     *
     * @return int
     */
    public function width() : int
    {
        return $this->neurons;
    }

    /**
     * Return the parameters of the layer.
     *
     * @throws \RuntimeException
     * @return \Generator<\Rubix\ML\NeuralNet\Parameters\Parameter>
     */
    public function parameters() : Generator
    {
        if (!$this->weights) {
            throw new RuntimeException('Layer has not been initialized.');
        }

        yield $this->weights;

        if ($this->biases) {
            yield $this->biases;
        }
    }

    /**
     * Initialize the layer with the fan in from the previous layer and return
     * the fan out for this layer.
     *
     * @param int $fanIn
     * @return int
     */
    public function initialize(int $fanIn) : int
    {
        $fanOut = $this->neurons;

        $w = $this->weightInitializer->initialize($fanIn, $fanOut);

        $this->weights = new MatrixParam($w);

        if ($this->bias) {
            $b = $this->biasInitializer->initialize(1, $fanOut)
                ->columnAsVector(0);

            $this->biases = new VectorParam($b);
        }

        return $fanOut;
    }

    /**
     * Compute a forward pass through the layer.
     *
     * @param \Tensor\Matrix $input
     * @throws \RuntimeException
     * @return \Tensor\Matrix
     */
    public function forward(Matrix $input) : Matrix
    {
        if (!$this->weights) {
            throw new RuntimeException('Layer is not initialized');
        }

        $this->input = $input;

        $z = $this->weights->w()->matmul($input);

        if ($this->biases) {
            $z = $z->add($this->biases->w());
        }

        return $z;
    }

    /**
     * Compute an inference pass through the layer.
     *
     * @param \Tensor\Matrix $input
     * @throws \RuntimeException
     * @return \Tensor\Matrix
     */
    public function infer(Matrix $input) : Matrix
    {
        if (!$this->weights) {
            throw new RuntimeException('Layer is not initialized');
        }

        $z = $this->weights->w()->matmul($input);

        if ($this->biases) {
            $z = $z->add($this->biases->w());
        }

        return $z;
    }

    /**
     * Calculate the gradient and update the parameters of the layer.
     *
     * @param \Rubix\ML\Deferred $prevGradient
     * @param \Rubix\ML\NeuralNet\Optimizers\Optimizer $optimizer
     * @throws \RuntimeException
     * @return \Rubix\ML\Deferred
     */
    public function back(Deferred $prevGradient, Optimizer $optimizer) : Deferred
    {
        if (!$this->weights) {
            throw new RuntimeException('Layer has not been initialized.');
        }

        if (!$this->input) {
            throw new RuntimeException('Must perform forward pass before'
                . ' backpropagating.');
        }

        $dOut = $prevGradient();

        $dW = $dOut->matmul($this->input->transpose());

        $w = $this->weights->w();

        $this->weights->update($optimizer->step($this->weights, $dW));

        if ($this->biases) {
            $dB = $dOut->sum();

            $this->biases->update($optimizer->step($this->biases, $dB));
        }

        unset($this->input);

        return new Deferred([$this, 'gradient'], [$w, $dOut]);
    }

    /**
     * Calculate the gradient for the previous layer.
     *
     * @param \Tensor\Matrix $w
     * @param \Tensor\Matrix $dOut
     * @return \Tensor\Matrix
     */
    public function gradient(Matrix $w, Matrix $dOut) : Matrix
    {
        return $w->transpose()->matmul($dOut);
    }

    /**
     * Return the parameters of the layer in an associative array.
     *
     * @throws \RuntimeException
     * @return \Rubix\ML\NeuralNet\Parameters\Parameter[]
     */
    public function read() : array
    {
        if (!$this->weights) {
            throw new RuntimeException('Layer has not been initialized.');
        }

        $params = [
            'weights' => clone $this->weights,
        ];

        if ($this->biases) {
            $params['biases'] = clone $this->biases;
        }

        return $params;
    }

    /**
     * Restore the parameters in the layer from an associative array.
     *
     * @param \Rubix\ML\NeuralNet\Parameters\Parameter[] $parameters
     */
    public function restore(array $parameters) : void
    {
        $this->weights = $parameters['weights'];
        $this->biases = $parameters['biases'] ?? null;
    }
}
