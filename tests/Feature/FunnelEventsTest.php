<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * Funnel events: with an sd-analytics key the contact buttons, the CV PDF button and the language switch carry the
 * data attribute the analytics script reads on a click (the page path is sent by the script itself, nothing else).
 * Without the key the markup carries nothing.
 */
class FunnelEventsTest extends TestCase
{
    private const PAGES = ['/', '/nl', '/cv', '/nl/cv', '/projects', '/toolkit', '/blog', '/blog/strangler-fig-what-the-bridge-taught-me', '/contact'];

    public function test_nothing_is_marked_without_a_key(): void
    {
        foreach (self::PAGES as $path) {
            $this->assertStringNotContainsString('data-sda-event', (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent(), $path);
        }
    }

    public function test_buttons_carry_the_event_attribute_with_a_key(): void
    {
        config(['splotnikov-dev.analytics_key' => 'abcDEF123456']);
        $allowed = ['Contact button click', 'CV PDF download', 'Language switch'];
        foreach (self::PAGES as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            preg_match_all('/data-sda-event="([^"]*)"/', $html, $m);
            $this->assertNotEmpty($m[1], $path);
            foreach ($m[1] as $name) {
                $this->assertContains($name, $allowed, $path);
                $this->assertLessThan(5, str_word_count($name), 'event names stay under five words');
            }
            $this->assertStringContainsString('data-sda-event="Contact button click"', $html, $path);
        }
        $cv = (string) $this->get('http://splotnikov.dev/cv')->getContent();
        $this->assertMatchesRegularExpression('/id="btn-pdf"[^>]*data-sda-event="CV PDF download"/', $cv);
        $this->assertMatchesRegularExpression('/id="nl"[^>]*data-sda-event="Language switch"/', $cv);
        $this->assertMatchesRegularExpression('/<a class="btn cta" href="[^"]*contact"[^>]*data-sda-event="Contact button click"/', $cv);
    }
}
