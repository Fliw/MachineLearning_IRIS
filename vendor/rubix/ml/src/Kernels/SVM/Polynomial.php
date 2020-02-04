<?php

namespace Rubix\ML\Kernels\SVM;

use InvalidArgumentException;
use RuntimeException;
use svm;

/**
 * Polynomial
 *
 * Operating in high dimensions, the polynomial to the pth degree of the
 * sample vector.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class Polynomial implements Kernel
{
    /**
     * The degree of the polynomial.
     *
     * @var int
     */
    protected $degree;

    /**
     * The kernel coefficient.
     *
     * @var float|null
     */
    protected $gamma;

    /**
     * The independent term.
     *
     * @var float
     */
    protected $coef0;

    /**
     * @param int $degree
     * @param float $gamma
     * @param float $coef0
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(int $degree = 3, ?float $gamma = null, float $coef0 = 0.)
    {
        if (!extension_loaded('svm')) {
            throw new RuntimeException('SVM extension is not loaded, check'
                . ' PHP configuration.');
        }

        if ($degree < 1) {
            throw new InvalidArgumentException('Degree must be greater than 0,'
                . " $degree given.");
        }
        
        $this->degree = $degree;
        $this->gamma = $gamma;
        $this->coef0 = $coef0;
    }

    /**
     * Return the options for the libsvm runtime.
     *
     * @return mixed[]
     */
    public function options() : array
    {
        return [
            svm::OPT_KERNEL_TYPE => svm::KERNEL_POLY,
            svm::OPT_DEGREE => $this->degree,
            svm::OPT_GAMMA => $this->gamma,
            svm::OPT_COEF_ZERO => $this->coef0,
        ];
    }
}
