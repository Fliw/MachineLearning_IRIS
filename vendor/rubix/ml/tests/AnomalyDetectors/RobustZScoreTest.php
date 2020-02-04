<?php

namespace Rubix\ML\Tests\AnomalyDetectors;

use Rubix\ML\Learner;
use Rubix\ML\Ranking;
use Rubix\ML\DataType;
use Rubix\ML\Estimator;
use Rubix\ML\Persistable;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Datasets\Generators\Blob;
use Rubix\ML\Datasets\Generators\Circle;
use Rubix\ML\AnomalyDetectors\RobustZScore;
use Rubix\ML\Datasets\Generators\Agglomerate;
use Rubix\ML\CrossValidation\Metrics\FBeta;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use RuntimeException;

/**
 * @group AnomalyDetectors
 * @covers \Rubix\ML\AnomalyDetectors\RobustZScore
 */
class RobustZScoreTest extends TestCase
{
    protected const TRAIN_SIZE = 300;
    protected const TEST_SIZE = 10;
    protected const MIN_SCORE = 0.9;

    protected const RANDOM_SEED = 0;

    /**
     * @var \Rubix\ML\Datasets\Generators\Agglomerate
     */
    protected $generator;

    /**
     * @var \Rubix\ML\AnomalyDetectors\RobustZScore
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

        $this->estimator = new RobustZScore(3.5, 0.5);

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
        $this->assertInstanceOf(RobustZScore::class, $this->estimator);
        $this->assertInstanceOf(Learner::class, $this->estimator);
        $this->assertInstanceOf(Ranking::class, $this->estimator);
        $this->assertInstanceOf(Persistable::class, $this->estimator);
        $this->assertInstanceOf(Estimator::class, $this->estimator);
    }

    /**
     * @test
     */
    public function badThreshold() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new RobustZScore(-3.5);
    }

    /**
     * @test
     */
    public function badAlpha() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new RobustZScore(3.5, 1.5);
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
    public function trainPredict() : void
    {
        $training = $this->generator->generate(self::TRAIN_SIZE);
        
        $testing = $this->generator->generate(self::TEST_SIZE);

        $this->estimator->train($training);

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
