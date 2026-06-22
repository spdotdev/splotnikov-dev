<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\SplotnikovDev\Http\Controllers\SiteController;

Route::domain(config('splotnikov-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('splotnikov.home');
        Route::get('/cv', [SiteController::class, 'cv'])->name('splotnikov.cv');

        // Crawler / PWA files served at the site root.
        Route::get('/robots.txt', [SiteController::class, 'robots'])->name('splotnikov.robots');
        Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('splotnikov.sitemap');
        Route::get('/site.webmanifest', [SiteController::class, 'webmanifest'])->name('splotnikov.webmanifest');
    });
