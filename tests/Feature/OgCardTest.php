<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * The social card is rebuilt by hand (design/make-og.py). It writes public/og-image.source-hash, a hash of what the
 * card is rendered from. A changed headline or portrait without a rebuild fails here and names the command.
 */
class OgCardTest extends TestCase
{
    public function test_social_card_is_built_from_the_current_source(): void
    {
        $root = __DIR__.'/../../';
        $expected = hash('sha256', (string) file_get_contents($root.'design/og-image.html').(string) file_get_contents($root.'public/portrait.jpg'));
        $this->assertFileExists($root.'public/og-image.source-hash');
        $this->assertSame($expected, trim((string) file_get_contents($root.'public/og-image.source-hash')), 'The social card is stale. Rebuild it: python3 design/make-og.py (then commit public/og-image.png and public/og-image.source-hash)');
    }
}
