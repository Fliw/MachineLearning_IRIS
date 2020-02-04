<?php

namespace Rubix\ML\Transformers;

use Rubix\ML\DataType;
use Rubix\ML\Datasets\Dataset;
use Rubix\ML\Other\Helpers\Stats;
use Rubix\ML\Other\Specifications\SamplesAreCompatibleWithTransformer;
use InvalidArgumentException;
use RuntimeException;

use function is_null;

/**
 * Variance Threshold Filter
 *
 * A type of feature selector that selects feature columns that have a greater
 * variance than the user-specified threshold. As an extreme example, if a
 * feature column has a variance of 0 then that feature will all be valued equally.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class VarianceThresholdFilter implements Transformer, Stateful
{
    /**
     * Feature columns with a variance greater than this threshold will be
     * selected.
     *
     * @var float
     */
    protected $threshold;

    /**
     * The indices of the feature columns that have been selected.
     *
     * @var bool[]|null
     */
    protected $selected;

    /**
     * @param float $threshold
     * @throws \InvalidArgumentException
     */
    public function __construct(float $threshold = 0.)
    {
        if ($threshold < 0.) {
            throw new InvalidArgumentException('Threshold must be 0 or greater'
                . ", $threshold given.");
        }

        $this->threshold = $threshold;
    }

    /**
     * Return the data types that this transformer is compatible with.
     *
     * @return \Rubix\ML\DataType[]
     */
    public function compatibility() : array
    {
        return DataType::all();
    }

    /**
     * Is the transformer fitted?
     *
     * @return bool
     */
    public function fitted() : bool
    {
        return isset($this->selected);
    }

    /**
     * Return the column indexes that have been selected during fitting.
     *
     * @return int[]
     */
    public function selected() : array
    {
        return array_keys($this->selected ?: []);
    }

    /**
     * Fit the transformer to the dataset.
     *
     * @param \Rubix\ML\Datasets\Dataset $dataset
     */
    public function fit(Dataset $dataset) : void
    {
        SamplesAreCompatibleWithTransformer::check($dataset, $this);
        
        $this->selected = [];

        foreach ($dataset->types() as $column => $type) {
            if ($type->isContinuous()) {
                $values = $dataset->column($column);
                
                if (Stats::variance($values) > $this->threshold) {
                    $this->selected[$column] = true;
                }
            }
        }
    }

    /**
     * Transform the dataset in place.
     *
     * @param array[] $samples
     * @throws \RuntimeException
     */
    public function transform(array &$samples) : void
    {
        if (is_null($this->selected)) {
            throw new RuntimeException('Transformer has not been fitted.');
        }

        foreach ($samples as &$sample) {
            $sample = array_values(array_intersect_key($sample, $this->selected));
        }
    }
}
