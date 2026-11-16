<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * Cheap CSS guard (issue #26): the weekly axe and focus-ring check found two CSS details
 * (opacity on calendar text, outline:none without a focus ring). This fails them at test time.
 * Scans public/site.css and every <style> block in resources/views/**\/*.blade.php.
 */
class CssGuardTest extends TestCase
{
    /** Selectors allowed to remove the outline without a :focus-visible rule. selector => reason. */
    private const OUTLINE_ALLOW = [
    ];

    /** Selectors allowed to set opacity although they are calendar text. selector => reason. */
    private const OPACITY_ALLOW = [
    ];

    /** @return array<string, string> label => css text */
    private function sources(): array
    {
        $root = dirname(__DIR__, 2);
        $out = ['public/site.css' => file_get_contents($root.'/public/site.css')];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root.'/resources/views'));
        foreach ($it as $f) {
            if (! str_ends_with($f->getFilename(), '.blade.php')) {
                continue;
            }
            preg_match_all('~<style[^>]*>(.*?)</style>~s', file_get_contents($f->getPathname()), $m);
            if ($m[1]) {
                $out[substr($f->getPathname(), strlen($root) + 1)] = implode("\n", $m[1]);
            }
        }

        return $out;
    }

    /** @return array<int, array{0: string, 1: string}> [selector, body] per selector of every innermost rule */
    private function rules(string $css): array
    {
        $css = preg_replace('~/\*.*?\*/~s', '', $css);
        preg_match_all('~([^{}]+)\{([^{}]*)\}~', $css, $m, PREG_SET_ORDER);
        $rules = [];
        foreach ($m as $r) {
            foreach (explode(',', $r[1]) as $sel) {
                $rules[] = [trim(preg_replace('~\s+~', ' ', $sel)), $r[2]];
            }
        }

        return $rules;
    }

    private function base(string $selector): string
    {
        return trim(preg_replace('~:focus(-visible|-within)?~', '', $selector));
    }

    public function test_outline_none_has_a_focus_visible_rule(): void
    {
        $bad = [];
        foreach ($this->sources() as $label => $css) {
            $rules = $this->rules($css);
            $ringed = [];
            foreach ($rules as [$sel, $body]) {
                if (str_contains($sel, ':focus-visible')) {
                    $ringed[$this->base($sel)] = true;
                }
            }
            foreach ($rules as [$sel, $body]) {
                if (preg_match('~outline\s*:\s*(none|0)\s*(;|$|!)~i', $body)
                    && ! str_contains($sel, ':focus-visible')
                    && ! isset($ringed[$this->base($sel)])
                    && ! isset(self::OUTLINE_ALLOW[$sel])) {
                    $bad[] = "$label: $sel";
                }
            }
        }
        $this->assertSame([], $bad, "outline:none/0 without a :focus-visible rule for the same selector:\n".implode("\n", $bad));
    }

    public function test_calendar_text_has_no_opacity(): void
    {
        $bad = [];
        foreach ($this->sources() as $label => $css) {
            foreach ($this->rules($css) as [$sel, $body]) {
                if (str_contains($sel, '.cal-g') && preg_match('~(^|[;\s])opacity\s*:~i', $body) && ! isset(self::OPACITY_ALLOW[$sel])) {
                    $bad[] = "$label: $sel";
                }
            }
        }
        $this->assertSame([], $bad, "opacity on .cal-g selectors (fails the axe contrast check):\n".implode("\n", $bad));
    }
}
