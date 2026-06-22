<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\SplotnikovDev\Http\Controllers\SiteController;

Route::domain(config('splotnikov-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('splotnikov.home');
        Route::get('/cv', [SiteController::class, 'cv'])->name('splotnikov.cv');
    });
