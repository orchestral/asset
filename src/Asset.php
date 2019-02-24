<?php

namespace Orchestra\Asset;

use Illuminate\Contracts\Support\Htmlable;

class Asset implements Htmlable
{
    /**
     * Asset Dispatcher instance.
     *
     * @var \Orchestra\Asset\Dispatcher
     */
    protected $dispatcher;

    /**
     * The asset container name.
     *
     * @var string
     */
    protected $name;

    /**
     * The asset container path prefix.
     *
     * @var string
     */
    protected $path = null;

    /**
     * All of the registered assets.
     *
     * @var array
     */
    protected $assets = [];

    /**
     * Create a new asset container instance.
     *
     * @param  string  $name
     * @param  \Orchestra\Asset\Dispatcher  $dispatcher
     */
    public function __construct(string $name, Dispatcher $dispatcher)
    {
        $this->name = $name;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Enable asset versioning.
     *
     * @return $this
     */
    final public function addVersioning(): self
    {
        $this->dispatcher->addVersioning();

        return $this;
    }

    /**
     * Disable asset versioning.
     *
     * @return $this
     */
    final public function removeVersioning(): self
    {
        $this->dispatcher->removeVersioning();

        return $this;
    }

    /**
     * Set the asset container path prefix.
     *
     * @param  string|null  $path
     *
     * @return $this
     */
    public function prefix(?string $path = null)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Add an asset to the container.
     *
     * The extension of the asset source will be used to determine the type
     * of asset being registered (CSS or JavaScript). When using a non-standard
     * extension, the style/script methods may be used to register assets.
     *
     * <code>
     *     // Add an asset to the container
     *     Orchestra\Asset::container()->add('jquery', 'js/jquery.js');
     *
     *     // Add an asset that has dependencies on other assets
     *     Orchestra\Asset::add('jquery', 'js/jquery.js', 'jquery-ui');
     *
     *     // Add an asset that should have attributes applied to its tags
     *     Orchestra\Asset::add('jquery', 'js/jquery.js', null, array('defer'));
     * </code>
     *
     * @param  string|array  $name
     * @param  string  $source
     * @param  string|array  $dependencies
     * @param  string|array  $attributes
     * @param  string|array  $replaces
     *
     * @return $this
     */
    public function add(
        $name,
        string $source,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        $type = (\pathinfo($source, PATHINFO_EXTENSION) == 'css') ? 'style' : 'script';

        return $this->$type($name, $source, $dependencies, $attributes, $replaces);
    }

    /**
     * Add a CSS file to the registered assets.
     *
     * @param  string|array  $name
     * @param  string  $source
     * @param  string|array  $dependencies
     * @param  string|array  $attributes
     * @param  string|array  $replaces
     *
     * @return $this
     */
    public function style(
        $name,
        string $source,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        if (! \array_key_exists('media', $attributes)) {
            $attributes['media'] = 'all';
        }

        $this->register('style', $name, $source, $dependencies, $attributes, $replaces);

        return $this;
    }

    /**
     * Add a JavaScript file to the registered assets.
     *
     * @param  string|array  $name
     * @param  string  $source
     * @param  string|array  $dependencies
     * @param  string|array  $attributes
     * @param  string|array  $replaces
     *
     * @return $this
     */
    public function script(
        $name,
        string $source,
        $dependencies = [],
        $attributes = [],
        $replaces = []
    ) {
        $this->register('script', $name, $source, $dependencies, $attributes, $replaces);

        return $this;
    }

    /**
     * Add an asset to the array of registered assets.
     *
     * @param  string  $type
     * @param  string|array  $name
     * @param  string  $source
     * @param  string|array  $dependencies
     * @param  string|array  $attributes
     * @param  string|array  $replaces
     *
     * @return void
     */
    protected function register(
        string $type,
        $name,
        string $source,
        $dependencies,
        $attributes,
        $replaces
    ): void {
        $dependencies = (array) $dependencies;
        $attributes = (array) $attributes;
        $replaces = (array) $replaces;

        if (\is_array($name)) {
            $replaces = \array_merge($name, $replaces);
            $name = '*';
        }

        $this->assets[$type][$name] = [
            'source' => $source,
            'dependencies' => $dependencies,
            'attributes' => $attributes,
            'replaces' => $replaces,
        ];
    }

    /**
     * Get the links to all of the registered CSS assets.
     *
     * @return string
     */
    public function styles(): string
    {
        return $this->group('style');
    }

    /**
     * Get the links to all of the registered JavaScript assets.
     *
     * @return string
     */
    public function scripts(): string
    {
        return $this->group('script');
    }

    /**
     * Get the links to all the registered CSS and JavaScript assets.
     *
     * @return string
     */
    public function show(): string
    {
        return $this->group('script').$this->group('style');
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml(): string
    {
        return $this->show();
    }

    /**
     * Get all of the registered assets for a given type / group.
     *
     * @param  string  $group
     *
     * @return string
     */
    protected function group(string $group): string
    {
        return $this->dispatcher->run($group, $this->assets, $this->path);
    }
}
