<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Round 12: the structured data and the social tags are complete on every page, in both languages. */
class StructuredDataTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    /** @return list<string> */
    private function pages(): array
    {
        $pages = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog', '/nl', '/nl/cv', '/nl/contact', '/nl/projects', '/nl/toolkit', '/nl/privacy'];
        foreach (Posts::all() as $post) {
            $pages[] = '/blog/'.$post['slug'];
        }

        return $pages;
    }

    /** @return list<array<string, mixed>> */
    private function blocks(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);

        return array_map(fn (string $b): array => (array) json_decode($b, true, 512, JSON_THROW_ON_ERROR), $m[1]);
    }

    public function test_every_person_block_has_url_image_and_the_same_links_and_no_email(): void
    {
        foreach ($this->pages() as $path) {
            $html = (string) $this->get(self::HOST.$path)->assertOk()->getContent();
            foreach ($this->blocks($html) as $block) {
                $this->assertArrayNotHasKey('email', $block, $path.' puts an email in structured data');
                $this->assertArrayNotHasKey('telephone', $block, $path);
                $person = ($block['@type'] ?? '') === 'Person' ? $block : (($block['@type'] ?? '') === 'ProfilePage' ? $block['mainEntity'] : null);
                if ($person === null) {
                    continue;
                }
                $this->assertArrayNotHasKey('email', $person, $path);
                $this->assertSame('https://splotnikov.dev', $person['url'], $path);
                $this->assertStringEndsWith('/vendor/splotnikov/portrait.jpg', (string) $person['image'], $path);
                foreach (['https://github.com/spdotdev', 'https://www.linkedin.com/in/stanislavplotnikov/', 'https://scuttle.dev'] as $link) {
                    $this->assertContains($link, $person['sameAs'], $path.' sameAs '.$link);
                }
            }
        }
        foreach (['/', '/cv'] as $path) {
            $types = array_map(fn ($b) => $b['@type'], $this->blocks((string) $this->get(self::HOST.$path)->getContent()));
            $this->assertTrue(in_array('Person', $types, true) || in_array('ProfilePage', $types, true), $path.' names the person');
        }
    }

    public function test_blog_posts_carry_both_dates_an_author_and_the_social_image(): void
    {
        foreach (Posts::all() as $post) {
            $blocks = $this->blocks((string) $this->get(self::HOST.'/blog/'.$post['slug'])->getContent());
            $posting = array_values(array_filter($blocks, fn ($b) => $b['@type'] === 'BlogPosting'))[0];
            $this->assertSame($post['date'], $posting['datePublished']);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $posting['dateModified']);
            $this->assertGreaterThanOrEqual($posting['datePublished'], $posting['dateModified']);
            $this->assertSame('Stanislav Plotnikov', $posting['author']['name']);
            $this->assertStringEndsWith('/vendor/splotnikov/og-image.png', $posting['image']);
        }
    }

    public function test_every_breadcrumb_item_resolves(): void
    {
        $seen = [];
        foreach ($this->pages() as $path) {
            foreach ($this->blocks((string) $this->get(self::HOST.$path)->getContent()) as $block) {
                if ($block['@type'] !== 'BreadcrumbList') {
                    continue;
                }
                foreach ($block['itemListElement'] as $i => $item) {
                    $this->assertSame($i + 1, $item['position']);
                    $seen[$item['item']] = true;
                }
            }
        }
        $this->assertNotEmpty($seen);
        foreach (array_keys($seen) as $url) {
            $this->assertStringStartsWith('https://splotnikov.dev', $url);
            $this->get(str_replace('https://splotnikov.dev', self::HOST, $url))->assertOk();
        }
    }

    public function test_every_page_has_the_full_set_of_social_tags_and_the_twins_say_it_in_dutch(): void
    {
        foreach ($this->pages() as $path) {
            $html = (string) $this->get(self::HOST.$path)->getContent();
            foreach (['og:image:width" content="1200"', 'og:image:height" content="630"', 'og:image:alt" content="', 'twitter:card" content="summary_large_image"', 'og:image" content="https://'] as $needle) {
                $this->assertStringContainsString($needle, $html, $path.' lacks '.$needle);
            }
            if (str_starts_with($path, '/nl')) {
                $this->assertStringContainsString('senior developer en solution architect', $html, $path.' has an English image description');
            }
        }
        $size = getimagesize(__DIR__.'/../../public/og-image.png');
        $this->assertSame([1200, 630], [$size[0], $size[1]], 'the declared width and height are the real ones');
    }
}
