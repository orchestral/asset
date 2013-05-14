<?php namespace Orchestra\Asset\Tests;

use Mockery as m;
use Orchestra\Asset\Environment;

class EnvironmentTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app = new \Illuminate\Container\Container;
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
	 * Test contructing Orchestra\View\Theme\ThemeManager.
	 *
	 * @test
	 */
	public function testConstructMethod()
	{
		$app = $this->app;
		$app['html'] = $html = m::mock('Html');

		$html->shouldReceive('script')->once()->with('foo.js', m::any())->andReturn('foo')
			->shouldReceive('style')->once()->with('foobar.css', m::any())->andReturn('foobar')
			->shouldReceive('style')->once()->with('foo.css', m::any())->andReturn('foo');

		$stub = new Environment($app);

		$this->assertInstanceOf('\Orchestra\Asset\Container', $stub->container());

		$stub->add('foo', 'foo.js');
		$stub->add('foobar', 'foobar.css');
		$stub->style('foo', 'foo.css', array('foobar'));

		$this->assertEquals('foo', $stub->scripts());
		$this->assertEquals('foobarfoo', $stub->styles());
	}
}
