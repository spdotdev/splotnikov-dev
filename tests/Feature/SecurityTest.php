<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Spdotdev\SplotnikovDev\Mail\ContactMessage;
use Spdotdev\SplotnikovDev\Models\ContactLead;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Tests\TestCase;

/**
 * Round 12: the package code against hostile input. Each test pins a place where data from the request or the CMS
 * lands in a redirect, a header, a mail, a log line, an XML file or a script block.
 */
class SecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // The throttle is tested elsewhere; these tests send many forms in a row.
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    private const HOST = 'http://splotnikov.dev';

    private function token(int $age = 10): string
    {
        $t = (string) (time() - $age);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /** @return array<string, string> */
    private function valid(array $over = []): array
    {
        return $over + ['name' => 'Test Person', 'email' => 'test@example.com', 'about' => 'Security', 'message' => 'A message that is long enough.', 't' => $this->token(), 'website' => ''];
    }

    private function cms(): string
    {
        if (! class_exists('Spdotdev\\SdCms\\Models\\CmsArticle')) {
            eval('namespace Spdotdev\\SdCms\\Models; class CmsArticle { public static array $rows = []; public static function published(string $site) { return new class { public function get() { return new \\Illuminate\\Support\\Collection(CmsArticle::$rows); } }; } }');
        }

        return 'Spdotdev\\SdCms\\Models\\CmsArticle';
    }

    public function test_a_hostile_referer_never_decides_where_the_form_redirects(): void
    {
        Mail::fake();
        foreach (['https://evil.example/phish', '//evil.example/x', 'javascript:alert(1)', 'http://splotnikov.dev.evil.example/contact', 'http://splotnikov.dev/not-the-form?x=1'] as $referer) {
            $bad = $this->withHeaders(['referer' => $referer])->post(self::HOST.'/contact', $this->valid(['name' => '']));
            $this->assertStringStartsWith(self::HOST.'/contact', (string) $bad->headers->get('Location'), 'error redirect for '.$referer);
            $this->assertStringNotContainsString('evil', (string) $bad->headers->get('Location'));

            $ok = $this->withHeaders(['referer' => $referer])->post(self::HOST.'/contact', $this->valid());
            $this->assertStringStartsWith(self::HOST.'/contact', (string) $ok->headers->get('Location'), 'success redirect for '.$referer);
            $this->assertStringNotContainsString('evil', (string) $ok->headers->get('Location'));
        }
        // A real referer from the Dutch form still returns to the Dutch form, with the topic kept.
        $nl = $this->withHeaders(['referer' => self::HOST.'/nl/contact?about=Security'])->post(self::HOST.'/contact', $this->valid(['name' => '']));
        $this->assertSame(self::HOST.'/nl/contact?about=Security', $nl->headers->get('Location'));
    }

    public function test_a_hostile_referer_is_not_stored_as_the_page(): void
    {
        Mail::fake();
        $this->withHeaders(['referer' => 'https://evil.example/'.str_repeat('a', 500)])->post(self::HOST.'/contact', $this->valid())->assertSessionHas('contact_sent');
        $lead = ContactLead::query()->firstOrFail();
        $this->assertLessThanOrEqual(200, mb_strlen((string) $lead->page));
        $this->assertSame('en', $lead->language);
    }

    public function test_the_signed_time_token_is_checked_in_both_directions(): void
    {
        Mail::fake();
        $future = (string) (time() + 3600);
        $cases = [
            'too fresh' => $this->token(0),
            'older than six hours' => $this->token(6 * 3600 + 5),
            'from the future' => $future.'.'.hash_hmac('sha256', $future, (string) config('app.key')),
            'forged signature' => time() - 10 .'.'.str_repeat('0', 64),
            'no signature' => (string) (time() - 10),
            'signed with another key' => (time() - 10).'.'.hash_hmac('sha256', (string) (time() - 10), 'other'),
        ];
        foreach ($cases as $why => $t) {
            $this->post(self::HOST.'/contact', $this->valid(['t' => $t]))->assertSessionHasErrors('message');
            $this->assertSame(0, ContactLead::query()->count(), $why.' stored a lead');
        }
        $this->post(self::HOST.'/contact', $this->valid(['t' => $this->token(5 * 3600)]))->assertSessionHas('contact_sent');
        $this->assertSame(1, ContactLead::query()->count());
    }

    public function test_posted_extra_fields_cannot_set_stored_columns(): void
    {
        Mail::fake();
        $this->post(self::HOST.'/contact', $this->valid(['mail_status' => 'sent', 'is_test' => '1', 'id' => '99', 'ip_hash' => 'x', 'language' => 'zz', 'created_at' => '2001-01-01']))->assertSessionHas('contact_sent');
        $lead = ContactLead::query()->firstOrFail();
        $this->assertNotSame(99, $lead->id);
        $this->assertFalse($lead->is_test);
        $this->assertNotSame('x', $lead->ip_hash);
        $this->assertSame('en', $lead->language);
        $this->assertNotSame('2001-01-01', $lead->created_at?->format('Y-m-d'));
    }

    /** @return array<string, array{0: mixed}> */
    public static function turnstileFailures(): array
    {
        return [
            'no token' => [null],
            'Cloudflare says no' => [['success' => false]],
            'Cloudflare answers 500' => [500],
            'Cloudflare times out' => ['throw'],
        ];
    }

    #[DataProvider('turnstileFailures')]
    public function test_turnstile_failure_modes_all_refuse_and_store_nothing(mixed $mode): void
    {
        Mail::fake();
        config(['splotnikov-dev.turnstile_secret' => 'secret']);
        Log::spy();
        $fake = match (true) {
            $mode === 500 => Http::response('boom', 500),
            $mode === 'throw' => fn () => throw new \RuntimeException('timeout with secret in the text'),
            is_array($mode) => Http::response($mode, 200),
            default => Http::response(['success' => true], 200),
        };
        Http::fake(['challenges.cloudflare.com/*' => $fake]);

        $extra = $mode === null ? [] : ['cf-turnstile-response' => 'x'];
        $this->post(self::HOST.'/contact', $this->valid($extra))->assertSessionHasErrors('message');
        $this->assertSame(0, ContactLead::query()->count());
        if ($mode === 'throw') {
            Log::shouldHaveReceived('warning')->withArgs(fn ($m, $ctx) => ! str_contains((string) json_encode($ctx), 'secret'))->once();
        }
    }

    public function test_turnstile_success_is_the_only_way_through(): void
    {
        Mail::fake();
        config(['splotnikov-dev.turnstile_secret' => 'secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true], 200)]);
        $this->post(self::HOST.'/contact', $this->valid(['cf-turnstile-response' => 'x']))->assertSessionHas('contact_sent');
        $this->assertSame(1, ContactLead::query()->count());
    }

    public function test_a_log_line_about_a_failed_store_holds_no_visitor_data(): void
    {
        Mail::fake();
        Schema::drop('splotnikov_contact_leads');
        Log::spy();
        $this->post(self::HOST.'/contact', $this->valid(['name' => 'Private Name', 'email' => 'private@example.com', 'message' => 'Private message text here.']))->assertSessionHasErrors('message');
        Log::shouldHaveReceived('error')->withArgs(function ($m, $ctx) {
            $all = $m.json_encode($ctx);

            return ! str_contains($all, 'Private') && ! str_contains($all, 'private@');
        })->once();
    }

    public function test_the_mail_cannot_carry_a_header_or_markup_from_the_visitor(): void
    {
        // The form rejects control characters outright ...
        Mail::fake();
        $this->post(self::HOST.'/contact', $this->valid(['name' => "Eve\r\nBcc: victim@example.com"]))->assertSessionHasErrors('name');
        $this->post(self::HOST.'/contact', $this->valid(['about' => "Security\r\nBcc: victim@example.com"]))->assertSessionHasErrors('about');
        $this->post(self::HOST.'/contact', $this->valid(['email' => "a@example.com\r\nBcc: victim@example.com"]))->assertSessionHasErrors('email');
        Mail::assertNothingSent();

        // ... and the mail itself strips them again, in case a caller skips the form.
        $m = (new ContactMessage("Eve\r\nBcc: victim@example.com <script>alert(1)</script>", 'eve@example.com', "Se\r\ncurity", "Body\r\nwith <b>markup</b> and <script>alert(1)</script>", 'hash', "/contact\r\nX: y"))->build();
        $this->assertStringNotContainsString("\r", (string) $m->subject);
        $this->assertStringNotContainsString("\n", (string) $m->subject);
        $this->assertTrue($m->hasReplyTo('eve@example.com'));
        $this->assertStringNotContainsString("\n", $m->replyTo[0]['name']);
        $html = (string) $m->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<b>markup</b>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function test_the_mail_names_the_page_the_visitor_wrote_from_and_replies_to_the_visitor(): void
    {
        Mail::fake();
        $this->withHeaders(['referer' => self::HOST.'/nl/contact'])->post(self::HOST.'/contact', $this->valid(['about' => 'Legacy modernization']))->assertSessionHas('contact_sent', 'mailed');
        Mail::assertSent(ContactMessage::class, function (ContactMessage $m) {
            $m->build();
            $this->assertTrue($m->hasReplyTo('test@example.com'));
            $this->assertSame('splotnikov.dev contact: Legacy modernization · Test Person', $m->subject);
            $this->assertSame('/nl/contact', $m->page);
            $this->assertStringContainsString('Page: /nl/contact', (string) $m->render());

            return true;
        });
    }

    public function test_a_cms_title_cannot_break_out_of_a_script_an_attribute_or_the_xml_files(): void
    {
        Schema::create('cms_articles', function ($t) {
            $t->id();
        });
        $cms = $this->cms();
        $evil = '"><img src=x onerror=alert(1)> </script><script>alert(2)</script> data-i="blp_h1" ]]> & \' ';
        $cms::$rows = [(object) ['slug' => 'evil', 'title' => $evil, 'summary' => $evil, 'body' => $evil, 'tags' => [$evil], 'published_at' => '2026-10-31 09:00:00', 'title_nl' => $evil, 'summary_nl' => $evil]];
        try {
            foreach (['/blog/evil', '/blog', '/feed.xml', '/sitemap.xml'] as $path) {
                $res = $this->get(self::HOST.$path)->assertOk();
                $body = (string) $res->getContent();
                $this->assertStringNotContainsString('<script>alert(2)', $body, $path);
                $this->assertStringNotContainsString('<img src=x', $body, $path);
                $this->assertStringNotContainsString('onerror=alert(1)>', $body, $path);
                // The content type is a fixed value: nothing from a title reaches a header.
                $this->assertDoesNotMatchRegularExpression('/evil|alert|\r|\n/', (string) $res->headers->get('Content-Type'));
            }
            $post = (string) $this->get(self::HOST.'/blog/evil')->getContent();
            // Every script block is closed by the page's own tags: the number of openers equals the number of closers.
            $this->assertSame(substr_count(strtolower($post), '<script'), substr_count(strtolower($post), '</script>'));
            $this->assertNotNull(simplexml_load_string((string) $this->get(self::HOST.'/feed.xml')->getContent()), 'the feed is still well-formed XML');
            $this->assertNotFalse(simplexml_load_string((string) $this->get(self::HOST.'/sitemap.xml')->getContent()), 'the sitemap is still well-formed XML');
            // The Dutch twins are fixed pages: a title that names a data-i key changes nothing on them.
            $nl = (string) $this->get(self::HOST.'/nl/projects')->assertOk()->getContent();
            $this->assertStringNotContainsString('onerror', $nl);
        } finally {
            $cms::$rows = [];
            Schema::drop('cms_articles');
        }
    }

    public function test_the_contact_topic_and_old_input_are_escaped_in_the_form(): void
    {
        $evil = '"><script>alert(1)</script>';
        $page = (string) $this->withSession(['_old_input' => ['name' => $evil, 'email' => $evil, 'message' => $evil.'</textarea>']])->get(self::HOST.'/contact?about='.rawurlencode($evil))->assertOk()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)', $page);
        $this->assertStringContainsString('&lt;/textarea&gt;', $page, 'the old message is escaped inside the textarea');
    }

    public function test_the_package_never_echoes_the_host_or_the_url_into_a_page_without_escaping(): void
    {
        $page = (string) $this->get(self::HOST.'/blog?p='.rawurlencode('"><script>alert(1)</script>').'&page='.rawurlencode('<b>'))->assertNotFound()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)', $page);
    }

    private function breakTheDatabase(): void
    {
        $this->cms();
        config(['database.connections.testing' => ['driver' => 'sqlite', 'database' => '/nonexistent-dir/none.sqlite', 'prefix' => '']]);
        DB::purge('testing');
    }

    public function test_every_content_page_renders_with_the_built_in_posts_when_the_database_is_down(): void
    {
        $this->breakTheDatabase();
        Log::spy();
        $paths = ['/', '/cv', '/projects', '/toolkit', '/privacy', '/blog', '/feed.xml', '/sitemap.xml', '/robots.txt', '/site.webmanifest', '/nl', '/nl/cv', '/nl/projects', '/nl/toolkit', '/nl/privacy', '/nl/contact'];
        foreach (Posts::builtIn() as $slug => $_) {
            $paths[] = '/blog/'.$slug;
        }
        foreach ($paths as $path) {
            $this->get(self::HOST.$path)->assertOk();
        }
        $this->get(self::HOST.'/blog/does-not-exist')->assertNotFound();
        $this->assertStringContainsString('strangler-fig-what-the-bridge-taught-me', (string) $this->get(self::HOST.'/sitemap.xml')->getContent());
        Log::shouldHaveReceived('warning')->withArgs(fn ($m, $ctx) => str_contains($m, 'sd-cms') && ! str_contains((string) json_encode($ctx), 'nonexistent'))->atLeast()->once();
    }

    public function test_the_contact_page_renders_when_the_database_is_down_and_the_post_says_so_instead_of_failing(): void
    {
        Mail::fake();
        $this->get(self::HOST.'/contact')->assertOk();
        $this->breakTheDatabase();
        $this->get(self::HOST.'/contact')->assertOk()->assertSee('Tell me what you are running');
        $this->post(self::HOST.'/contact', $this->valid())->assertSessionHasErrors('message');
    }

    public function test_the_contact_page_renders_with_the_database_session_driver_and_the_database_down(): void
    {
        config(['session.driver' => 'database']);
        $this->breakTheDatabase();
        $this->get(self::HOST.'/contact')->assertOk()->assertSee('Tell me what you are running');
        $this->get(self::HOST.'/nl/contact')->assertOk();
    }

    public function test_post_body_links_only_reach_pages_of_this_site_and_everything_else_stays_escaped(): void
    {
        $html = Posts::html('See the [home page](/) and [the CV](/cv#top) and [a topic](/contact?about=Security).');
        $this->assertSame('See the <a href="/">home page</a> and <a href="/cv#top">the CV</a> and <a href="/contact?about=Security">a topic</a>.', $html);
        foreach (['[x](//evil.example)', '[x](https://evil.example)', '[x](javascript:alert(1))', '[x](/a"onmouseover="alert(1))', '[x](/a b)', "[x](/a\nb)", '[<b>x</b>](/)', '[x](/<script>)'] as $bad) {
            $out = Posts::html($bad);
            $this->assertStringNotContainsString('<script', $out, $bad);
            $this->assertStringNotContainsString('<b>', $out, $bad);
            $this->assertStringNotContainsString('href="//', $out, $bad);
            $this->assertStringNotContainsString('href="https', $out, $bad);
            $this->assertStringNotContainsString('href="javascript', $out, $bad);
            $this->assertDoesNotMatchRegularExpression('/<a href="[^"]*["\s<>][^"]*"/', preg_replace('/<a href="[^"<>\s]*">/', '', $out) ?? '', $bad);
        }
    }
}
