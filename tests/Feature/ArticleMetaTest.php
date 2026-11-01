<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

class ArticleMetaTest extends TestCase
{
    public function test_every_post_carries_article_times_and_author_in_the_head(): void
    {
        $this->assertNotEmpty(Posts::all());
        foreach (Posts::all() as $post) {
            $html = (string) $this->get('http://splotnikov.dev/blog/'.$post['slug'])->assertOk()->getContent();
            $this->assertStringContainsString('<meta property="og:type" content="article" />', $html);
            preg_match('/<meta property="article:published_time" content="([^"]+)"/', $html, $pub);
            preg_match('/<meta property="article:modified_time" content="([^"]+)"/', $html, $mod);
            $this->assertNotEmpty($pub, $post['slug'].' lacks article:published_time');
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $pub[1]);
            $this->assertSame($post['date'], $pub[1]);
            // No edit date is stored yet (issue 14), so the modified time equals the published time.
            $this->assertSame($pub[1], $mod[1] ?? null);
            $this->assertStringContainsString('<meta property="article:author" content="https://splotnikov.dev" />', $html);
        }
    }

    public function test_pages_that_are_not_articles_carry_no_article_meta(): void
    {
        foreach (['/', '/blog', '/cv'] as $path) {
            $this->assertStringNotContainsString('article:published_time', (string) $this->get('http://splotnikov.dev'.$path)->getContent(), $path);
        }
    }
}
