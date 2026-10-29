<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * Not a test of behaviour: with SPLOTNIKOV_DUMP=<dir> it writes every page as a
 * static HTML file (asset links made relative) so the theme can be screenshotted
 * without a PHP host. Skipped otherwise.
 */
class PreviewDumpTest extends TestCase
{
    public function test_dump_every_page_as_static_html(): void
    {
        $dir = getenv('SPLOTNIKOV_DUMP');
        if (! $dir) {
            $this->markTestSkipped('Set SPLOTNIKOV_DUMP=<dir> to write the preview files.');
        }
        is_dir($dir) || mkdir($dir, 0755, true);

        $pages = ['/' => 'index', '/cv' => 'cv', '/contact' => 'contact', '/projects' => 'projects', '/toolkit' => 'toolkit', '/blog' => 'blog', '/blog/strangler-fig-what-the-bridge-taught-me' => 'post'];
        $map = [];
        foreach ($pages as $path => $file) {
            $map['href="http://splotnikov.dev'.$path.'"'] = 'href="'.$file.'.html"';
            $map['href="http://splotnikov.dev'.$path.'#'] = 'href="'.$file.'.html#';
            $map['action="http://splotnikov.dev'.$path.'"'] = 'action="'.$file.'.html"';
        }
        $map['https://splotnikov.dev/vendor/splotnikov/'] = 'vendor/splotnikov/';
        foreach ($pages as $path => $file) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            file_put_contents($dir.'/'.$file.'.html', strtr($html, $map));
        }
        $this->assertFileExists($dir.'/index.html');
    }
}
