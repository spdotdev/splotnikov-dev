<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Spdotdev\SplotnikovDev\Tests\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Production PHP runs with short_open_tag on (php:8.4-fpm image). A literal "<?xml" in a view is then read as a PHP
 * tag and the page answers 500, while a laptop with the setting off stays green. This compiles every Blade view
 * and lints the compiled PHP with short_open_tag=1, so the failure shows on any machine.
 */
class ProductionPhpTest extends TestCase
{
    /** @return array{0: int, 1: string} */
    private function lintWithShortTags(string $php): array
    {
        $file = tempnam(sys_get_temp_dir(), 'blade-lint-');
        file_put_contents($file, $php);
        $out = [];
        exec(escapeshellarg(PHP_BINARY).' -d short_open_tag=1 -d display_errors=1 -l '.escapeshellarg($file).' 2>&1', $out, $code);
        unlink($file);

        return [$code, implode("\n", $out)];
    }

    public function test_every_view_compiles_to_php_that_lints_with_short_open_tag_on(): void
    {
        $checked = 0;
        foreach ((new Finder)->files()->in(__DIR__.'/../../resources/views')->name('*.blade.php') as $file) {
            [$code, $out] = $this->lintWithShortTags(Blade::compileString((string) file_get_contents($file->getPathname())));
            $this->assertSame(0, $code, $file->getRelativePathname().' does not lint with short_open_tag=1: '.$out);
            $checked++;
        }
        $this->assertGreaterThanOrEqual(10, $checked);
    }

    public function test_the_lint_catches_a_literal_xml_declaration(): void
    {
        [$code] = $this->lintWithShortTags(Blade::compileString('<?xml version="1.0"?><feed></feed>'));
        $this->assertNotSame(0, $code, 'The lint must fail on a literal xml declaration, otherwise it proves nothing.');
    }
}
