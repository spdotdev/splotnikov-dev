<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Http\Controllers\SiteController;
use Spdotdev\SplotnikovDev\Http\Middleware\DutchVersion;

// Content pages start no session and set no cookie (2026-10-30): nothing on them needs one, a visitor who only reads
// gets no cookie at all, and the server does no session read or write per page. Only the contact page, which holds
// a form with a CSRF token and a flash message, and the POST behind it keep the full web group. The three CSRF
// class names cover the Laravel versions (13 uses PreventRequestForgery in the web group).
$noSession = [
    StartSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
    ValidateCsrfToken::class,
    VerifyCsrfToken::class,
    AddQueuedCookiesToResponse::class,
];

Route::domain(config('splotnikov-dev.domain'))
    ->middleware('web')
    ->group(function () use ($noSession) {
        Route::get('/contact', [SiteController::class, 'contact'])->name('splotnikov.contact.page');
        Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:6,1')->name('splotnikov.contact');
        Route::prefix('nl')->middleware(DutchVersion::class)->group(function () {
            Route::get('/contact', [SiteController::class, 'contact']);
        });

        Route::withoutMiddleware($noSession)->group(function () {
            Route::get('/', [SiteController::class, 'index'])->name('splotnikov.home');
            Route::get('/cv', [SiteController::class, 'cv'])->name('splotnikov.cv');
            Route::get('/projects', [SiteController::class, 'projects'])->name('splotnikov.projects');
            Route::get('/toolkit', [SiteController::class, 'toolkit'])->name('splotnikov.toolkit');
            Route::get('/blog', [SiteController::class, 'blog'])->name('splotnikov.blog');
            Route::get('/blog/{slug}', [SiteController::class, 'post'])->where('slug', '[a-z0-9-]+')->name('splotnikov.post');

            // The Dutch version of the pages that exist in full in both languages (see Dutch.php).
            Route::prefix('nl')->middleware(DutchVersion::class)->group(function () {
                Route::get('/', [SiteController::class, 'index'])->name('splotnikov.nl.home');
                Route::get('/cv', [SiteController::class, 'cv']);
                Route::get('/projects', [SiteController::class, 'projects']);
                Route::get('/toolkit', [SiteController::class, 'toolkit']);
            });

            // Crawler / PWA files served at the site root.
            Route::get('/robots.txt', [SiteController::class, 'robots'])->name('splotnikov.robots');
            Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('splotnikov.sitemap');
            Route::get('/site.webmanifest', [SiteController::class, 'webmanifest'])->name('splotnikov.webmanifest');
        });
    });
