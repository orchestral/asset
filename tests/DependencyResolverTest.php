<?php namespace Orchestra\Asset\TestCase;

use Orchestra\Asset\DependencyResolver;

class DependencyResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Asset\DependencyResolver::arrange() method.
     *
     * @test
     */
    public function testArrangeMethod()
    {
        $stub = new DependencyResolver;

        $output = array(
            'jquery-ui' => array(
                'source'       => 'jquery.ui.min.js',
                'dependencies' => array('jquery'),
                'attributes'   => array(),
            ),
            'jquery' => array(
                'source'       => 'jquery.min.js',
                'dependencies' => array(),
                'attributes'   => array(),
            ),
            'bootstrap' => array(
                'source'       => 'bootstrap.min.js',
                'dependencies' => array('jquery'),
                'attributes'   => array(),
            ),
            'backbone' => array(
                'source'       => 'backbone.min.js',
                'dependencies' => array('jquery', 'zepto'),
                'attributes'   => array(),
            ),
        );

        $expected = array(
            'jquery' => array(
                'source'       => 'jquery.min.js',
                'dependencies' => array(),
                'attributes'   => array(),
            ),
            'jquery-ui' => array(
                'source'       => 'jquery.ui.min.js',
                'dependencies' => array(),
                'attributes'   => array(),
            ),
            'bootstrap' => array(
                'source'       => 'bootstrap.min.js',
                'dependencies' => array(),
                'attributes'   => array(),
            ),
            'backbone' => array(
                'source'       => 'backbone.min.js',
                'dependencies' => array(),
                'attributes'   => array(),
            ),
        );

        $this->assertEquals($expected, $stub->arrange($output));
    }

    /**
     * Test Orchestra\Asset\DependencyResolver::arrange() method throws
     * exception given self dependence.
     *
     * @expectedException \RuntimeException
     */
    public function testArrangeMethodThrowsExceptionGivenSelfDependence()
    {
        $stub = new DependencyResolver;

        $output = array(
            'jquery-ui' => array(
                'source'       => 'jquery.ui.min.js',
                'dependencies' => array('jquery-ui'),
                'attributes'   => array(),
            ),
        );

        $stub->arrange($output);
    }

    /**
     * Test Orchestra\Asset\DependencyResolver::arrange() method throws
     * exception given circular dependence.
     *
     * @expectedException \RuntimeException
     */
    public function testArrangeMethodThrowsExceptionGivenCircularDependence()
    {
        $stub = new DependencyResolver;

        $output = array(
            'jquery-ui' => array(
                'source'       => 'jquery.ui.min.js',
                'dependencies' => array('jquery'),
                'attributes'   => array(),
            ),
            'jquery' => array(
                'source'       => 'jquery.min.js',
                'dependencies' => array('jquery-ui'),
                'attributes'   => array(),
            ),
        );

        $stub->arrange($output);
    }
}
