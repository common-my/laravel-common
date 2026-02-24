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
            __DIR__ . '/Config/baseConfig.php',
            'base'
        );
    }
}
