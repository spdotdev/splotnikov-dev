<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Every icon the head and the manifest name exists at the size it states; the colors match the light tokens. */
class IconsAndManifestTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    private function public(string $file): string
    {
        return __DIR__.'/../../public/'.$file;
    }

    /** @return array{0: int, 1: int} */
    private function pngSize(string $path): array
    {
        $this->assertFileExists($path);
        $head = (string) file_get_contents($path, false, null, 0, 24);
        $this->assertSame("\x89PNG\r\n\x1a\n", substr($head, 0, 8), $path.' is not a PNG');
        /** @var array{1: int, 2: int} $u */
        $u = unpack('N2', substr($head, 16, 8));

        return [$u[1], $u[2]];
    }

    public function test_the_manifest_icons_exist_at_the_stated_size(): void
    {
        $m = (array) json_decode((string) $this->get(self::HOST.'/site.webmanifest')->assertOk()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertNotEmpty($m['icons']);
        foreach ($m['icons'] as $icon) {
            $this->assertStringStartsWith('/vendor/splotnikov/', $icon['src']);
            [$w, $h] = $this->pngSize($this->public(substr($icon['src'], strlen('/vendor/splotnikov/'))));
            $this->assertSame($icon['sizes'], $w.'x'.$h, $icon['src']);
        }
        $this->assertContains('512x512', array_column($m['icons'], 'sizes'));
        $this->assertContains('192x192', array_column($m['icons'], 'sizes'));
    }

    public function test_the_head_icons_exist_at_the_stated_size_and_the_apple_icon_is_180(): void
    {
        $html = (string) $this->get(self::HOST.'/')->assertOk()->getContent();
        preg_match_all('#<link rel="(icon|apple-touch-icon)"([^>]*)>#', $html, $links, PREG_SET_ORDER);
        $this->assertGreaterThanOrEqual(4, count($links));
        $apple = 0;
        foreach ($links as $l) {
            preg_match('#href="[^"]*/vendor/splotnikov/([^"]+)"#', $l[2], $href);
            $this->assertNotEmpty($href, $l[0]);
            if (str_ends_with($href[1], '.png')) {
                preg_match('#sizes="(\d+)x(\d+)"#', $l[2], $s);
                $this->assertNotEmpty($s, $l[0].' states no size');
                $this->assertSame([(int) $s[1], (int) $s[2]], $this->pngSize($this->public($href[1])), $href[1]);
            } else {
                $this->assertFileExists($this->public($href[1]));
            }
            if ($l[1] === 'apple-touch-icon') {
                $apple++;
                $this->assertSame([180, 180], $this->pngSize($this->public($href[1])));
            }
        }
        $this->assertSame(1, $apple);
    }

    public function test_the_manifest_colors_match_the_light_tokens_of_the_stylesheet(): void
    {
        $css = (string) file_get_contents($this->public('site.css'));
        $this->assertSame(1, preg_match('/^:root\{--bg:(#[0-9a-f]{6});/m', $css, $bg));
        $m = (array) json_decode((string) $this->get(self::HOST.'/site.webmanifest')->getContent(), true);
        $this->assertSame($bg[1], $m['theme_color']);
        $this->assertSame($bg[1], $m['background_color']);
        $this->assertStringContainsString('<meta name="theme-color" content="'.$bg[1].'"', (string) $this->get(self::HOST.'/')->getContent());
    }

    public function test_the_svg_icon_has_no_script_and_no_event_handlers(): void
    {
        $svg = (string) file_get_contents($this->public('icon.svg'));
        $this->assertStringContainsString('<svg', $svg);
        $this->assertDoesNotMatchRegularExpression('/<script|\son[a-z]+\s*=|javascript:|<foreignObject|<iframe/i', $svg);
    }
}
