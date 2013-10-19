<?php namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Illuminate\Container\Container as App;
use Orchestra\Asset\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new App;
        $this->app['path.public'] = '/var/public';
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test constructing Orchesta\Asset\Container.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $app = $this->app;
        $app['html'] = $html = m::mock('Html');
        $app['files'] = $files = m::mock('Filesystem');

        $html->shouldReceive('script')->once()->with('foo.js', m::any())->andReturn('foo')
            ->shouldReceive('style')->once()->with('foobar.css', m::any())->andReturn('foobar')
            ->shouldReceive('style')->once()->with('foo.css', m::any())->andReturn('foo')
            ->shouldReceive('style')->once()->with('hello.css', m::any())->andReturn('hello');
        $files->shouldReceive('lastModified')->times(4)->andReturn('');

        $stub = new Container($app, 'default', true);

        $this->assertInstanceOf('\Orchestra\Asset\Container', $stub);
        $this->assertEquals('', $stub->styles());

        $stub->add('foo', 'foo.js');
        $stub->add('foobar', 'foobar.css');
        $stub->style('foo', 'foo.css', array('foobar'));
        $stub->style('hello', 'hello.css', array('jquery'));

        $this->assertEquals('foo', $stub->scripts());
        $this->assertEquals('foobarfoohello', $stub->styles());
    }

     /**
     * Test Orchesta\Asset\Container::asset() method return empty string
     * when name is not defined.
     *
     * @test
     */
    public function testAssetMethod()
    {
        $app  = $this->app;
        $stub = new Container($app, 'default', true);

        $this->assertEquals('', $stub->asset('script', 'foo'));
    }
}
