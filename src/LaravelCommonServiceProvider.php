<?php

namespace CommonMy\LaravelCommon;

use Illuminate\Support\ServiceProvider;

class LaravelCommonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__ . '/Config/laravel-common.php',
            'laravel-common'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/laravel-common.php' => config_path('laravel-common.php'),
            ], 'laravel-common-config');
        }
    }
}
