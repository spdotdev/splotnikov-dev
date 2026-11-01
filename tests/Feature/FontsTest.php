<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

class FontsTest extends TestCase
{
    private function css(): string
    {
        return (string) file_get_contents(__DIR__.'/../../public/site.css');
    }

    public function test_every_font_face_swaps_and_every_font_file_is_used(): void
    {
        preg_match_all('/@font-face\{[^}]*\}/', $this->css(), $faces);
        $this->assertCount(4, $faces[0]);
        $used = [];
        foreach ($faces[0] as $face) {
            $this->assertMatchesRegularExpression('/font-display:(swap|optional)/', $face);
            $this->assertStringContainsString('unicode-range', $face);
            preg_match('#url\(fonts/([a-z-]+\.woff2)\)#', $face, $m);
            $used[] = $m[1];
        }
        $shipped = array_map('basename', glob(__DIR__.'/../../public/fonts/*.woff2') ?: []);
        sort($used);
        sort($shipped);
        $this->assertSame($used, $shipped, 'A font file is shipped that no @font-face uses (or the reverse)');
    }

    public function test_preloads_are_the_latin_files_the_css_lists(): void
    {
        $html = (string) $this->get('http://splotnikov.dev/')->getContent();
        preg_match_all('#<link rel="preload" href="[^"]*/fonts/([a-z-]+\.woff2)"#', $html, $m);
        $this->assertSame(['geist-latin.woff2', 'geist-mono-latin.woff2'], $m[1]);
        foreach ($m[1] as $file) {
            $this->assertStringContainsString('url(fonts/'.$file.')', $this->css());
        }
    }

    public function test_pages_use_no_glyph_outside_the_font_subsets(): void
    {
        $set = [];
        preg_match_all('/unicode-range:([^}]*)\}/', $this->css(), $ranges);
        foreach ($ranges[1] as $list) {
            foreach (explode(',', $list) as $r) {
                [$a, $b] = array_pad(explode('-', substr(trim($r), 2)), 2, null);
                for ($c = hexdec($a); $c <= hexdec($b ?? $a); $c++) {
                    $set[$c] = true;
                }
            }
        }
        // The arrows U+2190, U+2192 and U+2197 are in the latin files since round 15: no system font fallback is allowed any more.
        foreach ([0x2190, 0x2191, 0x2192, 0x2193, 0x2197] as $arrow) {
            $this->assertArrayHasKey($arrow, $set, 'U+'.dechex($arrow).' is not in a unicode-range');
        }
        $allowed = [];
        foreach (['/', '/cv', '/projects', '/toolkit', '/contact', '/privacy', '/blog', '/nl', '/nl/cv', '/nl/projects', '/nl/toolkit', '/nl/contact', '/nl/privacy'] as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            $text = html_entity_decode(strip_tags((string) preg_replace('#<(script|style)\b.*?</\1>#s', '', $html)), ENT_QUOTES, 'UTF-8');
            foreach (mb_str_split($text) as $ch) {
                $cp = mb_ord($ch);
                if ($cp > 0x20 && ! isset($set[$cp]) && ! isset($allowed[$cp])) {
                    $this->fail("$path uses U+".strtoupper(dechex($cp)).' ('.$ch.') which neither font file covers');
                }
            }
        }
        $this->assertTrue(true);
    }
}
