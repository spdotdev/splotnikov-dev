<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Support\ServiceProvider;

class SplotnikovDevServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/splotnikov-dev.php', 'splotnikov-dev');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'splotnikov');

        $this->publishes([
            __DIR__.'/../config/splotnikov-dev.php' => config_path('splotnikov-dev.php'),
        ], 'splotnikov-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/splotnikov'),
        ], 'splotnikov-dev-assets');
    }
}
