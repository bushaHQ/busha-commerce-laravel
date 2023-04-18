<?php

namespace Busha\Commerce;

use Illuminate\Support\ServiceProvider;

class BushaServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/busha.php', 'busha');

        // Register the service the package provides.
        $this->app->bind('busha-commerce', fn ($app) =>
            new BushaCommerce($app)
        );

        $this->app->alias('busha-commerce', BushaCommerce::class);
    }

    /**
     * Get the services provided by the provider
     * @return array
     */
    public function provides()
    {
        return ['busha-commerce'];
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/busha.php' => config_path('busha.php'),
        ], 'config');

    }

}