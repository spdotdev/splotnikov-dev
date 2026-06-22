<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\View\View;

class SiteController
{
    public function index(): View
    {
        return view('splotnikov::portfolio');
    }

    public function cv(): View
    {
        return view('splotnikov::cv');
    }
}
