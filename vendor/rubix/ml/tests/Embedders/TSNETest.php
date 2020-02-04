<?php

namespace Rubix\ML\Tests\Embedders;

use Rubix\ML\Verbose;
use Rubix\ML\DataType;
use Rubix\ML\Embedders\TSNE;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Other\Loggers\BlackHole;
use Rubix\ML\Datasets\Generators\Blob;
use Rubix\ML\Kernels\Distance\Euclidean;
use Rubix\ML\Datasets\Generators\Agglomerate;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * @group Embedders
 * @covers \Rubix\ML\Embedders\TSNE
 */
class TSNETest extends TestCase
{
    protected const DATASET_SIZE = 30;

    protected const RANDOM_SEED = 0;

    /**
     * @var \Rubix\ML\Datasets\Generators\Generator
     */
    protected $generator;

    /**
     * @var \Rubix\ML\Embedders\TSNE
     */
    protected $embedder;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->generator = new Agglomerate([
            'red' => new Blob([255, 32, 0], 30.0),
            'green' => new Blob([0, 128, 0], 10.0),
            'blue' => new Blob([0, 32, 255], 20.0),
        ], [2, 3, 4]);

        $this->embedder = new TSNE(1, 10.0, 10, 12.0, 500, 1e-7, 10, new Euclidean());

        $this->embedder->setLogger(new BlackHole());

        srand(self::RANDOM_SEED);
    }

    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(TSNE::class, $this->embedder);
        $this->assertInstanceOf(Verbose::class, $this->embedder);
    }

    /**
     * @test
     */
    public function badNumDimensions() : void
    {
        $this->expectException(InvalidArgumentException::class);

        new TSNE(0);
    }

    /**
     * @test
     */
    public function compatibility() : void
    {
        $expected = [
            DataType::continuous(),
        ];

        $this->assertEquals($expected, $this->embedder->compatibility());
    }

    /**
     * @test
     */
    public function embed() : void
    {
        $dataset = $this->generator->generate(self::DATASET_SIZE);

        $samples = $this->embedder->embed($dataset);

        $this->assertCount(self::DATASET_SIZE, $samples);
        $this->assertCount(1, $samples[0]);
    }

    /**
     * @test
     */
    public function embedIncompatible() : void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->embedder->embed(Unlabeled::quick([['bad']]));
    }
}
