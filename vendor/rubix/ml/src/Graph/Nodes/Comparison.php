<?php

namespace Rubix\ML\Graph\Nodes;

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Graph\Nodes\Traits\HasBinaryChildren;
use InvalidArgumentException;

use function count;

/**
 * Comparison
 *
 * A node that represents a comparison of a value in a single dimension
 * with the value on the node (i.e. the split value).
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Comparison implements Decision
{
    use HasBinaryChildren;
    
    /**
     * The feature column (index) of the split value.
     *
     * @var int
     */
    protected $column;

    /**
     * The value that the node splits on.
     *
     * @var int|float|string
     */
    protected $value;

    /**
     * The left and right splits of the training data.
     *
     * @var \Rubix\ML\Datasets\Labeled[]
     */
    protected $groups;

    /**
     * The amount of impurity that the split introduces.
     *
     * @var float
     */
    protected $impurity;

    /**
     * The number of training samples this node is responsible for.
     *
     * @var int
     */
    protected $n;

    /**
     * @param int $column
     * @param mixed $value
     * @param \Rubix\ML\Datasets\Labeled[] $groups
     * @param float $impurity
     * @throws \InvalidArgumentException
     */
    public function __construct(int $column, $value, array $groups, float $impurity)
    {
        if (!is_string($value) and !is_numeric($value)) {
            throw new InvalidArgumentException('Split value must be a string'
                . ' or numeric type, ' . gettype($value) . ' given.');
        }

        if (count($groups) !== 2) {
            throw new InvalidArgumentException('The number of groups'
                . ' must be exactly 2.');
        }

        foreach ($groups as $group) {
            if (!$group instanceof Labeled) {
                throw new InvalidArgumentException('Group must be a'
                    . ' labeled dataset, ' . gettype($group) . ' given.');
            }
        }

        $this->column = $column;
        $this->value = $value;
        $this->groups = $groups;
        $this->impurity = $impurity;
        $this->n = (int) array_sum(array_map('count', $groups));
    }

    /**
     * Return the feature column (index) of the split value.
     *
     * @return int
     */
    public function column() : int
    {
        return $this->column;
    }

    /**
     * Return the split value.
     *
     * @return int|float|string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Return the left and right splits of the training data.
     *
     * @return \Rubix\ML\Datasets\Labeled[]
     */
    public function groups() : array
    {
        return $this->groups;
    }

    /**
     * Return the impurity score of the node.
     *
     * @return float
     */
    public function impurity() : float
    {
        return $this->impurity;
    }

    /**
     * Return the number of samples from the training set this node represents.
     *
     * @return int
     */
    public function n() : int
    {
        return $this->n;
    }

    /**
     * Return the decrease in impurity this decision node introduces.
     *
     * @return float
     */
    public function purityIncrease() : float
    {
        $impurity = $this->impurity;

        if ($this->left instanceof Decision) {
            $impurity -= $this->left->impurity()
                * ($this->left->n() / $this->n);
        }

        if ($this->right instanceof Decision) {
            $impurity -= $this->right->impurity()
                * ($this->right->n() / $this->n);
        }

        return $impurity;
    }

    /**
     * Remove the left and right splits of the training data.
     */
    public function cleanup() : void
    {
        unset($this->groups);
    }
}
