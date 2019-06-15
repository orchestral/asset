<?php

namespace Orchestra\Asset;

use Illuminate\Support\Traits\ForwardsCalls;

class Factory
{
    use ForwardsCalls;

    /**
     * Asset Dispatcher instance.
     *
     * @var \Orchestra\Asset\Dispatcher
     */
    protected $dispatcher;

    /**
     * All of the instantiated asset containers.
     *
     * @var array
     */
    protected $containers = [];

    /**
     * Construct a new environment.
     *
     * @param  \Orchestra\Asset\Dispatcher  $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
     * @param  string  $container
     *
     * @return \Orchestra\Asset\Asset
     */
    public function container(string $container = 'default'): Asset
    {
        if (! isset($this->containers[$container])) {
            $this->containers[$container] = new Asset($container, $this->dispatcher);
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
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->container(), $method, $parameters);
    }
}
