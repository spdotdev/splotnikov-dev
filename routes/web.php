<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Http\Controllers\SiteController;

Route::domain(config('splotnikov-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('splotnikov.home');
        Route::get('/cv', [SiteController::class, 'cv'])->name('splotnikov.cv');
        Route::get('/projects', [SiteController::class, 'projects'])->name('splotnikov.projects');
        Route::get('/toolkit', [SiteController::class, 'toolkit'])->name('splotnikov.toolkit');
        Route::get('/blog', [SiteController::class, 'blog'])->name('splotnikov.blog');
        Route::get('/blog/{slug}', [SiteController::class, 'post'])->where('slug', '[a-z0-9-]+')->name('splotnikov.post');
        Route::get('/contact', [SiteController::class, 'contact'])->name('splotnikov.contact.page');
        Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:6,1')->name('splotnikov.contact');

        // Crawler / PWA files served at the site root.
        Route::get('/robots.txt', [SiteController::class, 'robots'])->name('splotnikov.robots');
        Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('splotnikov.sitemap');
        Route::get('/site.webmanifest', [SiteController::class, 'webmanifest'])->name('splotnikov.webmanifest');
    });
