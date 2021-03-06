<?php

namespace Rubix\ML\Tests\CrossValidation\Metrics;

use Rubix\ML\Estimator;
use Rubix\ML\CrossValidation\Metrics\Metric;
use Rubix\ML\CrossValidation\Metrics\Informedness;
use PHPUnit\Framework\TestCase;
use Generator;

/**
 * @group Metrics
 * @covers \Rubix\ML\CrossValidation\Metrics\Informedness
 */
class InformednessTest extends TestCase
{
    /**
     * @var \Rubix\ML\CrossValidation\Metrics\Informedness
     */
    protected $metric;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->metric = new Informedness();
    }

    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(Informedness::class, $this->metric);
        $this->assertInstanceOf(Metric::class, $this->metric);
    }

    /**
     * @test
     */
    public function range() : void
    {
        $expected = [-1.0, 1.0];

        $this->assertEquals($expected, $this->metric->range());
    }

    /**
     * @test
     */
    public function compatibility() : void
    {
        $expected = [
            Estimator::CLASSIFIER,
            Estimator::ANOMALY_DETECTOR,
        ];

        $this->assertEquals($expected, $this->metric->compatibility());
    }

    /**
     * @test
     * @dataProvider scoreProvider
     *
     * @param (string|int)[] $predictions
     * @param (string|int)[] $labels
     * @param float $expected
     */
    public function score(array $predictions, array $labels, float $expected) : void
    {
        [$min, $max] = $this->metric->range();

        $score = $this->metric->score($predictions, $labels);

        $this->assertThat(
            $score,
            $this->logicalAnd(
                $this->greaterThanOrEqual($min),
                $this->lessThanOrEqual($max)
            )
        );

        $this->assertEquals($expected, $score);
    }

    /**
     * @return \Generator<array>
     */
    public function scoreProvider() : Generator
    {
        yield [
            ['wolf', 'lamb', 'wolf', 'lamb', 'wolf'],
            ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'],
            0.16666666666666652,
        ];

        yield [
            ['wolf', 'wolf', 'lamb', 'lamb', 'lamb'],
            ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'],
            -1.0,
        ];

        yield [
            ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'],
            ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'],
            1.0,
        ];

        yield [
            [0, 1, 0, 1, 0],
            [0, 0, 0, 1, 0],
            0.75,
        ];

        yield [
            [0, 0, 0, 1, 0],
            [0, 0, 0, 1, 0],
            1.0,
        ];

        yield [
            [1, 1, 1, 0, 1],
            [0, 0, 0, 1, 0],
            -1.0,
        ];
    }
}
