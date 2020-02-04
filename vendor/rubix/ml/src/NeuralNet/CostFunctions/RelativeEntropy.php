<?php

namespace Rubix\ML\NeuralNet\CostFunctions;

use Tensor\Matrix;

use const Rubix\ML\EPSILON;

/**
 * Relative Entropy
 *
 * Relative Entropy or *Kullback-Leibler divergence* is a measure of how the
 * expectation and activation of the network diverge.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class RelativeEntropy implements ClassificationLoss
{
    /**
     * Return a tuple of the min and max output value for this function.
     *
     * @return float[]
     */
    public function range() : array
    {
        return [-INF, INF];
    }

    /**
     * Compute the loss.
     *
     * @param \Tensor\Matrix $output
     * @param \Tensor\Matrix $target
     * @return float
     */
    public function compute(Matrix $output, Matrix $target) : float
    {
        $target = $target->clip(EPSILON, 1.0);
        $output = $output->clip(EPSILON, 1.0);

        return $target->divide($output)->log()
            ->multiply($target)
            ->mean()
            ->mean();
    }

    /**
     * Calculate the gradient of the cost function with respect to the output.
     *
     * @param \Tensor\Matrix $output
     * @param \Tensor\Matrix $target
     * @return \Tensor\Matrix
     */
    public function differentiate(Matrix $output, Matrix $target) : Matrix
    {
        $target = $target->clip(EPSILON, 1.0);
        $output = $output->clip(EPSILON, 1.0);

        return $output->subtract($target)
            ->divide($output);
    }
}
