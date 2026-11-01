<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * Measured in round 15 (Playwright, 390 and 1366 px, every page): the largest contentful paint is text on every page
 * (a lead paragraph or the h1), never an image. So no image needs fetchpriority, the images in the first screen stay
 * eager, and the ones below it load lazily. Every image decodes off the main thread.
 */
class ImagesTest extends TestCase
{
    private const PAGES = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog', '/nl', '/nl/cv', '/nl/contact'];

    /** @return array<int, string> */
    private function images(string $path): array
    {
        $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
        preg_match_all('/<img\b[^>]*>/', $html, $m);

        return $m[0];
    }

    public function test_every_image_decodes_async_with_a_size_and_an_alt(): void
    {
        foreach (self::PAGES as $path) {
            foreach ($this->images($path) as $img) {
                $this->assertStringContainsString('decoding="async"', $img, $path.': '.$img);
                $this->assertMatchesRegularExpression('/\bwidth="\d+"/', $img, $path);
                $this->assertMatchesRegularExpression('/\bheight="\d+"/', $img, $path);
                $this->assertStringContainsString(' alt="', $img, $path);
            }
        }
    }

    public function test_first_screen_images_are_eager_and_images_below_it_are_lazy(): void
    {
        foreach (['/', '/nl', '/cv', '/nl/cv'] as $path) {
            foreach ($this->images($path) as $img) {
                $this->assertStringNotContainsString('loading="lazy"', $img, $path.' has its portrait in the first screen');
            }
        }
        foreach (['/contact', '/nl/contact'] as $path) {
            $images = $this->images($path);
            $this->assertNotEmpty($images);
            foreach ($images as $img) {
                $this->assertStringContainsString('loading="lazy"', $img, $path.' loads an image the visitor has not asked for');
            }
        }
    }
}
