<?php namespace Orchestra\Asset;

class Environment
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
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
     * @param  \Illuminate\Foundation\Application   $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get an asset container instance.
     *
     * <code>
     *     // Get the default asset container
     *     $container = Orchestra\Asset::container();
     *
     *     // Get a named asset container
     *     $container = Orchestra\Asset::container('footer');
     * </code>
     *
     * @param  string   $container
     * @return Container
     */
    public function container($container = 'default')
    {
        if (! isset($this->containers[$container])) {
            $this->containers[$container] = new Container($this->app, $container);
        }

        return $this->containers[$container];
    }

    /**
     * Magic Method for calling methods on the default container.
     *
     * <code>
     *     // Call the "styles" method on the default container
     *     echo Orchestra\Asset::styles();
     *
     *     // Call the "add" method on the default container
     *     Orchestra\Asset::add('jquery', 'js/jquery.js');
     * </code>
     *
     * @param  string   $method
     * @param  array    $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->container(), $method), $parameters);
    }
}
