<?php

namespace Rubix\ML\Tests\AnomalyDetectors;

use Rubix\ML\Online;
use Rubix\ML\Ranking;
use Rubix\ML\Learner;
use Rubix\ML\DataType;
use Rubix\ML\Estimator;
use Rubix\ML\Persistable;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\AnomalyDetectors\Loda;
use Rubix\ML\Datasets\Generators\Blob;
use Rubix\ML\Datasets\Generators\Circle;
use Rubix\ML\Datasets\Generators\Agglomerate;
use Rubix\ML\CrossValidation\Metrics\FBeta;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use RuntimeException;

/**
 * @group AnomalyDetectors
 * @covers \Rubix\ML\AnomalyDetectors\Loda
 */
class LodaTest extends TestCase
{
    protected const TRAIN_SIZE = 400;
    protected const TEST_SIZE = 10;
    protected const MIN_SCORE = 0.9;

    protected const RANDOM_SEED = 0;

    /**
     * @var \Rubix\ML\Datasets\Generators\Agglomerate
     */
    protected $generator;

    /**
     * @var \Rubix\ML\AnomalyDetectors\Loda
     */
    protected $estimator;

    /**
     * @var \Rubix\ML\CrossValidation\Metrics\FBeta
     */
    protected $metric;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->generator = new Agglomerate([
            '0' => new Blob([0.0, 0.0], 0.5),
            '1' => new Circle(0.0, 0.0, 8.0, 0.1),
        ], [0.9, 0.1]);

        $this->estimator = new Loda(100, null, 10.0);

        $this->metric = new FBeta();

        srand(self::RANDOM_SEED);
    }

    protected function assertPreConditions() : void
    {
        $this->assertFalse($this->estimator->trained());
    }

    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(Loda::class, $this->estimator);
        $this->assertInstanceOf(Learner::class, $this->estimator);
        $this->assertInstanceOf(Online::class, $this->estimator);
        $this->assertInstanceOf(Ranking::class, $this->estimator);
        $this->assertInstanceOf(Persistable::class, $this->estimator);
        $this->assertInstanceOf(Estimator::class, $this->estimator);
    }

    /**
     * @test
     */
    public function badNumEstimators() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new Loda(-100);
    }

    /**
     * @test
     */
    public function badNumBins() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new Loda(100, 0);
    }

    /**
     * @test
     */
    public function estimateBins() : void
    {
        $this->assertSame(4, Loda::estimateBins(10));
        $this->assertSame(8, Loda::estimateBins(100));
        $this->assertSame(11, Loda::estimateBins(1000));
        $this->assertSame(14, Loda::estimateBins(10000));
        $this->assertSame(18, Loda::estimateBins(100000));
    }

    /**
     * @test
     */
    public function type() : void
    {
        $this->assertSame(Estimator::ANOMALY_DETECTOR, $this->estimator->type());
    }

    /**
     * @test
     */
    public function compatibility() : void
    {
        $expected = [
            DataType::continuous(),
        ];

        $this->assertEquals($expected, $this->estimator->compatibility());
    }

    /**
     * @test
     */
    public function trainPartialPredict() : void
    {
        $training = $this->generator->generate(self::TRAIN_SIZE);
        
        $testing = $this->generator->generate(self::TEST_SIZE);

        $folds = $training->fold(3);

        $this->estimator->train($folds[0]);
        $this->estimator->partial($folds[1]);
        $this->estimator->partial($folds[2]);

        $this->assertTrue($this->estimator->trained());

        $predictions = $this->estimator->predict($testing);

        $score = $this->metric->score($predictions, $testing->labels());

        $this->assertGreaterThanOrEqual(self::MIN_SCORE, $score);
    }

    /**
     * @test
     */
    public function trainIncompatible() : void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->estimator->train(Unlabeled::quick([['bad']]));
    }

    /**
     * @test
     */
    public function predictUntrained() : void
    {
        $this->expectException(RuntimeException::class);

        $this->estimator->predict(Unlabeled::quick());
    }
}
