<?php

namespace Orchestra\Asset\TestCase\Unit;

use Mockery as m;
use Orchestra\Asset\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Test contructing Orchestra\Asset\Factory.
     *
     * @test
     */
    public function it_can_setup_a_factory()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $dispatcher->shouldReceive('addVersioning')->once()->andReturn(null)
            ->shouldReceive('removeVersioning')->once()->andReturn(null);

        $env = new Factory($dispatcher);
        $stub = $env->container();

        $this->assertInstanceOf('\Orchestra\Asset\Asset', $stub);

        $env->addVersioning()->removeVersioning();
    }
}
