<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Dutch;
use Spdotdev\SplotnikovDev\Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    public function test_pages_switched_in_the_browser_carry_the_dutch_attribute_map(): void
    {
        foreach (['/blog', '/blog/strangler-fig-what-the-bridge-taught-me'] as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->getContent();
            $this->assertStringContainsString('Hoofdnavigatie', $html, $path.' lacks the Dutch attribute map');
            $this->assertStringContainsString('aria-label="Main navigation"', $html);
        }
    }

    public function test_every_attribute_in_the_map_is_used_by_the_layout_or_a_page(): void
    {
        $views = '';
        foreach (glob(__DIR__.'/../../resources/views/{,partials/}*.blade.php', GLOB_BRACE) ?: [] as $f) {
            $views .= (string) file_get_contents($f);
        }
        $views .= (string) file_get_contents(__DIR__.'/../../public/site.js');
        foreach (Dutch::attributeMap() as $en => $nl) {
            $this->assertNotSame('', $nl);
            $this->assertStringContainsString(htmlspecialchars($en, ENT_QUOTES), $views, 'The map holds "'.$en.'" but no view writes it');
        }
    }

    public function test_the_script_swaps_and_restores_attributes_and_the_language(): void
    {
        $js = (string) file_get_contents(__DIR__.'/../../public/site.js');
        $this->assertStringContainsString("data-en-' + a", $js);
        $this->assertStringContainsString('T.a[en]', $js);
        $this->assertStringContainsString('root.lang = l;', $js);
    }
}
