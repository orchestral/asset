<?php namespace Orchestra\Asset\TestCase;

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
	 * Test contructing Orchestra\View\Theme\ThemeManager.
	 *
	 * @test
	 */
	public function testConstructMethod()
	{
		$app = $this->app;
		$app['html'] = $html = m::mock('Html');
		$app['files'] = $files = m::mock('Filesystem');

		$html->shouldReceive('script')->twice()->with('foo.js', m::any())->andReturn('foo')
			->shouldReceive('style')->twice()->with('foobar.css', m::any())->andReturn('foobar')
			->shouldReceive('style')->twice()->with('foo.css', m::any())->andReturn('foo')
			->shouldReceive('style')->twice()->with('hello.css', m::any())->andReturn('hello');
		$files->shouldReceive('lastModified')->times(8)->andReturn('');

		$env  = new Environment($app);
		$stub = $env->container();

		$refl = new \ReflectionObject($stub);
		$useVersioning = $refl->getProperty('useVersioning');
		$useVersioning->setAccessible(true);

		$this->assertFalse($useVersioning->getValue($stub));
		$stub->addVersioning();
		$this->assertTrue($useVersioning->getValue($stub));

		$this->assertInstanceOf('\Orchestra\Asset\Container', $stub);

		$stub->add('foo', 'foo.js');
		$stub->add('foobar', 'foobar.css');
		$stub->style('foo', 'foo.css', array('foobar'));
		$stub->style('hello', 'hello.css', array('jquery'));

		$this->assertEquals('foo', $stub->scripts());
		$this->assertEquals('foobarfoohello', $stub->styles());
		$this->assertEquals('foobarfoohellofoo', $stub->show());

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
