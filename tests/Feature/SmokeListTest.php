<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * scripts/smoke-urls.txt is what the live smoke check (every six hours) fetches. This keeps the list honest: every
 * path is in the sitemap or is a crawler file or the deliberate 404 address, and every marker is in the rendered page.
 */
class SmokeListTest extends TestCase
{
    private const FIXED = ['/feed.xml', '/sitemap.xml', '/robots.txt', '/site.webmanifest', '/nl/does-not-exist'];

    /** @return list<array{int, string, string}> */
    private function rows(): array
    {
        $rows = [];
        foreach (file(__DIR__.'/../../scripts/smoke-urls.txt', FILE_IGNORE_NEW_LINES) ?: [] as $line) {
            if (trim($line) === '' || str_starts_with(ltrim($line), '#')) {
                continue;
            }
            $parts = array_map('trim', explode('|', $line, 3));
            $this->assertCount(3, $parts, "Smoke line needs status | path | marker: $line");
            $rows[] = [(int) $parts[0], $parts[1], $parts[2]];
        }

        return $rows;
    }

    public function test_the_booking_page_is_in_the_list(): void
    {
        $booking = (string) config('splotnikov-dev.booking_url');
        $this->assertStringStartsWith('https://', $booking);
        $this->assertContains($booking, array_column($this->rows(), 1), 'The booking page the buttons lead to is not in scripts/smoke-urls.txt');
    }

    public function test_smoke_list_is_covered_by_the_sitemap_and_the_rendered_pages(): void
    {
        $sitemap = (string) $this->get('http://splotnikov.dev/sitemap.xml')->assertOk()->getContent();
        $rows = $this->rows();
        $this->assertGreaterThanOrEqual(12, count($rows));
        $paths = array_column($rows, 1);
        $this->assertContains('/', $paths);
        $this->assertContains('/nl', $paths);
        $this->assertContains('/nl/contact', $paths);

        foreach ($rows as [$status, $path, $marker]) {
            if (str_starts_with($path, 'https://')) {
                continue; // external addresses are fetched live only, see test_the_booking_page_is_in_the_list
            }
            if (! in_array($path, self::FIXED, true)) {
                $this->assertStringContainsString('<loc>https://splotnikov.dev'.($path === '/' ? '/' : $path).'</loc>', $sitemap, "$path is not in the sitemap");
            }
            $response = $this->get('http://splotnikov.dev'.$path);
            $response->assertStatus($status);
            $this->assertStringContainsString($marker, (string) $response->getContent(), "Marker of $path is not in the rendered page: $marker");
        }
    }
}
