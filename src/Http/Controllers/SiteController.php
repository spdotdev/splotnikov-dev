<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Spdotdev\SplotnikovDev\Posts;

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

    public function projects(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::projects');
    }

    public function toolkit(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::toolkit');
    }

    public function blog(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::blog');
    }

    public function post(string $slug): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::post', ['post' => Posts::get($slug)]);
    }

    public function contact(): View
    {
        // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('splotnikov::contact');
    }

    public function robots(): Response
    {
        return $this->staticFile('robots.txt', 'text/plain');
    }

    public function sitemap(): Response
    {
        // The static file plus every article that only exists in sd-cms.
        $xml = (string) file_get_contents(__DIR__.'/../../../public/sitemap.xml');
        $extra = '';
        foreach (Posts::all() as $post) {
            $loc = 'https://splotnikov.dev/blog/'.$post['slug'];
            if (! str_contains($xml, '<loc>'.$loc.'</loc>')) {
                $extra .= "    <url>\n        <loc>".$loc."</loc>\n        <lastmod>".$post['date']."</lastmod>\n        <changefreq>yearly</changefreq>\n        <priority>0.6</priority>\n    </url>\n";
            }
        }
        $xml = str_replace('</urlset>', $extra.'</urlset>', $xml);

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function webmanifest(): Response
    {
        return $this->staticFile('site.webmanifest', 'application/manifest+json');
    }

    /**
     * Serve a shipped static file from the package's public/ directory at the
     * site root (the published vendor path is not at the web root, so crawler
     * and PWA files must be routed explicitly).
     */
    private function staticFile(string $name, string $contentType): Response
    {
        $path = __DIR__.'/../../../public/'.$name;
        abort_unless(is_file($path), 404);

        return response((string) file_get_contents($path), 200, ['Content-Type' => $contentType]);
    }
}
