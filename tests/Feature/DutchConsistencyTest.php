<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Spdotdev\SplotnikovDev\Dutch;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/** Round 12: one Dutch term per concept on every page: je (never u), website, e-mail, gesprek, EUR with a dot. */
class DutchConsistencyTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    /** @return array<string, string> key (with the page) to Dutch string */
    private function strings(): array
    {
        $pages = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog'];
        foreach (Posts::all() as $post) {
            $pages[] = '/blog/'.$post['slug'];
        }
        $all = [];
        foreach ($pages as $path) {
            foreach (Dutch::strings((string) $this->get(self::HOST.$path)->assertOk()->getContent()) as $k => $v) {
                $all[$path.' '.$k] = strip_tags($v);
            }
        }
        // The server-side messages of the contact form.
        $c = new \ReflectionMethod(ContactController::class, 'messages');
        foreach ($c->invoke(null, true) as $k => $v) {
            $all['messages '.$k] = $v;
        }

        return $all;
    }

    public function test_the_dutch_text_uses_one_term_per_concept(): void
    {
        $all = $this->strings();
        $this->assertGreaterThan(250, count($all));
        $rules = [
            'je, not u or uw' => '/(?<!\d )(?<!\d)\b(uw|U|u)\b/',
            'website, not site' => '/(?<!off-)\bsite\b(?!-)/i',
            'e-mail with a hyphen' => '/\b[Ee]mail(?:adres|en)?\b/',
            'gesprek, not call' => '/\b[Cc]alls?\b/',
            'EUR with the Dutch thousands dot' => '/EUR\s?\d{4,}\b|EUR\s?\d{1,3},\d{3}\b/',
            'no calque: eigenaarschap, mens in de lus, opgeschreven op, van welke soort ook, geklokt, aan gaat' => '/eigenaarschap|in de lus|opgeschreven op|van welke soort ook|geklokt|aan gaat|klagen bij/',
            'opensource is one word' => '/\b[Oo]pen source (projecten|software|code)\b/',
            'je, not the stressed jouw, for the unstressed possessive' => '/\bjouw (systeem|bedrijf|team|data|stack)\b/',
            'Dutch month names' => '/\b\d{1,2} (January|February|March|April|June|July|August|September|October|November|December)\b/',
        ];
        foreach ($rules as $what => $pattern) {
            foreach ($all as $key => $text) {
                $this->assertDoesNotMatchRegularExpression($pattern, $text, 'Dutch string '.$key.' breaks the rule "'.$what.'"');
            }
        }
    }

    public function test_the_service_and_product_names_stay_english_in_dutch_text(): void
    {
        $text = implode(' ', $this->strings());
        $this->assertStringNotContainsString('Gezondheidscheck', $text);
        $this->assertStringNotContainsString('Veiligheidscheck', $text);
        $this->assertStringContainsString('Scuttle Development', $text);
    }
}
