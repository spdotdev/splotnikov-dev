<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

class SiteTest extends TestCase
{
    public function test_portfolio_renders_on_the_configured_host(): void
    {
        // Routes are bound at boot from config('splotnikov-dev.domain'),
        // whose default is splotnikov.dev. Request that host directly.
        $this->get('http://splotnikov.dev/')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_cv_renders_on_the_configured_host(): void
    {
        $this->get('http://splotnikov.dev/cv')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_robots_txt_is_served_at_the_root(): void
    {
        $this->get('http://splotnikov.dev/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');
    }

    public function test_sitemap_xml_is_served_at_the_root(): void
    {
        $this->get('http://splotnikov.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_webmanifest_is_served_at_the_root(): void
    {
        $this->get('http://splotnikov.dev/site.webmanifest')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }
}
