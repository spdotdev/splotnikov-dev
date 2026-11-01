<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * The terminal scenes show figures (312 tests, assertions, durations) that are a fixed re-enactment of a sample
 * project, not a live count. They must agree with each other, and the code must say that they are a re-enactment.
 */
class FixedFiguresTest extends TestCase
{
    public function test_the_test_count_is_one_figure_everywhere_and_marked_as_a_re_enactment(): void
    {
        $js = (string) file_get_contents(__DIR__.'/../../public/site.js');
        $home = (string) file_get_contents(__DIR__.'/../../resources/views/portfolio.blade.php');

        preg_match_all('/\b(\d{2,4}) (?:tests|passed)\b/', $js.$home, $m);
        $this->assertNotEmpty($m[1]);
        $this->assertSame(['312'], array_values(array_unique(array_filter($m[1], fn ($n) => (int) $n > 100))), 'The scenes show different test counts');
        $this->assertStringContainsString('fixed re-enactment', $js);
        $this->assertStringContainsString('fixed re-enactment', $home);

        $page = (string) $this->get('http://splotnikov.dev/')->assertOk()->getContent();
        $this->assertStringContainsString('312 tests green', $page);
    }
}
