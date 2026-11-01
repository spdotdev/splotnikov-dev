<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Facts that scuttle.dev states too, pinned here so the two sites cannot drift apart (read from the live scuttle.dev on 2026-11-01). */
class FactsAgreeTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    public function test_the_business_facts_match_scuttle_dev(): void
    {
        $cv = (string) $this->get(self::HOST.'/cv')->assertOk()->getContent();
        foreach (['+31 (0) 6 18 23 31 75', 'info@scuttle.dev', 'KvK 96040947', 'BTW NL005184584B85', 'github.com/spdotdev', 'linkedin.com/in/stanislavplotnikov'] as $fact) {
            $this->assertStringContainsString($fact, $cv, $fact);
        }
        $this->assertStringNotContainsString('182 33 175', $cv, 'the work number is written in one grouping on both sites');
    }

    public function test_the_languages_and_the_city_match_scuttle_dev(): void
    {
        $cv = (string) $this->get(self::HOST.'/cv')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/Dutch, English(,| and) Russian/', strip_tags($cv));
        $this->assertStringContainsString('Eindhoven', $cv);
        $this->assertStringContainsString('PHP since 2008', $cv);
    }
}
