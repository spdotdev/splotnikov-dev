<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Round 12: the repository is public, so its own files must agree with each other. */
class PackageHygieneTest extends TestCase
{
    private function root(string $file = ''): string
    {
        return dirname(__DIR__, 2).'/'.$file;
    }

    public function test_every_env_variable_in_the_config_is_in_the_readme_and_in_env_example_with_the_same_default(): void
    {
        $config = (string) file_get_contents($this->root('config/splotnikov-dev.php'));
        preg_match_all("/env\\('(SPLOTNIKOV_[A-Z_]+)'(?:,\\s*(.*?))?\\)/", $config, $m, PREG_SET_ORDER);
        $this->assertGreaterThanOrEqual(8, count($m));
        $example = (string) file_get_contents($this->root('.env.example'));
        $readme = (string) file_get_contents($this->root('README.md'));
        foreach ($m as $hit) {
            $name = $hit[1];
            $default = trim($hit[2] ?? '', " '\"");
            $this->assertMatchesRegularExpression('/^'.$name.'=(.*)$/m', $example, $name.' is missing in .env.example');
            preg_match('/^'.$name.'=(.*)$/m', $example, $e);
            $this->assertSame($default, trim($e[1]), $name.' has another default in .env.example than in the config');
            $this->assertMatchesRegularExpression('/\| `[^|]*'.$name.'[^|]*` \| ([^|]*) \|/', $readme, $name.' is missing in the README table');
            preg_match('/\| `[^|]*'.$name.'[^|]*` \| ([^|]*) \|/', $readme, $r);
            $shown = trim($r[1], ' `');
            $this->assertSame($default === '' ? 'empty' : $default, $shown, $name.' has another default in the README than in the config');
        }
    }

    public function test_the_license_file_matches_composer_json_and_composer_json_has_what_a_public_package_needs(): void
    {
        $composer = json_decode((string) file_get_contents($this->root('composer.json')), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('proprietary', $composer['license']);
        $this->assertStringContainsStringIgnoringCase('proprietary', (string) file_get_contents($this->root('LICENSE')));
        $this->assertStringContainsString('All rights reserved', (string) file_get_contents($this->root('LICENSE')));
        $this->assertNotEmpty($composer['description']);
        $this->assertNotEmpty($composer['keywords']);
        $this->assertStringStartsWith('https://github.com/spdotdev/splotnikov-dev', $composer['support']['issues']);
        $this->assertStringStartsWith('https://github.com/spdotdev/splotnikov-dev', $composer['support']['source']);
    }

    public function test_the_dist_archive_leaves_out_tests_docs_and_design_sources_but_keeps_what_runs(): void
    {
        $attributes = (string) file_get_contents($this->root('.gitattributes'));
        foreach (['/tests', '/docs', '/design', '/scripts', '/.github', '/CLAUDE.md'] as $path) {
            $this->assertMatchesRegularExpression('#^'.preg_quote($path, '#').' export-ignore$#m', $attributes, $path.' is shipped in the dist archive');
        }
        foreach (['src', 'routes', 'resources', 'config', 'public', 'database', 'README', 'LICENSE', 'composer'] as $needed) {
            $this->assertStringNotContainsString('/'.$needed.' export-ignore', $attributes);
        }
        // Nothing at run time reads a path that the archive leaves out.
        foreach (['src', 'routes', 'resources', 'config'] as $dir) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root($dir), \FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                $code = (string) file_get_contents($file->getPathname());
                $this->assertDoesNotMatchRegularExpression('#(?:__DIR__|base_path|dirname)[^\n]*[\'"/](?:docs|design|scripts|tests)/#', $code, $file->getPathname());
            }
        }
    }

    public function test_the_changelog_has_a_line_for_each_recent_tag_and_points_to_the_history(): void
    {
        $log = (string) file_get_contents($this->root('CHANGELOG.md'));
        foreach (['v0.3.34', 'v0.3.35', 'v0.3.36', 'v0.3.37', 'v0.3.38', 'v0.3.39', 'v0.3.40'] as $tag) {
            $this->assertStringContainsString('- '.$tag.':', $log);
        }
        $this->assertStringContainsString('git history', $log);
    }
}
