<?php

namespace Rubix\ML\Tests\Other\Specifications;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Classifiers\NaiveBayes;
use Rubix\ML\Other\Specifications\LabelsAreCompatibleWithLearner;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * @group Specifications
 * @covers \Rubix\ML\Other\Specifications\LabelsAreCompatibleWithLearner
 */
class LabelsAreCompatibleWithLearnerTest extends TestCase
{
    /**
     * @test
     */
    public function check() : void
    {
        $estimator = new NaiveBayes();

        $dataset = Labeled::quick([
            [6., -1.1, 5, 'college'],
        ], [200]);

        $this->expectException(InvalidArgumentException::class);

        LabelsAreCompatibleWithLearner::check($dataset, $estimator);
    }
}
