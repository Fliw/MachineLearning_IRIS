<?php

namespace Rubix\ML\Tests\Transformers;

use Rubix\ML\Transformers\Stateful;
use Rubix\ML\Transformers\Transformer;
use Rubix\ML\Datasets\Generators\Blob;
use Rubix\ML\Transformers\SparseRandomProjector;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @group Transformers
 * @covers \Rubix\ML\Transformers\SparseRandomProjector
 */
class SparseRandomProjectorTest extends TestCase
{
    /**
     * @var \Rubix\ML\Datasets\Generators\Generator
     */
    protected $generator;
    
    /**
     * @var \Rubix\ML\Transformers\SparseRandomProjector
     */
    protected $transformer;

    /**
     * @before
     */
    protected function setUp() : void
    {
        $this->generator = new Blob(array_fill(0, 10, 0.0), 3.0);

        $this->transformer = new SparseRandomProjector(4);
    }
    
    /**
     * @test
     */
    public function build() : void
    {
        $this->assertInstanceOf(SparseRandomProjector::class, $this->transformer);
        $this->assertInstanceOf(Transformer::class, $this->transformer);
        $this->assertInstanceOf(Stateful::class, $this->transformer);
    }
    
    /**
     * @test
     */
    public function fitTransform() : void
    {
        $this->assertCount(10, $this->generator->generate(1)->sample(0));

        $this->transformer->fit($this->generator->generate(30));

        $this->assertTrue($this->transformer->fitted());

        $sample = $this->generator->generate(1)
            ->apply($this->transformer)
            ->sample(0);

        $this->assertCount(4, $sample);
    }
    
    /**
     * @test
     */
    public function transformUnfitted() : void
    {
        $this->expectException(RuntimeException::class);

        $samples = $this->generator->generate(1)->samples();

        $this->transformer->transform($samples);
    }
}
