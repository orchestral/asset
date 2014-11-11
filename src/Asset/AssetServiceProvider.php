<?php namespace Orchestra\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResolver();

        $this->registerDispatcher();

        $this->registerAsset();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerAsset()
    {
        $this->app->singleton('orchestra.asset', function ($app) {
            return new Factory($app['orchestra.asset.dispatcher']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerDispatcher()
    {
        $this->app->singleton('orchestra.asset.dispatcher', function ($app) {
            return new Dispatcher(
                $app['files'],
                $app['html'],
                $app['orchestra.asset.resolver'],
                $app['path.public']
            );
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerResolver()
    {
        $this->app->singleton('orchestra.asset.resolver', function () {
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
        return ['orchestra.asset', 'orchestra.asset.dispatcher', 'orchestra.asset.resolver'];
    }
}
