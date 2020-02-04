<?php

namespace Rubix\ML\CrossValidation\Reports;

use Rubix\ML\Estimator;
use InvalidArgumentException;

use function count;

/**
 * Confusion Matrix
 *
 * A Confusion Matrix is a table that visualizes the true positives, false positives,
 * true negatives, and false negatives of a classification experiment. The name stems
 * from the fact that the matrix makes it clear to see if the estimator is *confusing*
 * any two classes.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class ConfusionMatrix implements Report
{
    /**
     * The estimator types that this report is compatible with.
     *
     * @return int[]
     */
    public function compatibility() : array
    {
        return [
            Estimator::CLASSIFIER,
            Estimator::ANOMALY_DETECTOR,
        ];
    }

    /**
     * Generate the report.
     *
     * @param (string|int)[] $predictions
     * @param (string|int)[] $labels
     * @throws \InvalidArgumentException
     * @return array[]
     */
    public function generate(array $predictions, array $labels) : array
    {
        if (count($predictions) !== count($labels)) {
            throw new InvalidArgumentException('The number of labels'
                . ' must equal the number of predictions.');
        }
        
        $classes = array_unique(array_merge($predictions, $labels));

        $matrix = array_fill_keys($classes, array_fill_keys($classes, 0));

        foreach ($predictions as $i => $prediction) {
            ++$matrix[$prediction][$labels[$i]];
        }

        return $matrix;
    }
}
