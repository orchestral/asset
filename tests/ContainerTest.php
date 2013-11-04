<?php namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Orchestra\Asset\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test constructing Orchesta\Asset\Container.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher[run]');

        $assets = array(
            'script' => array(
                'foo' => array(
                    'source'       => 'foo.js',
                    'dependencies' => array(),
                    'attributes'   => array(),
                ),
            ),
            'style' => array(
                'foobar' => array(
                    'source'       => 'foobar.css',
                    'dependencies' => array(),
                    'attributes'   => array('media' => 'all'),
                ),
                'foo' => array(
                    'source'       => 'foo.css',
                    'dependencies' => array('foobar'),
                    'attributes'   => array('media' => 'all'),
                ),
                'hello' => array(
                    'source'       => 'hello.css',
                    'dependencies' => array('jquery'),
                    'attributes'   => array('media' => 'all'),
                ),
            ),
        );

        $dispatcher->shouldReceive('run')->twice()->with('script', $assets)->andReturn('scripted')
            ->shouldReceive('run')->twice()->with('style', $assets)->andReturn('styled');


        $stub = new Container('default', $dispatcher);

        $stub->add('foo', 'foo.js');
        $stub->add('foobar', 'foobar.css');
        $stub->style('foo', 'foo.css', array('foobar'));
        $stub->style('hello', 'hello.css', array('jquery'));

        $this->assertEquals('scripted', $stub->scripts());
        $this->assertEquals('styled', $stub->styles());
        $this->assertEquals('styledscripted', $stub->show());
    }

     /**
     * Test Orchesta\Asset\Container::asset() method return empty string
     * when name is not defined.
     *
     * @test
     */
    public function testAssetMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher[run]');

        $dispatcher->shouldReceive('run')->once()->with('script', array())->andReturn('');

        $stub = new Container('default', $dispatcher);
        $this->assertEquals('', $stub->scripts());
    }
}
