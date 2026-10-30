<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        // A missing page on this host gets the site's own 404, not the framework's bare one. Other hosts of the
        // same app keep theirs.
        $handler = $this->app->make(ExceptionHandler::class);
        if (method_exists($handler, 'renderable')) {
            $handler->renderable(function (NotFoundHttpException $e, Request $request) {
                if ($request->getHost() !== config('splotnikov-dev.domain') || $request->expectsJson()) {
                    return null;
                }

                return response()->view('splotnikov::404', [], 404);
            });
        }

        $this->publishes([
            __DIR__.'/../config/splotnikov-dev.php' => config_path('splotnikov-dev.php'),
        ], 'splotnikov-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/splotnikov'),
        ], 'splotnikov-dev-assets');
    }
}
