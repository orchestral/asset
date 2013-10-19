<?php namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Asset\Environment;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
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
        $this->app = new Container;
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
     * Test contructing Orchestra\Asset\Environment.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $app = $this->app;

        $env  = new Environment($app);
        $stub = $env->container();

        $this->assertInstanceOf('\Orchestra\Asset\Container', $stub);

        $refl = new \ReflectionObject($stub);
        $useVersioning = $refl->getProperty('useVersioning');
        $useVersioning->setAccessible(true);

        $this->assertFalse($useVersioning->getValue($stub));
        $stub->addVersioning();
        $this->assertTrue($useVersioning->getValue($stub));

        $stub->removeVersioning();
        $this->assertFalse($useVersioning->getValue($stub));
    }

    /**
     * Test contructing Orchestra\Asset\Environment throws exception due to
     * self dependent.
     *
     * @expectedException \RuntimeException
     */
    public function testConstructMethodThrowsExceptionDueSelfDependent()
    {
        $app = $this->app;
        $stub = new Environment($app);
        $stub->style('foo', 'foo.css', array('foo'));
        $stub->styles();
    }

    /**
     * Test contructing Orchestra\Asset\Environment throws exception due to
     * circular dependent.
     *
     * @expectedException \RuntimeException
     */
    public function testConstructMethodThrowsExceptionDueCircularDependent()
    {
        $app = $this->app;
        $stub = new Environment($app);
        $stub->style('foo', 'foo.css', array('foobar'));
        $stub->style('foobar', 'foobar.css', array('foo'));
        $stub->styles();
    }
}
