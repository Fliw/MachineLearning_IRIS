<?php

namespace Rubix\ML\CrossValidation\Metrics;

use Rubix\ML\Estimator;
use InvalidArgumentException;

use function count;

/**
 * Mean Absolute Error
 *
 * A scale-dependent metric that measures the average absolute error between a set of
 * predictions and their ground-truth labels. One of the nice properties of MAE is that it
 * has the same units of measurement as the labels being estimated.
 *
 * > **Note:** In order to maintain the convention of *maximizing* validation scores, this
 * metric outputs the negative of the original score.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class MeanAbsoluteError implements Metric
{
    /**
     * Return a tuple of the min and max output value for this metric.
     *
     * @return float[]
     */
    public function range() : array
    {
        return [-INF, 0.0];
    }

    /**
     * The estimator types that this metric is compatible with.
     *
     * @return int[]
     */
    public function compatibility() : array
    {
        return [
            Estimator::REGRESSOR,
        ];
    }

    /**
     * Score a set of predictions.
     *
     * @param (int|float)[] $predictions
     * @param (int|float)[] $labels
     * @throws \InvalidArgumentException
     * @return float
     */
    public function score(array $predictions, array $labels) : float
    {
        if (empty($predictions)) {
            return 0.0;
        }

        if (count($predictions) !== count($labels)) {
            throw new InvalidArgumentException('The number of labels'
                . ' must equal the number of predictions.');
        }

        $error = 0.0;

        foreach ($predictions as $i => $prediction) {
            $error += abs($labels[$i] - $prediction);
        }

        return -($error / count($predictions));
    }
}
