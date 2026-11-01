<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** What a screen reader gets: unique link names, honest alt text, announced form messages, labelled landmarks, Dutch names on Dutch pages. */
class SemanticsTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    private const PAGES = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog', '/blog?p=2026', '/blog/strangler-fig-what-the-bridge-taught-me', '/nl', '/nl/cv', '/nl/contact', '/nl/projects', '/nl/toolkit', '/nl/privacy'];

    private function xpath(string $html): DOMXPath
    {
        $doc = new DOMDocument;
        @$doc->loadHTML('<?xml encoding="UTF-8">'.$html);

        return new DOMXPath($doc);
    }

    private function page(string $path): DOMXPath
    {
        return $this->xpath((string) $this->get(self::HOST.$path)->assertOk()->getContent());
    }

    public function test_one_main_and_every_navigation_has_a_label(): void
    {
        foreach (self::PAGES as $path) {
            $x = $this->page($path);
            $this->assertSame(1, $x->query('//main')->length, "$path: exactly one main");
            foreach ($x->query('//nav') as $nav) {
                $this->assertNotSame('', trim((string) $nav->getAttribute('aria-label')), "$path: a nav without a label");
            }
            $labels = [];
            foreach ($x->query('//nav[@aria-label]') as $nav) {
                $labels[] = $nav->getAttribute('aria-label');
            }
            $this->assertSame($labels, array_values(array_unique($labels)), "$path: two navigations share a label");
        }
    }

    public function test_a_link_name_is_unique_within_a_page_unless_it_goes_to_the_same_place(): void
    {
        foreach (self::PAGES as $path) {
            $names = [];
            foreach ($this->page($path)->query('//a[@href]') as $a) {
                /** @var DOMElement $a */
                $name = mb_strtolower(trim((string) preg_replace('/\s+/', ' ', $a->getAttribute('aria-label') !== '' ? $a->getAttribute('aria-label') : $a->textContent)));
                if ($name === '') {
                    continue;
                }
                $href = (string) preg_replace(['/#.*$/', '#^http://splotnikov\.dev#'], '', $a->getAttribute('href'));
                $names[$name][$href] = true;
            }
            foreach ($names as $name => $targets) {
                $this->assertCount(1, $targets, "$path: the link name \"$name\" goes to ".implode(' and ', array_keys($targets)));
            }
        }
    }

    public function test_every_image_alt_says_what_the_picture_shows(): void
    {
        foreach (self::PAGES as $path) {
            foreach ($this->page($path)->query('//img') as $img) {
                /** @var DOMElement $img */
                $alt = trim($img->getAttribute('alt'));
                if ($img->getAttribute('role') === 'presentation' || $img->getAttribute('aria-hidden') === 'true') {
                    continue;
                }
                $this->assertGreaterThanOrEqual(5, mb_strlen($alt), "$path: alt of ".$img->getAttribute('src').' is empty or too short');
                $this->assertDoesNotMatchRegularExpression('/\.(png|jpe?g|svg|webp)$|^(image|photo|picture|foto|afbeelding)$/i', $alt, "$path: alt is a file name or a generic word");
            }
        }
    }

    public function test_the_form_messages_are_announced(): void
    {
        $sent = $this->xpath((string) $this->withSession(['contact_sent' => 'mailed'])->get(self::HOST.'/contact')->assertOk()->getContent());
        $this->assertSame(1, $sent->query('//*[contains(@class,"sent")][@role="status"]')->length);

        $bag = new ViewErrorBag;
        $bag->put('default', new MessageBag(['message' => ['Please write a message.']]));
        $err = $this->xpath((string) $this->withSession(['errors' => $bag])->get(self::HOST.'/contact')->assertOk()->getContent());
        $this->assertSame(1, $err->query('//*[contains(@class,"err")][@role="alert"]')->length);
    }

    public function test_the_dutch_pages_carry_dutch_names_and_the_lang_attribute(): void
    {
        $english = ['Main navigation', 'Language', 'Theme', 'System', 'Light', 'Dark', 'Close', 'Tools', 'Projects', 'Companies', 'Show the photo larger', 'Photo of Stanislav Plotnikov', 'With or without company details', 'scuttle.dev, my company website', 'Interactive examples: terminal sessions, code and an agent run from my daily work'];
        foreach (['/nl', '/nl/cv', '/nl/contact', '/nl/projects', '/nl/toolkit', '/nl/privacy'] as $path) {
            $x = $this->page($path);
            $this->assertSame('nl', $x->query('//html')->item(0)?->getAttribute('lang'), "$path: html lang");
            foreach ($x->query('//*[@aria-label or @title]') as $el) {
                /** @var DOMElement $el */
                foreach (['aria-label', 'title'] as $attr) {
                    $this->assertNotContains($el->getAttribute($attr), $english, "$path: $attr is still English");
                }
            }
        }
        $en = $this->page('/');
        $this->assertSame('en', $en->query('//html')->item(0)?->getAttribute('lang'));
        $this->assertSame('Main navigation', $en->query('//nav')->item(0)?->getAttribute('aria-label'));
    }

    public function test_blog_archive_links_name_their_period(): void
    {
        $x = $this->page('/blog');
        foreach ($x->query('//a[contains(@class,"sall")]') as $a) {
            /** @var DOMElement $a */
            $this->assertMatchesRegularExpression('/^All posts of \d{4}(-\d{2})?$/', $a->getAttribute('aria-label'));
        }
        $this->assertGreaterThan(0, $x->query('//a[contains(@class,"sall")]')->length);
    }
}
