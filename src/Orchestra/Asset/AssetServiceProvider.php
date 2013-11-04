<?php namespace Orchestra\Asset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('orchestra.asset', function ($app) {
            return new Environment($app['orchestra.asset.dispatcher']);
        });

        $this->app->bindShared('orchestra.asset.dispatcher', function () {
            return new Dispatcher($app['files'], $app['html'], $app['path.public']);
        });

        $this->app->bindShared('orchestra.asset.resolver', function () {
            return new DependencyResolver;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('orchestra.asset', 'orchestra.asset.dispatcher', 'orchestra.asset.resolver');
    }
}
