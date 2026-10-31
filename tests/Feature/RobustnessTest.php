<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Spdotdev\SplotnikovDev\Archive;
use Spdotdev\SplotnikovDev\Cases;
use Spdotdev\SplotnikovDev\Cv;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Models\ContactLead;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Projects;
use Spdotdev\SplotnikovDev\Tests\TestCase;
use Spdotdev\SplotnikovDev\Toolkit;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Round 9: the contact form must never lose or garble a lead, odd requests must not break a page, and the static
 * rules of the markup (images, links, ids, headings) hold on every page in both languages.
 */
class RobustnessTest extends TestCase
{
    private const HOST = 'http://splotnikov.dev';

    private function agedToken(): string
    {
        $t = (string) (time() - 10);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /** @return list<string> every page of the site in both languages, with the blog posts */
    private function pages(): array
    {
        $pages = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog'];
        foreach (Posts::all() as $post) {
            $pages[] = '/blog/'.$post['slug'];
        }
        foreach (['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy'] as $p) {
            $pages[] = '/nl'.($p === '/' ? '' : $p);
        }

        return $pages;
    }

    public function test_the_dutch_contact_page_shows_the_topics_in_dutch_and_posts_the_english_values(): void
    {
        $html = (string) $this->get(self::HOST.'/nl/contact')->assertOk()->getContent();
        preg_match('#<select name="about".*?</select>#s', $html, $m);
        $select = $m[0] ?? '';
        $this->assertStringContainsString('>Nog niet zeker<', $select);
        $this->assertStringContainsString('>Een freelance- of contractrol<', $select);
        $this->assertStringContainsString('>Beveiliging<', $select);
        foreach (['Not sure yet', 'A freelance or contract role', 'Legacy modernization', 'Automation with AI agents', 'Architecture advice', 'Something else'] as $english) {
            $this->assertStringNotContainsString('>'.$english.'<', $select, $english.' is still English on the Dutch page');
        }
        $this->assertStringContainsString('value="Freelance or contract role"', $select, 'the server keeps receiving the English value');
        $this->assertStringContainsString('value="Security"', $select);
    }

    public function test_the_contact_lead_is_one_sentence_plus_the_promise_in_both_languages(): void
    {
        foreach (['/contact', '/nl/contact'] as $path) {
            $html = (string) $this->get(self::HOST.$path)->assertOk()->getContent();
            $this->assertSame(1, preg_match('#<p class="lead"[^>]*>(.*?)</p>#s', $html, $m), $path);
            $this->assertLessThanOrEqual(35, str_word_count(strip_tags($m[1])), $path.' lead is short enough for the first screen of a phone');
        }
    }

    public function test_validation_errors_are_translated_visible_at_the_top_and_keep_what_was_typed(): void
    {
        $bad = ['name' => '', 'email' => 'not-an-email', 'about' => 'Security', 'message' => 'short', 't' => $this->agedToken(), 'website' => ''];

        $en = $this->from(self::HOST.'/contact')->post(self::HOST.'/contact', $bad);
        $en->assertRedirect(self::HOST.'/contact')->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertSame('Please enter your name.', session('errors')->first('name'));
        $this->assertSame('That email address does not look right. Please check it.', session('errors')->first('email'));

        $nl = $this->from(self::HOST.'/nl/contact')->post(self::HOST.'/contact', $bad);
        $nl->assertRedirect(self::HOST.'/nl/contact')->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertSame('Vul je naam in.', session('errors')->first('name'));
        $this->assertSame('Je bericht is te kort. Schrijf minstens 10 tekens.', session('errors')->first('message'));
        $this->assertSame(0, ContactLead::query()->count(), 'nothing is stored for an invalid message');

        // The page that follows shows every error above the first field, and the topic the visitor picked stays selected.
        $page = (string) $this->withSession(['errors' => session('errors'), '_old_input' => $bad])->get(self::HOST.'/contact')->assertOk()->getContent();
        $this->assertGreaterThan(0, $errAt = (int) strpos($page, 'class="err"'));
        $this->assertLessThan((int) strpos($page, 'name="name"'), $errAt, 'the error sits above the first field');
        $this->assertStringContainsString('Vul je naam in.', $page);
        $this->assertMatchesRegularExpression('#<option [^>]*value="Security"[^>]* selected#', $page, 'the chosen topic survives the error');
    }

    public function test_the_bot_check_message_is_dutch_on_the_dutch_page(): void
    {
        $ok = ['name' => 'Test Person', 'email' => 'test@example.com', 'message' => 'A message that is long enough.', 'website' => ''];
        $this->from(self::HOST.'/nl/contact')->post(self::HOST.'/contact', $ok + ['t' => ContactController::token()]);
        $this->assertStringStartsWith('De botcontrole is mislukt', session('errors')->first('message'));
        $this->from(self::HOST.'/contact')->post(self::HOST.'/contact', $ok + ['t' => ContactController::token()]);
        $this->assertStringStartsWith('The bot check failed', session('errors')->first('message'));
    }

    public function test_after_a_send_the_message_comes_first_in_the_form_and_the_form_is_empty(): void
    {
        Mail::fake();
        $ok = ['name' => 'Test Person', 'email' => 'test@example.com', 'about' => 'Security', 'message' => 'A message that is long enough.', 't' => $this->agedToken(), 'website' => ''];
        $this->from(self::HOST.'/contact')->post(self::HOST.'/contact', $ok)->assertSessionHas('contact_sent', 'mailed');
        $this->assertSame(1, ContactLead::query()->count());

        $page = (string) $this->withSession(['contact_sent' => 'mailed'])->get(self::HOST.'/contact')->assertOk()->getContent();
        $this->assertLessThan((int) strpos($page, 'name="name"'), (int) strpos($page, 'class="sent"'), 'the confirmation is above the first field');
        $this->assertStringContainsString('Sent. I reply within one working day.', $page);
        $this->assertStringNotContainsString('value="Test Person"', $page, 'the form is cleared after a send');
        $this->assertStringContainsString("scrollIntoView({block:'start'})", $page, 'the message is scrolled into view');
        $this->assertStringContainsString('#cform .sent,#cform .err', $page);
    }

    public function test_a_script_in_the_about_parameter_is_escaped_and_selects_nothing(): void
    {
        $page = (string) $this->get(self::HOST.'/contact?about='.rawurlencode('<script>alert(1)</script>'))->assertOk()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)', $page);
        $this->assertSame(0, preg_match('#<option [^>]*\sselected#', $page), 'no topic is selected');
        $this->get(self::HOST.'/nl/contact?about=Security')->assertOk();
    }

    public function test_odd_requests_get_clean_answers(): void
    {
        $this->get(self::HOST.'/nl/does-not-exist')->assertNotFound()->assertSee('noindex', false);
        $this->get(self::HOST.'/projects/does-not-exist')->assertNotFound();
        $this->get(self::HOST.'/blog/'.str_repeat('a', 400))->assertNotFound();
        $this->get(self::HOST.'/?x='.str_repeat('a', 6000))->assertOk();
        $this->get(self::HOST.'/blog?p='.str_repeat('9', 300).'&m='.str_repeat('x', 300).'&page=abc')->assertOk();
        foreach (['/', '/cv', '/contact', '/nl', '/nl/cv'] as $path) {
            $head = $this->call('HEAD', self::HOST.$path);
            $this->assertSame(200, $head->getStatusCode(), 'HEAD '.$path);
            $this->assertSame('', (string) $head->getContent(), 'HEAD '.$path.' has no body');
        }
        foreach (['/cv', '/projects', '/nl', '/blog'] as $path) {
            $this->assertSame(405, $this->post(self::HOST.$path)->getStatusCode(), 'POST '.$path);
        }
        $this->assertSame(405, $this->call('PUT', self::HOST.'/contact')->getStatusCode());
    }

    public function test_every_image_has_size_and_alt_every_external_link_is_safe_ids_are_unique_and_headings_do_not_skip(): void
    {
        foreach ($this->pages() as $path) {
            $html = (string) $this->get(self::HOST.$path)->assertOk()->getContent();
            $body = (string) preg_replace('#<script\b.*?</script>#s', '', $html);

            preg_match_all('#<img\b[^>]*>#i', $body, $imgs);
            foreach ($imgs[0] as $img) {
                $this->assertMatchesRegularExpression('#\swidth="\d+"#', $img, $path.': '.$img.' has no width');
                $this->assertMatchesRegularExpression('#\sheight="\d+"#', $img, $path.': '.$img.' has no height');
                $this->assertMatchesRegularExpression('#\salt="#', $img, $path.': '.$img.' has no alt');
            }

            preg_match_all('#<a\b[^>]*>#i', $body, $links);
            foreach ($links[0] as $a) {
                if (preg_match('#\shref="https?://(?!splotnikov\.dev)#', $a) || str_contains($a, 'target="_blank"')) {
                    $this->assertMatchesRegularExpression('#\srel="[^"]*noopener#', $a, $path.': '.$a.' lacks rel="noopener"');
                }
            }

            preg_match_all('#\sid="([^"]+)"#', $body, $ids);
            $dupes = array_keys(array_filter(array_count_values($ids[1]), fn (int $n): bool => $n > 1));
            $this->assertSame([], $dupes, $path.' has duplicate ids');

            preg_match_all('#<h([1-6])\b#', $body, $h);
            $levels = array_map('intval', $h[1]);
            $this->assertSame(1, count(array_filter($levels, fn (int $l): bool => $l === 1)), $path.' has exactly one h1');
            $prev = 0;
            foreach ($levels as $l) {
                $this->assertLessThanOrEqual($prev + 1, $l, $path.' skips a heading level (h'.$prev.' then h'.$l.')');
                $prev = $l;
            }
        }
    }

    public function test_every_footer_link_answers(): void
    {
        foreach (['/', '/nl/cv'] as $path) {
            $html = (string) $this->get(self::HOST.$path)->assertOk()->getContent();
            $this->assertSame(1, preg_match('#<footer\b.*?</footer>#s', $html, $f), $path.' has a footer');
            preg_match_all('#<a\b[^>]*\shref="(?:https?://splotnikov\.dev)?(/[^"\#]*)#', $f[0], $m);
            $this->assertNotEmpty($m[1]);
            foreach (array_unique($m[1]) as $href) {
                if (preg_match('#\.(pdf|vcf|png|svg|jpg|ico|webmanifest)$#', $href)) {
                    continue;
                }
                $this->assertContains($this->get(self::HOST.$href)->getStatusCode(), [200, 301], 'footer link '.$href.' on '.$path);
            }
        }
    }

    public function test_odd_cms_articles_do_not_break_the_blog_the_post_page_or_the_sitemap(): void
    {
        // A stand-in for the optional sd-cms model: the class and the table both have to exist for Posts to read it.
        if (! class_exists('Spdotdev\\SdCms\\Models\\CmsArticle')) {
            eval('namespace Spdotdev\\SdCms\\Models; class CmsArticle { public static array $rows = []; public static function published(string $site) { return new class { public function get() { return new \\Illuminate\\Support\\Collection(CmsArticle::$rows); } }; } }');
        }
        Schema::create('cms_articles', function ($t) {
            $t->id();
        });
        $cms = 'Spdotdev\\SdCms\\Models\\CmsArticle';
        $cms::$rows = [
            (object) ['slug' => 'odd-html', 'title' => '<img src=x onerror=alert(1)> "quoted" & </script><b>bold</b>', 'summary' => '</script><script>alert(1)</script>', 'body' => "<script>alert(1)</script>\n\n## <i>Head</i>\n\n- <b>item</b>", 'tags' => ['<b>tag</b>'], 'published_at' => '2026-10-31 09:00:00', 'title_nl' => null, 'summary_nl' => null],
            (object) ['slug' => 'very-long-title', 'title' => str_repeat('Long title words ', 40), 'summary' => str_repeat('Long summary ', 60), 'body' => 'Short body.', 'tags' => [], 'published_at' => '2026-10-30 09:00:00', 'title_nl' => '', 'summary_nl' => ''],
            (object) ['slug' => 'empty-body', 'title' => 'Empty body', 'summary' => '', 'body' => '', 'tags' => null, 'published_at' => null, 'title_nl' => null, 'summary_nl' => null],
        ];
        try {
            foreach (['/blog', '/blog/odd-html', '/blog/very-long-title', '/blog/empty-body'] as $path) {
                $html = (string) $this->get(self::HOST.$path)->assertOk()->getContent();
                $this->assertStringNotContainsString('<script>alert(1)', $html, $path.' lets a script through');
                $this->assertStringNotContainsString('<img src=x', $html, $path.' lets markup through');
                $this->assertStringNotContainsString('<b>bold</b>', $html, $path.' lets markup through');
                preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $ld);
                foreach ($ld[1] as $block) {
                    $this->assertNotNull(json_decode($block, true), $path.' has invalid JSON-LD');
                }
            }
            $this->get(self::HOST.'/blog/odd-html')->assertSee('&lt;img src=x', false);
            $this->get(self::HOST.'/sitemap.xml')->assertOk()->assertSee('odd-html', false);
            $this->assertSame(1, preg_match('#<h1\b#', (string) $this->get(self::HOST.'/blog/empty-body')->getContent()));
        } finally {
            $cms::$rows = [];
            Schema::drop('cms_articles');
        }
    }

    public function test_the_data_classes_survive_an_empty_or_unknown_key(): void
    {
        $this->assertSame([], Archive::filter([], '2026-W44'));
        $this->assertIsArray(Archive::tree([]));
        $this->assertIsArray(Archive::calendar([], '2026-10'));
        $this->assertNotEmpty(Projects::all());
        $this->assertNotEmpty(Cases::all());
        $this->assertNotEmpty(Toolkit::all());
        $this->assertNotEmpty(Toolkit::home());
        $this->assertNotEmpty(Cv::all());
        $this->assertNotEmpty(Cv::nl());
        $this->expectException(NotFoundHttpException::class);
        Posts::get('no-such-post');
    }
}
