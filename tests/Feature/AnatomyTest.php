<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * One anatomy per page type. Blog post: date, title, lead, body, more notes, closing call. Project card: kind, name,
 * text, chips, link. Pinned in both languages for every post, so a new article cannot drift from the shape.
 */
class AnatomyTest extends TestCase
{
    /** @param list<string> $needles */
    private function assertOrder(string $html, array $needles, string $what): void
    {
        $last = -1;
        foreach ($needles as $needle) {
            $pos = strpos($html, $needle, $last + 1);
            $this->assertNotFalse($pos, "$what: '$needle' is missing or out of order");
            $last = $pos;
        }
    }

    public function test_every_blog_post_has_the_same_section_order(): void
    {
        foreach (array_keys(Posts::all()) as $slug) {
            $html = (string) $this->get('http://splotnikov.dev/blog/'.$slug)->assertOk()->getContent();
            $this->assertOrder($html, ['<time ', '<h1', 'class="lead"', 'data-i="blp_more"', 'class="callout"', 'class="btn"'], "post $slug");
            $this->assertSame(1, substr_count($html, 'data-i="blp_more"'), "post $slug");
            $this->assertSame(1, substr_count($html, 'class="callout"'), "post $slug");
        }
    }

    public function test_project_cards_have_the_same_order(): void
    {
        $html = (string) $this->get('http://splotnikov.dev/projects')->assertOk()->getContent();
        preg_match_all('#<a class="c rv cg".*?</a>#s', $html, $found);
        $cards = $found[0];
        $this->assertNotEmpty($cards);
        foreach ($cards as $card) {
            $this->assertOrder($card, ['class="lbl"', '<h2>', '<p ', 'class="tags"', 'class="more"'], 'project card');
        }
    }

    public function test_reading_time_is_computed_at_220_words_a_minute(): void
    {
        $post = ['body' => []];
        $this->assertSame(1, Posts::readingMinutes($post));
        $post['body'] = [implode(' ', array_fill(0, 220, 'word'))];
        $this->assertSame(1, Posts::readingMinutes($post));
        $post['body'] = [implode(' ', array_fill(0, 221, 'word')), '- <b>bold</b> item'];
        $this->assertSame(2, Posts::readingMinutes($post));
        $post['body'] = [implode(' ', array_fill(0, 880, 'word'))];
        $this->assertSame(4, Posts::readingMinutes($post));
    }

    public function test_every_post_shows_its_reading_time_in_both_languages(): void
    {
        foreach (Posts::all() as $slug => $post) {
            $mins = Posts::readingMinutes($post);
            $html = (string) $this->get('http://splotnikov.dev/blog/'.$slug)->assertOk()->getContent();
            $this->assertStringContainsString('<span data-i="blp_read">'.$mins.' min read</span>', $html, $slug);
            $this->assertStringContainsString('"blp_read":"'.$mins.' min lezen"', $html, $slug);
        }
    }
}
