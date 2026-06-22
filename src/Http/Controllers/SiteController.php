<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\View\View;

class SiteController
{
    public function index(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::portfolio');
    }

    public function cv(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::cv');
    }
}
