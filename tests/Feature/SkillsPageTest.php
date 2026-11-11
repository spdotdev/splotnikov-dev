<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Skills;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** The skills page lists real, described skills; a public link is only for the one public repository. */
class SkillsPageTest extends TestCase
{
    public function test_every_skill_has_a_name_a_description_and_a_count(): void
    {
        $all = Skills::all();
        $this->assertGreaterThanOrEqual(10, count($all));
        foreach ($all as $sk) {
            $this->assertNotSame('', $sk['name']);
            $this->assertNotSame('', $sk['what']);
            $this->assertGreaterThanOrEqual(0, $sk['uses']);
        }
    }

    public function test_only_public_links_point_to_github_and_private_ones_have_none(): void
    {
        foreach (Skills::all() as $sk) {
            if ($sk['public_url'] !== null) {
                $this->assertStringStartsWith('https://github.com/spdotdev/', $sk['public_url']);
            }
        }
        $linked = array_filter(Skills::all(), fn (array $s): bool => $s['public_url'] !== null);
        $this->assertCount(1, $linked);
    }

    public function test_the_skills_page_renders(): void
    {
        $this->get('https://splotnikov.dev/skills')->assertOk()->assertSee('Skills I built');
    }
}
