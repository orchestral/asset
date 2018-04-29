<?php

namespace Orchestra\Asset\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Asset\DependencyResolver;

class DependencyResolverTest extends TestCase
{
    /** @test */
    public function it_can_arrange_the_dependencies()
    {
        $stub = new DependencyResolver();

        $output = [
            'app' => [
                'source' => 'app.min.js',
                'dependencies' => ['jquery', 'bootstrap', 'backbone'],
                'attributes' => [],
                'replaces' => [],
            ],
            'jquery-ui' => [
                'source' => 'jquery.ui.min.js',
                'dependencies' => ['jquery'],
                'attributes' => [],
                'replaces' => [],
            ],
            'jquery' => [
                'source' => 'jquery.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => [],
            ],
            'bootstrap' => [
                'source' => 'bootstrap.min.js',
                'dependencies' => ['jquery'],
                'attributes' => [],
                'replaces' => [],
            ],
            'backbone' => [
                'source' => 'backbone.min.js',
                'dependencies' => ['jquery', 'zepto'],
                'attributes' => [],
                'replaces' => [],
            ],
            'jquery.min' => [
                'source' => 'all.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => ['jquery', 'jquery-ui'],
            ],
        ];

        $expected = [
            'jquery.min' => [
                'source' => 'all.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => [],
            ],
            'bootstrap' => [
                'source' => 'bootstrap.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => [],
            ],
            'backbone' => [
                'source' => 'backbone.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => [],
            ],
            'app' => [
                'source' => 'app.min.js',
                'dependencies' => [],
                'attributes' => [],
                'replaces' => [],
            ],
        ];

        $this->assertEquals($expected, $stub->arrange($output));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_throws_exception_given_reference_to_self_as_dependencies()
    {
        $stub = new DependencyResolver();

        $output = [
            'jquery-ui' => [
                'source' => 'jquery.ui.min.js',
                'dependencies' => ['jquery-ui'],
                'attributes' => [],
                'replaces' => [],
            ],
        ];

        $stub->arrange($output);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_throws_exception_given_circular_dependencies()
    {
        $stub = new DependencyResolver();

        $output = [
            'jquery-ui' => [
                'source' => 'jquery.ui.min.js',
                'dependencies' => ['jquery'],
                'attributes' => [],
                'replaces' => [],
            ],
            'jquery' => [
                'source' => 'jquery.min.js',
                'dependencies' => ['jquery-ui'],
                'attributes' => [],
                'replaces' => [],
            ],
        ];

        $stub->arrange($output);
    }
}
