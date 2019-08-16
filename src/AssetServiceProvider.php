<?php

namespace Orchestra\Asset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;

class AssetServiceProvider extends ServiceProvider implements DeferrableProvider
{
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
    protected function registerAsset(): void
    {
        $this->app->singleton('orchestra.asset', static function (Container $app) {
            return new Factory($app->make('orchestra.asset.dispatcher'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerDispatcher(): void
    {
        $this->app->singleton('orchestra.asset.dispatcher', static function ($app) {
            return new Dispatcher(
                $app->make('files'),
                $app->make('html'),
                $app->make('orchestra.asset.resolver'),
                $app->publicPath()
            );
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    protected function registerResolver(): void
    {
        $this->app->singleton('orchestra.asset.resolver', static function () {
            return new DependencyResolver();
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
