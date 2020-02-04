<?php

namespace Rubix\ML\Kernels\Distance;

use Rubix\ML\DataType;
use InvalidArgumentException;

use function count;

/**
 * Gower
 *
 * A generalized robust distance kernel that measures a mix of categorical and
 * continuous data types while handling NaN values. When comparing continuous
 * data, the Gower metric is equivalent to the normalized Manhattan distance
 * and when comparing categorical data it is equivalent to the Hamming distance.
 *
 * > **Note:** The Gower metric expects that all continuous variables are on
 * the same scale. By default, the range is between 0 and 1.
 *
 * References:
 * [1] J. C. Gower. (1971). A General Coefficient of Similarity and Some of Its
 * Properties.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Gower implements Distance, NaNSafe
{
    /**
     * The range of the continuous feature columns.
     *
     * @var float
     */
    protected $range;

    /**
     * @param float $range
     * @throws \InvalidArgumentException
     */
    public function __construct(float $range = 1.)
    {
        if ($range <= 0.) {
            throw new InvalidArgumentException('Range must be greater'
                . " than 0,  $range given.");
        }

        $this->range = $range;
    }

    /**
     * Return the data types that this kernel is compatible with.
     *
     * @return \Rubix\ML\DataType[]
     */
    public function compatibility() : array
    {
        return [
            DataType::categorical(),
            DataType::continuous(),
        ];
    }

    /**
     * Compute the distance between two vectors.
     *
     * @param (string|int|float)[] $a
     * @param (string|int|float)[] $b
     * @return float
     */
    public function compute(array $a, array $b) : float
    {
        $distance = 0.;
        $nn = 0;

        $n = count($a);

        foreach ($a as $i => $valueA) {
            $valueB = $b[$i];

            switch (true) {
                case is_float($valueA) and is_nan($valueA):
                    ++$nn;

                    break 1;

                case is_float($valueB) and is_nan($valueB):
                    ++$nn;

                    break 1;

                case !is_string($valueA) and !is_string($valueB):
                    $distance += abs($valueA - $valueB)
                        / $this->range;

                    break 1;

                default:
                    if ($valueA !== $valueB) {
                        $distance += 1.;
                    }
            }
        }

        if ($nn === $n) {
            return NAN;
        }

        return $distance / ($n - $nn);
    }
}
