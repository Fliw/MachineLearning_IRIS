<?php

namespace Rubix\ML\Tests\CrossValidation\Reports;

use Rubix\ML\Estimator;
use Rubix\ML\CrossValidation\Reports\Report;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use PHPUnit\Framework\TestCase;

/**
 * @group Reports
 * @covers \Rubix\ML\CrossValidation\Reports\AggregateReport
 */
class AggregateReportTest extends TestCase
{
    /**
     * @var \Rubix\ML\CrossValidation\Reports\AggregateReport
     */
    protected $report;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->report = new AggregateReport([
            new ConfusionMatrix(),
            new MulticlassBreakdown(),
        ]);
    }

    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(AggregateReport::class, $this->report);
        $this->assertInstanceOf(Report::class, $this->report);
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

        $this->assertEquals($expected, $this->report->compatibility());
    }

    /**
     * @test
     */
    public function generate() : void
    {
        $predictions = ['wolf', 'lamb', 'wolf', 'lamb', 'wolf'];

        $labels = ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'];

        $result = $this->report->generate($predictions, $labels);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }
}
