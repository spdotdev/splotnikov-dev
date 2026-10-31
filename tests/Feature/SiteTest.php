<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Spdotdev\SplotnikovDev\Archive;
use Spdotdev\SplotnikovDev\Cv;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Mail\ContactMessage;
use Spdotdev\SplotnikovDev\Models\ContactLead;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Projects;
use Spdotdev\SplotnikovDev\Tests\TestCase;
use Spdotdev\SplotnikovDev\Toolkit;

class SiteTest extends TestCase
{
    public function test_the_form_does_not_say_sent_when_the_mailer_only_logs(): void
    {
        config(['splotnikov-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        $page = $this->get('http://splotnikov.dev/contact');
        $token = ContactController::token();
        $this->withSession([])->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', [
            'name' => 'A', 'email' => 'a@example.com', 'message' => 'A real message of enough length.', 't' => $this->agedToken(), 'website' => '',
        ])->assertSessionHas('contact_sent', 'stored')->assertSessionHasNoErrors();
        $lead = ContactLead::query()->sole();
        $this->assertSame('not_configured', $lead->mail_status);
        $this->assertSame('a@example.com', $lead->email);
    }

    public function test_a_contact_mail_dsn_makes_the_form_deliverable_even_when_the_default_mailer_logs(): void
    {
        Mail::fake();
        config(['splotnikov-dev.require_real_mailer' => true, 'mail.default' => 'log', 'splotnikov-dev.mail_dsn' => 'smtp://user:secret@127.0.0.1:2525']);
        $this->get('http://splotnikov.dev/contact');
        $this->withSession([])->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', [
            'name' => 'A', 'email' => 'a@example.com', 'message' => 'A real message of enough length.', 't' => $this->agedToken(), 'website' => '',
        ])->assertSessionHas('contact_sent', 'mailed');
        $this->assertSame('sent', ContactLead::query()->sole()->mail_status);
        $this->assertSame('smtp://user:secret@127.0.0.1:2525', config('mail.mailers.contact_form.url'));
    }

    public function test_the_contact_session_cookie_has_no_shared_domain(): void
    {
        config(['session.domain' => '.scuttle.dev']);
        $cookies = $this->get('http://splotnikov.dev/contact')->assertOk()->headers->getCookies();
        $this->assertNotEmpty($cookies);
        foreach ($cookies as $cookie) {
            $this->assertNull($cookie->getDomain(), $cookie->getName().' must be a host-only cookie on splotnikov.dev');
        }
    }

    public function test_content_pages_set_no_cookie_and_the_contact_form_keeps_its_session(): void
    {
        foreach (['/', '/nl', '/cv', '/projects', '/toolkit', '/blog', '/robots.txt', '/sitemap.xml'] as $path) {
            $response = $this->get('http://splotnikov.dev'.$path)->assertOk();
            $this->assertEmpty($response->headers->getCookies(), $path.' sets a cookie');
        }
        $contact = $this->get('http://splotnikov.dev/contact')->assertOk();
        $this->assertNotEmpty($contact->headers->getCookies(), 'the contact page needs its session for the CSRF token');
        $this->assertStringContainsString('name="_token"', (string) $contact->getContent());
    }

    public function test_every_internal_link_resolves(): void
    {
        $pages = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy', '/blog', '/nl', '/nl/cv', '/nl/contact', '/nl/projects', '/nl/toolkit', '/nl/privacy'];
        foreach (Posts::all() as $post) {
            $pages[] = '/blog/'.$post['slug'];
        }
        $links = [];
        foreach ($pages as $page) {
            $html = (string) $this->get('http://splotnikov.dev'.$page)->assertOk()->getContent();
            preg_match_all('#<a\b[^>]*\shref="(?:https://splotnikov\.dev)?(/[^"\#]*)#', $html, $m);
            foreach ($m[1] as $href) {
                $links[$href] = $page;
            }
        }
        foreach ($links as $href => $from) {
            if (preg_match('#\.(pdf|vcf|png|svg|jpg|ico|webmanifest)$#', $href)) {
                continue;
            }
            $status = $this->get('http://splotnikov.dev'.$href)->getStatusCode();
            $this->assertContains($status, [200, 301], $href.' linked from '.$from.' answers '.$status);
        }
    }

    public function test_sitemap_urls_resolve_and_hreflang_pairs_are_reciprocal(): void
    {
        $host = 'splotnikov.dev';
        $xml = simplexml_load_string((string) $this->get('http://splotnikov.dev/sitemap.xml')->assertOk()->getContent());
        $this->assertNotFalse($xml, 'the sitemap is well-formed XML');
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $seen = [];
        $pairs = [];
        foreach ($xml->url as $url) {
            $loc = (string) $url->loc;
            $this->assertStringStartsWith('https://'.$host, $loc);
            $this->assertArrayNotHasKey($loc, $seen, $loc.' is listed twice');
            $seen[$loc] = true;
            $path = (string) parse_url($loc, PHP_URL_PATH) ?: '/';
            $html = (string) $this->get('http://'.$host.$path)->assertOk()->getContent();
            $this->assertStringContainsString('<link rel="canonical" href="'.$loc.'" />', $html, $loc.' names itself as canonical');
            $robots = preg_match('#<meta name="robots" content="([^"]*)"#', $html, $r) ? $r[1] : '';
            $this->assertStringNotContainsString('noindex', $robots, $loc.' is indexable');
            foreach ($url->children('xhtml', true) as $alt) {
                $attrs = $alt->attributes();
                $pairs[$loc][(string) $attrs['hreflang']] = (string) $attrs['href'];
            }
            preg_match_all('#<link rel="alternate" hreflang="([^"]+)" href="([^"]+)" />#', $html, $m, PREG_SET_ORDER);
            $inPage = [];
            foreach ($m as $row) {
                $inPage[$row[1]] = $row[2];
            }
            if (isset($pairs[$loc])) {
                $this->assertSame($pairs[$loc], $inPage, $loc.': the sitemap alternates and the page alternates are the same');
            }
        }
        foreach ($pairs as $loc => $alts) {
            foreach (['en', 'nl'] as $lang) {
                $this->assertArrayHasKey($lang, $alts, $loc.' lists '.$lang);
                $this->assertArrayHasKey($alts[$lang], $seen, $loc.' points at '.$alts[$lang].' which is not in the sitemap');
                // reciprocal: the other page lists this one under the same two languages
                $this->assertSame($alts, $pairs[$alts[$lang]] ?? null, $alts[$lang].' does not list the same pair back');
            }
            $this->assertSame($alts['en'], $alts['x-default'], $loc.' x-default is the English page');
        }
    }

    public function test_missing_pages_get_the_site_own_404(): void
    {
        foreach (['/nope', '/blog/nope', '/nl/blog'] as $path) {
            $r = $this->get('http://splotnikov.dev'.$path)->assertNotFound();
            $r->assertSee('That page does not exist', false);
            $r->assertSee('noindex', false);
        }
    }

    public function test_dutch_twins_are_real_pages_with_hreflang(): void
    {
        foreach (['/', '/cv', '/contact', '/projects', '/toolkit'] as $path) {
            $nlPath = '/nl'.($path === '/' ? '' : $path);
            $nl = (string) $this->get('http://splotnikov.dev'.$nlPath)->assertOk()->getContent();
            $en = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('<html lang="nl"', $nl, $nlPath);
            $this->assertStringContainsString('<link rel="canonical" href="https://splotnikov.dev'.$nlPath.'" />', $nl, $nlPath.' canonical');
            foreach ([$en, $nl] as $html) {
                $this->assertStringContainsString('hreflang="nl" href="https://splotnikov.dev'.$nlPath.'"', $html, $path.' hreflang nl');
                $this->assertStringContainsString('hreflang="x-default" href="https://splotnikov.dev'.($path === '/' ? '/' : $path).'"', $html, $path.' hreflang x-default');
            }
            preg_match('/<title>(.*?)<\/title>/s', $nl, $t);
            preg_match('/name="description" content="(.*?)"/s', $nl, $d);
            $this->assertLessThanOrEqual(70, mb_strlen(html_entity_decode($t[1])), $nlPath.' title');
            $this->assertLessThanOrEqual(160, mb_strlen(html_entity_decode($d[1])), $nlPath.' description');
            $this->assertNotSame(strip_tags($en), strip_tags($nl), $nlPath.' differs from English');
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $nl, $blocks);
            foreach ($blocks[1] as $json) {
                $this->assertIsArray(json_decode($json, true), $nlPath.' ld+json parses');
            }
        }
        $this->assertStringContainsString('lang="nl"', (string) $this->get('http://splotnikov.dev/nl')->getContent());
        $this->get('http://splotnikov.dev/nl/blog')->assertNotFound();
        $sitemap = (string) $this->get('http://splotnikov.dev/sitemap.xml')->getContent();
        $this->assertNotFalse(simplexml_load_string($sitemap), 'the sitemap is well-formed XML');
        $this->assertStringContainsString('<loc>https://splotnikov.dev/nl</loc>', $sitemap);
        $this->assertStringContainsString('hreflang="nl" href="https://splotnikov.dev/nl/cv"', $sitemap);
        $this->assertStringNotContainsString('/nl/blog', $sitemap);
        $blog = (string) $this->get('http://splotnikov.dev/blog')->getContent();
        $this->assertStringNotContainsString('hreflang="nl"', $blog);
        $this->assertStringContainsString('<button id="nl"', $blog);
    }

    public function test_search_snippets_fit_and_structured_data_is_valid(): void
    {
        $paths = ['/', '/cv', '/contact', '/projects', '/toolkit', '/blog'];
        foreach (Posts::all() as $post) {
            $paths[] = '/blog/'.$post['slug'];
        }
        foreach ($paths as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            preg_match('/<title>(.*?)<\/title>/s', $html, $t);
            preg_match('/name="description" content="(.*?)"/s', $html, $d);
            $this->assertLessThanOrEqual(70, mb_strlen(html_entity_decode($t[1])), $path.' title length');
            $this->assertLessThanOrEqual(160, mb_strlen(html_entity_decode($d[1])), $path.' description length');
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $blocks);
            if ($path !== '/') {
                $this->assertNotEmpty($blocks[1], $path.' has structured data');
            }
            foreach ($blocks[1] as $json) {
                $data = json_decode($json, true);
                $this->assertIsArray($data, $path.' ld+json parses');
                $this->assertSame('https://schema.org', $data['@context'] ?? null, $path.' ld+json context');
            }
            $this->assertStringNotContainsString('fonts.googleapis.com', $html, $path.' loads no Google Fonts');
            preg_match_all('/<h([1-6])[ >]/', (string) preg_replace('#<script.*?</script>#s', '', $html), $levels);
            $prev = 0;
            foreach ($levels[1] as $level) {
                $this->assertLessThanOrEqual($prev + 1, (int) $level, $path.' skips a heading level');
                $prev = (int) $level;
            }
        }
    }

    public function test_portfolio_renders_on_the_configured_host(): void
    {
        // Routes are bound at boot from config('splotnikov-dev.domain'),
        // whose default is splotnikov.dev. Request that host directly.
        $this->get('http://splotnikov.dev/')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_cv_renders_on_the_configured_host(): void
    {
        $this->get('http://splotnikov.dev/cv')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_robots_txt_is_served_at_the_root(): void
    {
        $this->get('http://splotnikov.dev/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');
    }

    public function test_sitemap_xml_lists_every_page(): void
    {
        $this->get('http://splotnikov.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false)
            ->assertSee('https://splotnikov.dev/cv', false)
            ->assertSee('https://splotnikov.dev/contact', false);
    }

    public function test_webmanifest_is_served_at_the_root(): void
    {
        $this->get('http://splotnikov.dev/site.webmanifest')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov')
            ->assertSee('vendor/splotnikov/android-chrome-192x192.png', false);
    }

    public function test_every_page_shares_the_shell(): void
    {
        foreach (['/', '/cv', '/contact', '/projects', '/toolkit', '/blog', '/blog/strangler-fig-what-the-bridge-taught-me'] as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('vendor/splotnikov/site.css', $html, $path);
            $this->assertStringContainsString('vendor/splotnikov/site.js', $html, $path);
            $this->assertStringContainsString('vendor/splotnikov/icon.svg', $html, $path);
            if (in_array($path, ['/', '/cv', '/contact'], true)) {
                $this->assertStringContainsString('vendor/splotnikov/portrait.jpg', $html, $path);
            }
            $this->assertFileExists(__DIR__.'/../../public/og-image.png', 'the social preview image the layout links');
            $this->assertStringContainsString('class="lg', $html, $path);
            $this->assertSame(1, substr_count($html, '<h1'), $path.' has one h1');
            $this->assertSame(3, substr_count($html, 'data-th="'), $path.': three-way theme control, system, light, dark');
            $this->assertStringContainsString('href="http://splotnikov.dev/contact"', $html, $path.' links the contact page');
            $this->assertStringNotContainsString('profile.png', $html, $path.' uses the new portrait');
            $this->assertStringNotContainsString('—', $html, $path.' has no long dashes');
            if ($path !== '/cv') {
                $this->assertStringNotContainsString('96040947', $html, $path.' carries no Chamber of Commerce number');
                $this->assertStringNotContainsString('NL005184584B85', $html, $path.' carries no VAT number');
            }
            $this->assertStringContainsString('title="Curriculum vitae"', $html, $path.' names the CV in full on hover');
        }
    }

    public function test_every_page_is_translatable(): void
    {
        preg_match_all('/\b([a-z0-9_]+): "/', (string) file_get_contents(__DIR__.'/../../resources/views/layout.blade.php'), $shell);
        $paths = ['/', '/cv', '/contact', '/projects', '/toolkit', '/blog'];
        foreach (Posts::all() as $post) {
            $paths[] = '/blog/'.$post['slug'];
        }
        foreach ($paths as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('id="nl"', $html, $path.' has the language switch');
            $this->assertStringContainsString('Object.assign(window.SCUTTLE_NL.t', $html, $path.' pushes its Dutch strings');
            preg_match_all('/data-i="([A-Za-z0-9_]+)"/', $html, $m);
            $own = array_diff(array_unique($m[1]), $shell[1]);
            $this->assertGreaterThanOrEqual(7, count($own), $path.' marks its own text (found '.count($own).' keys)');
            foreach (array_unique($m[1]) as $key) {
                $this->assertTrue((bool) preg_match('/[{,\s]"?'.preg_quote($key, '/').'"?\s*:/', $html), $path.' has no Dutch string for data-i="'.$key.'"');
            }
        }
    }

    public function test_the_home_page_has_the_terminal_panel_and_the_sections(): void
    {
        $home = (string) $this->get('http://splotnikov.dev/')->assertOk()->getContent();

        $this->assertStringContainsString('id="demo"', $home);
        $this->assertSame(9, substr_count($home, 'class="it'), 'nine clickable scenes in the terminal panel');
        $js = (string) file_get_contents(__DIR__.'/../../public/site.js');
        preg_match_all('/data-k="([a-z-]+)"/', $home, $m);
        foreach ($m[1] as $key) {
            $this->assertStringContainsString("'".$key."': Object.assign(run(", $js, 'scene '.$key.' exists in site.js');
        }
        foreach (['id="manifesto"', 'id="projects"', 'id="toolkit"', 'id="contact"', 'class="name" data-dec', 'class="belt', 'class="scroller"', 'href="http://splotnikov.dev/toolkit"', 'PHP since 2008'] as $needle) {
            $this->assertStringContainsString($needle, $home);
        }
        $this->assertStringContainsString('"@type": "Person"', $home);
    }

    public function test_the_accent_is_teal_in_both_themes(): void
    {
        $css = (string) file_get_contents(__DIR__.'/../../public/site.css');

        $this->assertStringContainsString('--acc:#0f6e66;--acc2:#2dd4bf', $css, 'light accent (a deeper teal so text on the grey plates passes 4.5:1)');
        $this->assertStringContainsString('--acc:#2dd4bf;--acc2:#5eead4', $css, 'dark accent');
        $this->assertStringNotContainsString('#CF5A26', $css, 'no Claude orange left');
        $this->assertStringNotContainsString('rgba(232,147,106', $css, 'no orange glow left');
        $this->assertStringContainsString('grid-template-columns:minmax(0,1fr)', $css);
        $this->assertStringContainsString('overflow-x:clip', $css);
        $this->assertStringContainsString('@media print{', $css, 'the CV prints');
    }

    public function test_the_cv_is_printable_and_downloadable(): void
    {
        $cv = (string) $this->get('http://splotnikov.dev/cv')->assertOk()->getContent();

        $this->assertStringContainsString('id="btn-print"', $cv);
        $this->assertStringContainsString('id="btn-pdf"', $cv);
        $this->assertStringContainsString('html2pdf', $cv);
        $this->assertStringContainsString('integrity="sha384-', $cv, 'the PDF library loads with subresource integrity');
        $this->assertStringContainsString('id="cv-content"', $cv);
        $this->assertStringContainsString('id="cv-biz"', $cv, 'the company details block');
        $this->assertStringContainsString('data-biz="0"', $cv, 'the Company / Personal segment that hides it');
        $this->assertStringContainsString('KvK 96040947', $cv);
        foreach (['Executive summary', 'Core competencies', 'Professional experience', 'Employment history', 'Key projects', 'Hosintra B.V.', 'Radius College Breda', 'https://scuttle.dev', '+31 (0) 6 345 00 328'] as $needle) {
            $this->assertStringContainsString($needle, $cv);
        }
    }

    public function test_the_contact_page_has_the_form_and_the_cards(): void
    {
        $contact = (string) $this->get('http://splotnikov.dev/contact')->assertOk()->getContent();

        $this->assertStringContainsString('id="cform"', $contact);
        $this->assertStringContainsString('name="website"', $contact, 'honeypot');
        $this->assertStringContainsString('name="t"', $contact, 'signed timestamp');
        $this->assertSame(2, substr_count($contact, 'class="cc rv"'));
        $this->assertStringContainsString('id="pic-modal"', $contact);
        $this->assertStringContainsString('stplotnikov@gmail.com', $contact);
        $this->assertStringContainsString('href="https://plan.scuttle.dev/book/stas"', $contact);
        $this->assertStringNotContainsString('<iframe', $contact);
        $this->assertLessThan(strpos($contact, 'class="cc rv"'), strpos($contact, 'id="cform"'), 'the form comes first');
        $this->assertStringContainsString('contact-grid"', $contact, 'form and cards side by side');
        preg_match('/<div class="soc">(.*?)<\/div>/s', $contact, $soc);
        $this->assertSame(2, substr_count($soc[1], '<a '), 'the About card links LinkedIn and GitHub only');
        $this->assertStringNotContainsString('/cv"', $soc[1]);
    }

    public function test_projects_toolkit_and_blog_render_from_their_data(): void
    {
        $projects = (string) $this->get('http://splotnikov.dev/projects')->assertOk()->getContent();
        foreach (Projects::all() as $pr) {
            $this->assertStringContainsString('href="'.$pr['url'].'"', $projects);
        }
        $this->assertStringContainsString('Phalcon to Laravel', $projects);
        $this->assertStringContainsString('16 h', $projects);
        $this->assertSame(count(Projects::all()), substr_count((string) $this->get('http://splotnikov.dev/')->getContent(), 'class="c cg rv"'), 'every project is in the home scroller');

        $toolkit = (string) $this->get('http://splotnikov.dev/toolkit')->assertOk()->getContent();
        $this->assertCount(7, Toolkit::all(), 'the same seven groups as the CV expertise');
        foreach (Toolkit::all() as $g) {
            $this->assertStringContainsString($g['title'], $toolkit);
        }
        $this->assertStringContainsString('BackTrack', $toolkit);
        $this->assertSame(3, substr_count((string) $this->get('http://splotnikov.dev/')->getContent(), 'class="c g rv"'), 'three groups on the home page');

        $sitemap = (string) $this->get('http://splotnikov.dev/sitemap.xml')->getContent();
        foreach (['/projects', '/toolkit', '/blog'] as $path) {
            $this->assertStringContainsString('<loc>https://splotnikov.dev'.$path.'</loc>', $sitemap);
        }
        $blog = (string) $this->get('http://splotnikov.dev/blog')->assertOk()->getContent();
        foreach (Posts::all() as $post) {
            $this->assertStringContainsString($post['title'], $blog);
            $this->get('http://splotnikov.dev/blog/'.$post['slug'])->assertOk()->assertSee($post['title']);
            $this->assertStringContainsString('<loc>https://splotnikov.dev/blog/'.$post['slug'].'</loc>', $sitemap);
        }
        $this->get('http://splotnikov.dev/blog/nope')->assertNotFound();
    }

    public function test_the_blog_archive_filters_by_period_and_pages_the_list(): void
    {
        $posts = Posts::all();
        $tree = Archive::tree($posts);
        $this->assertSame([2026], array_keys($tree), 'numeric keys become ints in PHP');
        $this->assertSame([10], array_keys($tree[2026]));
        $this->assertSame(count($posts), array_sum($tree[2026][10]));

        $this->assertCount(count($posts), Archive::filter($posts, '2026'));
        $this->assertCount(count($posts), Archive::filter($posts, '2026-10'));
        $this->assertCount(0, Archive::filter($posts, '2025'));
        $this->assertCount(count($posts), Archive::filter($posts, 'nonsense'), 'a malformed period keeps everything');

        [$page, $n, $pages] = Archive::page($posts, 1, 1);
        $this->assertCount(1, $page);
        $this->assertSame([1, count($posts)], [$n, $pages]);
        [$page, $n] = Archive::page($posts, 99, 1);
        $this->assertSame(count($posts), $n, 'a page past the end clamps to the last page');
        $this->assertSame('October 2026', Archive::label('2026-10'));
        $this->assertSame('29 oktober 2026', Archive::label('2026-10-29', 'nl'));
        $this->assertSame('2026-W44', Archive::week('2026-10-29'));
        $this->assertCount(count($posts), Archive::filter($posts, '2026-W44'));
        $this->assertCount(0, Archive::filter($posts, '2026-W43'));
        $this->assertSame('Week 44, 2026', Archive::label('2026-W44'));
        $rows = Archive::calendar($tree, '2026-10');
        $this->assertSame('2026-W40', $rows[0]['week'], 'October 2026 starts in ISO week 40');
        $this->assertSame(1, $rows[0]['days'][3]['day'], 'the first of October 2026 is a Thursday');
        $this->assertSame(count($posts), array_sum(array_map(fn ($r) => array_sum(array_column($r['days'], 'posts')), $rows)));
        $this->assertSame('2026-10', Archive::calendarMonth($tree, ''));
        $this->assertSame('2026-10', Archive::calendarMonth($tree, '2026-W44'));

        $html = (string) $this->get('http://splotnikov.dev/blog')->assertOk()->getContent();
        $this->assertStringContainsString('class="arch"', $html);
        $this->assertStringContainsString('?p=2026-10-29', $html);
        $this->get('http://splotnikov.dev/blog?p=2026-10-29')->assertOk()->assertSee('29 October 2026');
        $this->get('http://splotnikov.dev/blog?p=2025')->assertOk()->assertSee('Nothing on this page.');
        $this->get('http://splotnikov.dev/blog?page=5')->assertOk();
        $this->get('http://splotnikov.dev/blog?p=2026-W44')->assertOk()->assertSee('Week 44, 2026');
        $this->assertStringContainsString('class="cal"', $html);
        $this->get('http://splotnikov.dev/blog?m=2026-09')->assertOk()->assertSee('September')->assertSee('?m=2026-08', false);
    }

    public function test_the_contact_form_sends_mail_and_blocks_bots(): void
    {
        Mail::fake();
        $ok = ['name' => 'Test Person', 'email' => 'test@example.com', 'about' => 'Legacy modernization', 'message' => 'We run a Laravel shop and the exports take hours.'];
        $token = ContactController::token();

        // Too fast: the signed timestamp is newer than 3 seconds.
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $ok + ['t' => $token])
            ->assertRedirect('http://splotnikov.dev/contact')->assertSessionHasErrors('message');
        Mail::assertNothingSent();

        $old = (string) (time() - 10);
        $oldToken = $old.'.'.hash_hmac('sha256', $old, (string) config('app.key'));

        // Honeypot filled: rejected.
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $ok + ['t' => $oldToken, 'website' => 'http://spam'])
            ->assertSessionHasErrors('website');

        // Forged signature: rejected.
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $ok + ['t' => $old.'.forged'])
            ->assertSessionHasErrors('message');
        Mail::assertNothingSent();

        // A real message: mailed to the configured address with the sender as reply-to.
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $ok + ['t' => $oldToken])
            ->assertRedirect('http://splotnikov.dev/contact')->assertSessionHas('contact_sent', 'mailed')->assertSessionHasNoErrors();
        $this->assertSame(1, ContactLead::query()->count(), 'only the real message is stored, not the rejected ones');
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->build();
            $rendered = $mail->render();

            return $mail->hasTo('stplotnikov@gmail.com') && $mail->hasReplyTo('test@example.com') && $mail->about === 'Legacy modernization' && str_contains($rendered, 'exports take hours');
        });
    }

    public function test_an_sd_cms_article_maps_to_a_post_and_lands_in_the_sitemap(): void
    {
        $article = (object) ['slug' => 'from-cms', 'title' => 'From the CMS', 'summary' => 'Sum', 'body' => "First.\n\n## Head\n\n- one\n- two\n\nLast.", 'tags' => ['PHP', 'News'], 'published_at' => new \DateTimeImmutable('2026-11-02 10:00:00'), 'title_nl' => 'Uit het CMS', 'summary_nl' => null, 'sells' => 'maintenance-retainer'];
        $post = Posts::fromArticle($article);

        $this->assertSame('from-cms', $post['slug']);
        $this->assertSame('2026-11-02', $post['date']);
        $this->assertSame(['First.', '## Head', '- one', '- two', 'Last.'], $post['body']);
        $this->assertSame(['title' => 'Uit het CMS', 'summary' => 'Sum'], $post['nl']);
        $this->assertArrayHasKey('strangler-fig-what-the-bridge-taught-me', Posts::all(), 'built-in articles stay without sd-cms');
        $this->get('http://splotnikov.dev/sitemap.xml')->assertOk()->assertSee('<urlset', false);
    }

    /** A form token that is old enough to pass the minimum fill time (3 seconds). */
    private function agedToken(): string
    {
        $t = (string) (time() - 10);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    public function test_the_cv_has_an_at_a_glance_block_and_profile_data(): void
    {
        $html = (string) $this->get('http://splotnikov.dev/cv')->assertOk()->getContent();
        $this->assertStringContainsString('EUR 90 per hour excl. VAT', $html);
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $types = array_map(fn ($j) => json_decode($j, true, 512, JSON_THROW_ON_ERROR)['@type'] ?? '', $m[1]);
        $this->assertContains('ProfilePage', $types);
        $this->assertStringContainsString('EUR 90 per uur excl. btw', json_encode(Cv::nl(), JSON_UNESCAPED_UNICODE));
    }

    /** @return array<string, string> */
    private function validForm(array $over = []): array
    {
        return $over + ['name' => 'Anna', 'email' => 'anna@example.com', 'about' => 'Hello', 'message' => 'We run an old PHP portal and need a plan.', 't' => $this->agedToken(), 'website' => ''];
    }

    public function test_a_stored_lead_survives_a_mail_failure(): void
    {
        config(['splotnikov-dev.require_real_mailer' => true, 'mail.default' => 'smtp', 'splotnikov-dev.mail_dsn' => '']);
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('smtp down'));
        $this->from('http://splotnikov.dev/nl/contact')->post('http://splotnikov.dev/contact', $this->validForm())
            ->assertSessionHas('contact_sent', 'stored')->assertSessionHasNoErrors();
        $lead = ContactLead::query()->sole();
        $this->assertSame('failed', $lead->mail_status);
        $this->assertSame('nl', $lead->language);
        $this->assertSame('/nl/contact', $lead->page);
    }

    public function test_the_page_says_saved_and_not_sent_when_only_stored(): void
    {
        config(['splotnikov-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm())->assertSessionHas('contact_sent', 'stored');
        $html = (string) $this->withSession(['contact_sent' => 'stored'])->get('http://splotnikov.dev/contact')->getContent();
        $this->assertStringContainsString('Received and saved', $html);
        $this->assertStringNotContainsString('data-i="ctp_sent"', $html);
    }

    public function test_the_ip_is_stored_only_as_a_hash(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm());
        $lead = ContactLead::query()->sole();
        $this->assertSame(32, strlen((string) $lead->ip_hash));
        $this->assertStringNotContainsString('203.0.113.9', json_encode($lead->getAttributes()));
    }

    public function test_nothing_is_stored_when_the_message_cannot_be_stored(): void
    {
        Schema::drop('splotnikov_contact_leads');
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm())
            ->assertSessionHasErrors('message')->assertSessionMissing('contact_sent');
    }

    public function test_the_leads_command_lists_and_purges_only_test_rows(): void
    {
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm());
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm(['name' => '[TEST] check']));
        $this->assertSame(1, ContactLead::query()->where('is_test', true)->count());
        $this->artisan('splotnikov:contact-leads')->expectsOutputToContain('2 shown, 2 stored in total')->assertSuccessful();
        $this->artisan('splotnikov:contact-leads', ['--purge-test' => true])->expectsOutputToContain('Deleted 1 test')->assertSuccessful();
        $this->assertSame(['Anna'], ContactLead::query()->pluck('name')->all());
    }

    public function test_a_failing_insert_logs_no_visitor_data(): void
    {
        Schema::drop('splotnikov_contact_leads');
        Log::shouldReceive('error')->once()->withArgs(function (string $msg, array $ctx = []): bool {
            $all = $msg.json_encode($ctx);
            foreach (['Anna', 'anna@example.com', 'old PHP portal', 'insert into'] as $secret) {
                if (stripos($all, $secret) !== false) {
                    return false;
                }
            }

            return isset($ctx['exception']) && isset($ctx['sqlstate']) && ! isset($ctx['error']);
        });
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm())->assertSessionHasErrors('message');
    }

    public function test_control_characters_are_rejected_at_validation(): void
    {
        foreach ([['name' => "An\e[2Jna"], ['email' => "a\x00@example.com"], ['about' => "x\ry"], ['message' => "Hello there \e[31mred\e[0m please"], ['message' => "Hello there \x07 bell please"]] as $bad) {
            $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm($bad))->assertSessionHasErrors();
        }
        $this->assertSame(0, ContactLead::query()->count());
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm(['message' => "Line one of the message\nLine two\twith a tab."]))->assertSessionHasNoErrors();
        $this->assertSame(1, ContactLead::query()->count());
    }

    public function test_the_leads_command_prints_stored_text_without_escapes_or_tags(): void
    {
        $l = ContactLead::create(['name' => "Eve\e[2J<info>x</info>", 'email' => 'e@example.com', 'about' => "a\e[31m", 'message' => "Hello \e[2J<error>boom</error>\x07 there", 'language' => 'en', 'mail_status' => 'pending']);
        foreach ([[], ['--id' => $l->id]] as $opts) {
            Artisan::call('splotnikov:contact-leads', $opts);
            $out = Artisan::output();
            $this->assertStringNotContainsString("\e", $out);
            $this->assertStringNotContainsString("\x07", $out);
            $this->assertStringContainsString('<info>', $out, 'a style tag from a visitor is printed literally, not applied');
        }
    }

    public function test_a_lead_without_a_sent_mail_raises_a_critical_log_line_with_no_visitor_data(): void
    {
        config(['splotnikov-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        Log::shouldReceive('critical')->once()->withArgs(function (string $msg, array $ctx = []): bool {
            foreach (['Anna', 'anna@example.com', 'old PHP portal'] as $secret) {
                if (stripos($msg.json_encode($ctx), $secret) !== false) {
                    return false;
                }
            }

            return str_contains($msg, 'lead #1') && str_contains($msg, 'splotnikov:contact-leads --unmailed');
        });
        $this->from('http://splotnikov.dev/contact')->post('http://splotnikov.dev/contact', $this->validForm())->assertSessionHas('contact_sent', 'stored');
    }

    public function test_the_own_analytics_snippet_only_exists_with_a_key_and_the_statement_follows(): void
    {
        $off = $this->get('http://splotnikov.dev/privacy')->assertOk();
        $this->assertStringNotContainsString('/_sda/', (string) $this->get('http://splotnikov.dev/')->getContent());
        $this->assertStringContainsString('no analytics of any kind is loaded', (string) $off->getContent());

        config(['splotnikov-dev.analytics_key' => 'abcDEF123456']);
        $response = $this->get('http://splotnikov.dev/');
        $home = (string) $response->getContent();
        $this->assertStringContainsString('<script defer data-site="abcDEF123456" src="/_sda/s.js"></script>', $home);
        $this->assertStringContainsString('<img src="/_sda/px/abcDEF123456.gif"', $home);
        $this->assertEmpty($response->headers->getCookies(), 'reading a page still sets no cookie');

        $on = (string) $this->get('http://splotnikov.dev/privacy')->getContent();
        foreach (['I count visits with a small tool that runs on my own server', 'does not store your IP address', 'Do Not Track', 'Ik tel bezoeken met een klein programma', 'Privacyverklaring'] as $needle) {
            $this->assertStringContainsString($needle, $on);
        }
        $this->assertStringNotContainsString('no analytics of any kind is loaded', $on);
        $this->get('http://splotnikov.dev/nl/privacy')->assertOk()->assertSee('Privacyverklaring', false);
        $this->get('http://splotnikov.dev/sitemap.xml')->assertSee('https://splotnikov.dev/privacy', false);
    }

    public function test_the_contact_success_event_is_only_added_with_a_key(): void
    {
        $this->withSession(['contact_sent' => 'mailed'])->get('http://splotnikov.dev/contact')->assertDontSee("sda('Contact form sent')", false);
        config(['splotnikov-dev.analytics_key' => 'abcDEF123456']);
        $this->withSession(['contact_sent' => 'mailed'])->get('http://splotnikov.dev/contact')->assertSee("sda('Contact form sent')", false);
    }
    /** Stas, 2026-10-31: mail and phone links live on the contact page only; the CV keeps the addresses as plain text. */
    public function test_only_the_contact_page_carries_mail_and_phone_links(): void
    {
        foreach (['/', '/cv', '/projects', '/privacy', '/nl', '/nl/cv', '/nl/privacy'] as $path) {
            $html = (string) $this->get('http://splotnikov.dev'.$path)->assertOk()->getContent();
            $this->assertStringNotContainsString('mailto:', $html, $path);
            $this->assertStringNotContainsString('href="tel:', $html, $path);
        }
        $this->assertStringContainsString('<span>stplotnikov@gmail.com</span>', (string) $this->get('http://splotnikov.dev/cv')->getContent());
        $contact = (string) $this->get('http://splotnikov.dev/contact')->assertOk()->getContent();
        $this->assertStringContainsString('href="mailto:stplotnikov@gmail.com"', $contact);
        $this->assertStringContainsString('href="tel:+31634500328"', $contact);
    }

}
