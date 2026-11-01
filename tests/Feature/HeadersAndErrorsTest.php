<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Round 12: cache headers and content types of the crawler files, sitemap and feed dates, the 404 page. */
class HeadersAndErrorsTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    public function test_crawler_and_feed_files_are_cacheable_for_an_hour_with_a_charset(): void
    {
        $types = [
            '/feed.xml' => 'application/atom+xml; charset=UTF-8',
            '/sitemap.xml' => 'application/xml; charset=UTF-8',
            '/robots.txt' => 'text/plain; charset=UTF-8',
            '/site.webmanifest' => 'application/manifest+json; charset=UTF-8',
        ];
        foreach ($types as $path => $type) {
            $res = $this->get(self::HOST.$path)->assertOk();
            $this->assertSame($type, $res->headers->get('Content-Type'), $path);
            $cache = (string) $res->headers->get('Cache-Control');
            $this->assertStringContainsString('public', $cache, $path);
            $this->assertMatchesRegularExpression('/max-age=(\d+)/', $cache, $path);
            preg_match('/max-age=(\d+)/', $cache, $m);
            $this->assertGreaterThanOrEqual(3600, (int) $m[1], $path);
            $this->assertEmpty($res->headers->getCookies(), $path.' sets no cookie');
        }
    }

    public function test_sitemap_dates_are_real_dates_and_the_blog_index_follows_the_newest_post(): void
    {
        Schema::create('cms_articles', function ($t) {
            $t->id();
        });
        if (! class_exists('Spdotdev\\SdCms\\Models\\CmsArticle')) {
            eval('namespace Spdotdev\\SdCms\\Models; class CmsArticle { public static array $rows = []; public static function published(string $site) { return new class { public function get() { return new \\Illuminate\\Support\\Collection(CmsArticle::$rows); } }; } }');
        }
        $cms = 'Spdotdev\\SdCms\\Models\\CmsArticle';
        $cms::$rows = [(object) ['slug' => 'newest', 'title' => 'Newest', 'summary' => 'S', 'body' => 'B', 'tags' => [], 'published_at' => '2027-03-04 09:00:00', 'title_nl' => null, 'summary_nl' => null]];
        try {
            $xml = (string) $this->get(self::HOST.'/sitemap.xml')->getContent();
            preg_match_all('#<lastmod>([^<]*)</lastmod>#', $xml, $m);
            foreach ($m[1] as $d) {
                $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $d);
                $this->assertNotFalse(\DateTime::createFromFormat('!Y-m-d', $d), $d);
                $this->assertSame($d, \DateTime::createFromFormat('!Y-m-d', $d)->format('Y-m-d'), $d.' is a real calendar date');
            }
            $this->assertMatchesRegularExpression('#<loc>https://splotnikov\.dev/blog</loc>\s*<lastmod>2027-03-04</lastmod>#', $xml);
            $feed = simplexml_load_string((string) $this->get(self::HOST.'/feed.xml')->getContent());
            $this->assertSame('2027-03-04T00:00:00Z', (string) $feed->updated, 'the feed is as new as its newest entry');
        } finally {
            $cms::$rows = [];
            Schema::drop('cms_articles');
        }
        // Without CMS articles the blog index carries the newest built-in post date.
        $xml = (string) $this->get(self::HOST.'/sitemap.xml')->getContent();
        $newest = max(array_column(Posts::builtIn(), 'date'));
        $this->assertMatchesRegularExpression('#<loc>https://splotnikov\.dev/blog</loc>\s*<lastmod>'.$newest.'</lastmod>#', $xml);
    }

    public function test_the_404_page_offers_the_four_useful_links_in_both_languages_and_is_not_indexed(): void
    {
        foreach (['/nope' => 'en', '/blog/nope' => 'en', '/nl/nope' => 'nl'] as $path => $lang) {
            $res = $this->get('https://splotnikov.dev'.$path)->assertNotFound();
            $html = (string) $res->getContent();
            $this->assertStringContainsString('<meta name="robots" content="noindex, follow" />', $html, $path);
            $this->assertStringContainsString('<html lang="'.$lang.'"', $html, $path);
            $this->assertEmpty($res->headers->getCookies(), $path.' sets no cookie');
            preg_match('#<article class="wrap page">(.*?)</article>#s', $html, $a);
            foreach (['', '/projects', '/cv', '/contact'] as $href) {
                $prefix = $lang === 'nl' ? '/nl' : '';
                $this->assertMatchesRegularExpression('#<a class="btn[^"]*" href="https://splotnikov\.dev'.preg_quote($prefix.$href, '#').'"#', $a[1], $path.' links to '.$href);
            }
            $this->assertSame(1, preg_match('#<h1\b#', $html));
            $this->assertStringContainsString($lang === 'nl' ? 'Die pagina bestaat niet' : 'That page does not exist', $html, $path);
        }
    }
}
