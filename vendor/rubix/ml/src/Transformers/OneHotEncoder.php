<?php

namespace Rubix\ML\Transformers;

use Rubix\ML\DataType;
use Rubix\ML\Datasets\Dataset;
use Rubix\ML\Other\Specifications\SamplesAreCompatibleWithTransformer;
use RuntimeException;

use function count;
use function is_null;

/**
 * One Hot Encoder
 *
 * The One Hot Encoder takes a feature column of categorical values and produces an n-d
 * *one-hot* representation where n is equal to the number of unique categories in that
 * column. After the transformation, a 0 in any location indicates that the category
 * represented by that column is not present in the sample whereas a 1 indicates that a
 * category is present. One hot encoding is typically used to convert categorical data to
 * continuous so that it can be used to train a learner that is only compatible with
 * continuous features.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class OneHotEncoder implements Transformer, Stateful
{
    /**
     * The set of unique possible categories per feature column of the training set.
     *
     * @var array[]|null
     */
    protected $categories;

    /**
     * The null encoding for each feature column.
     *
     * @var array[]|null
     */
    protected $templates;

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
        return isset($this->categories);
    }

    /**
     * Return the categories computed during fitting indexed by feature column.
     *
     * @return array[]|null
     */
    public function categories() : ?array
    {
        return $this->categories ? array_map('array_flip', $this->categories) : null;
    }

    /**
     * Fit the transformer to the dataset.
     *
     * @param \Rubix\ML\Datasets\Dataset $dataset
     */
    public function fit(Dataset $dataset) : void
    {
        SamplesAreCompatibleWithTransformer::check($dataset, $this);

        $this->categories = $this->templates = [];

        foreach ($dataset->types() as $column => $type) {
            if ($type->isCategorical()) {
                $values = $dataset->column($column);
                
                $categories = array_values(array_unique($values));

                $this->categories[$column] = array_flip($categories);

                $this->templates[$column] = array_fill(0, count($categories), 0);
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
        if (is_null($this->categories) or is_null($this->templates)) {
            throw new RuntimeException('Transformer has not been fitted.');
        }

        foreach ($samples as &$sample) {
            $temp = [];

            foreach ($this->categories as $column => $categories) {
                $template = $this->templates[$column];
                $category = $sample[$column];

                if (isset($categories[$category])) {
                    $template[$categories[$category]] = 1;
                }

                $temp[] = $template;

                unset($sample[$column]);
            }

            $sample = array_merge($sample, ...$temp);
        }
    }
}
