<?php namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Orchestra\Asset\Asset;

class AssetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test constructing Orchestra\Asset\Asset.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

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

        $dispatcher->shouldReceive('run')->twice()->with('script', $assets, null)->andReturn('scripted')
            ->shouldReceive('run')->twice()->with('style', $assets, null)->andReturn('styled');

        $stub = new Asset('default', $dispatcher);

        $stub->add('foo', 'foo.js');
        $stub->add('foobar', 'foobar.css');
        $stub->style('foo', 'foo.css', array('foobar'));
        $stub->style('hello', 'hello.css', array('jquery'));

        $this->assertEquals('scripted', $stub->scripts());
        $this->assertEquals('styled', $stub->styles());
        $this->assertEquals('scriptedstyled', $stub->show());
    }

    /**
     * Test Orchestra\Asset\Asset::prefix() method.
     *
     * @test
     */
    public function testPrefixMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $prefix = '//ajax.googleapis.com/ajax/libs/';
        $assets = array();

        $dispatcher->shouldReceive('run')->once()->with('script', $assets, $prefix)->andReturn('scripted')
            ->shouldReceive('run')->once()->with('style', $assets, $prefix)->andReturn('styled');

        $stub = new Asset('default', $dispatcher);
        $stub->prefix($prefix);

        $this->assertEquals('scriptedstyled', $stub->show());
    }

     /**
     * Test Orchestra\Asset\Asset::asset() method return empty string
     * when name is not defined.
     *
     * @test
     */
    public function testAssetMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $dispatcher->shouldReceive('run')->once()->with('script', array(), null)->andReturn('');

        $stub = new Asset('default', $dispatcher);
        $this->assertEquals('', $stub->scripts());
    }
}
