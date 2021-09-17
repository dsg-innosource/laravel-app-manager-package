<?php

namespace InnoSource\LaravelApplicationManager;

use Illuminate\Support\ServiceProvider;
use InnoSource\LaravelApplicationManager\Console\Commands\SendReport;

class LaravelApplicationManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lam');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'lam');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('lam.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lam'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/lam'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lam'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                SendReport::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'lam');

        // Register the main class to use with the facade
        $this->app->singleton('lam', function () {
            return new LaravelApplicationManager;
        });
    }
}
