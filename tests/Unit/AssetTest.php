<?php

namespace Orchestra\Asset\TestCase\Unit;

use Mockery as m;
use Orchestra\Asset\Asset;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_construct_dispatcher()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $assets = [
            'script' => [
                'foo' => [
                    'source' => 'foo.js',
                    'dependencies' => [],
                    'attributes' => [],
                    'replaces' => [],
                ],
            ],
            'style' => [
                '*' => [
                    'source' => 'all.min.css',
                    'dependencies' => [],
                    'attributes' => ['media' => 'all'],
                    'replaces' => ['foobar', 'foo', 'hello'],
                ],
                'foobar' => [
                    'source' => 'foobar.css',
                    'dependencies' => [],
                    'attributes' => ['media' => 'all'],
                    'replaces' => [],
                ],
                'foo' => [
                    'source' => 'foo.css',
                    'dependencies' => ['foobar'],
                    'attributes' => ['media' => 'all'],
                    'replaces' => [],
                ],
                'hello' => [
                    'source' => 'hello.css',
                    'dependencies' => ['jquery'],
                    'attributes' => ['media' => 'all'],
                    'replaces' => [],
                ],
            ],
        ];

        $dispatcher->shouldReceive('run')->twice()->with('script', $assets, null)->andReturn('scripted')
            ->shouldReceive('run')->twice()->with('style', $assets, null)->andReturn('styled');

        $stub = new Asset('default', $dispatcher);

        $stub->add('foo', 'foo.js');
        $stub->add('foobar', 'foobar.css');
        $stub->style('foo', 'foo.css', ['foobar']);
        $stub->style('hello', 'hello.css', ['jquery']);
        $stub->style(['foobar', 'foo', 'hello'], 'all.min.css');

        $this->assertEquals('scripted', $stub->scripts());
        $this->assertEquals('styled', $stub->styles());
        $this->assertEquals('scriptedstyled', $stub->show());
    }

    /** @test */
    public function it_can_declare_prefix_for_assets()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $prefix = '//ajax.googleapis.com/ajax/libs/';
        $assets = [];

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
    public function it_can_return_empty_string_when_name_is_not_defined()
    {
        $dispatcher = m::mock('\Orchestra\Asset\Dispatcher');

        $dispatcher->shouldReceive('run')->once()->with('script', [], null)->andReturn('');

        $stub = new Asset('default', $dispatcher);
        $this->assertEquals('', $stub->scripts());
    }
}
