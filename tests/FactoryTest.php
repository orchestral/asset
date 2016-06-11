<?php

namespace Orchestra\Asset\TestCase;

use Mockery as m;
use Orchestra\Asset\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test contructing Orchestra\Asset\Factory.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $dispatcher->shouldReceive('addVersioning')->once()->andReturn(null)
            ->shouldReceive('removeVersioning')->once()->andReturn(null);

        $env  = new Factory($dispatcher);
        $stub = $env->container();

        $this->assertInstanceOf('\Orchestra\Asset\Asset', $stub);

        $env->addVersioning()->removeVersioning();
    }
}
