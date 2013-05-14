<?php namespace Orchestra\Asset;

class Environment {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * All of the instantiated asset containers.
	 *
	 * @var array
	 */
	protected $containers = array();

	/**
	 * Construct a new environment.
	 *
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Get an asset container instance.
	 *
	 * <code>
	 *		// Get the default asset container
	 *		$container = Asset::container();
	 *
	 *		// Get a named asset container
	 *		$container = Asset::container('footer');
	 * </code>
	 *
	 * @access public	
	 * @param  string   $container
	 * @return Orchestra\Html\Asset\Container
	 */
	public function container($container = 'default')
	{
		if ( ! isset($this->containers[$container]))
		{
			$this->containers[$container] = new Container($this->app, $container);
		}

		return $this->containers[$container];
	}

	/**
	 * Magic Method for calling methods on the default container.
	 *
	 * <code>
	 *		// Call the "styles" method on the default container
	 *		echo Asset::styles();
	 *
	 *		// Call the "add" method on the default container
	 *		Asset::add('jquery', 'js/jquery.js');
	 * </code>
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->container(), $method), $parameters);
	}
}
