<?php namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Orchestra\Asset\Environment;
use Orchestra\Asset\Dispatcher;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test contructing Orchestra\Asset\Environment.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $dispatcher->shouldReceive('addVersioning')->once()->andReturn(null)
            ->shouldReceive('removeVersioning')->once()->andReturn(null);

        $env  = new Environment($dispatcher);
        $stub = $env->container();

        $this->assertInstanceOf('\Orchestra\Asset\Container', $stub);

        $env->addVersioning()->removeVersioning();
    }
}
