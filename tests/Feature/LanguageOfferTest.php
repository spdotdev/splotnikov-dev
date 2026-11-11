<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Http\Request;
use Spdotdev\SplotnikovDev\LanguageOffer;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** The Dutch offer: shown once to Dutch visitors on paired English pages, never after a choice. */
class LanguageOfferTest extends TestCase
{
    private function req(string $acceptLanguage = '', array $cookies = []): Request
    {
        $server = $acceptLanguage === '' ? [] : ['HTTP_ACCEPT_LANGUAGE' => $acceptLanguage];

        return Request::create('/', 'GET', [], $cookies, [], $server);
    }

    public function test_dutch_browser_on_a_paired_english_page_gets_the_offer(): void
    {
        $this->assertTrue(LanguageOffer::shouldOffer($this->req('nl-NL,nl;q=0.9,en;q=0.8'), true, false));
    }

    public function test_english_browser_gets_no_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('en-GB,en;q=0.9'), true, false));
    }

    public function test_no_language_header_gives_no_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req(), true, false));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req(' '), true, false));
    }

    public function test_a_dutch_ip_gets_the_offer_and_a_foreign_one_does_not(): void
    {
        $this->assertTrue(LanguageOffer::shouldOffer($this->req('en-GB'), true, false, 'NL'));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('en-GB'), true, false, 'US'));
    }

    public function test_no_offer_on_unpaired_pages_or_the_dutch_version(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL'), false, false));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL'), true, true));
    }

    public function test_a_stored_choice_stops_the_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL', [LanguageOffer::COOKIE => 'en']), true, false));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL', [LanguageOffer::COOKIE => 'nl']), true, false));
    }

    public function test_no_country_database_means_no_country(): void
    {
        config(['splotnikov-dev.geoip_db' => '']);
        $this->assertNull(LanguageOffer::countryOf($this->req()));
    }
}
