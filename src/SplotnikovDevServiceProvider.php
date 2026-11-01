<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // @sda('Event name') writes the attribute the sd-analytics script reads on a click, and only when a site key
        // is set: without the key the markup carries nothing and nothing is sent.
        Blade::directive('sda', fn (string $event): string => "<?php echo config('splotnikov-dev.analytics_key') ? ' data-sda-event=\"'.e($event).'\"' : ''; ?>");

        if ($this->app->runningInConsole()) {
            $this->commands([Console\ContactLeadsCommand::class]);
        }

        // A missing page on this host gets the site's own 404, not the framework's bare one. Other hosts of the
        // same app keep theirs.
        /** @var object $handler the app's handler; only Laravel's own Handler has renderable(), others are left alone */
        $handler = $this->app->make(ExceptionHandler::class);
        if (method_exists($handler, 'renderable')) {
            $handler->renderable(function (NotFoundHttpException $e, Request $request) {
                if ($request->getHost() !== config('splotnikov-dev.domain') || $request->expectsJson()) {
                    return null;
                }

                // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom)
                $html = view('splotnikov::404')->render();
                if ($request->is('nl') || $request->is('nl/*')) {
                    // The Dutch twin of a missing page is Dutch, like the pages it stands next to.
                    $html = (string) preg_replace('#<html lang="en"#', '<html lang="nl"', Dutch::links(Dutch::translate($html, Dutch::strings($html))), 1);
                }

                return response($html, 404);
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
