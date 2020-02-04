<?php

namespace Rubix\ML\Tests\CrossValidation\Reports;

use Rubix\ML\Estimator;
use Rubix\ML\CrossValidation\Reports\Report;
use Rubix\ML\CrossValidation\Reports\ContingencyTable;
use PHPUnit\Framework\TestCase;
use Generator;

/**
 * @group Reports
 * @covers \Rubix\ML\CrossValidation\Reports\ContingencyTable
 */
class ContingencyTableTest extends TestCase
{
    /**
     * @var \Rubix\ML\CrossValidation\Reports\ContingencyTable
     */
    protected $report;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->report = new ContingencyTable();
    }

    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(ContingencyTable::class, $this->report);
        $this->assertInstanceOf(Report::class, $this->report);
    }

    /**
     * @test
     */
    public function compatibility() : void
    {
        $expected = [
            Estimator::CLUSTERER,
        ];

        $this->assertEquals($expected, $this->report->compatibility());
    }

    /**
     * @test
     * @dataProvider generateProvider
     *
     * @param (string|int)[] $predictions
     * @param (string|int)[] $labels
     * @param array[] $expected
     */
    public function generate(array $predictions, array $labels, array $expected) : void
    {
        $result = $this->report->generate($predictions, $labels);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return \Generator<array>
     */
    public function generateProvider() : Generator
    {
        yield [
            [0, 1, 1, 0, 1],
            ['lamb', 'lamb', 'wolf', 'wolf', 'wolf'],
            [
                0 => [
                    'wolf' => 1,
                    'lamb' => 1,
                ],
                1 => [
                    'wolf' => 2,
                    'lamb' => 1,
                ],
            ],
        ];
    }
}
