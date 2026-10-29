<?php

namespace Spdotdev\SplotnikovDev\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Spdotdev\SplotnikovDev\Http\Controllers\ContactController;
use Spdotdev\SplotnikovDev\Mail\ContactMessage;
use Spdotdev\SplotnikovDev\Posts;
use Spdotdev\SplotnikovDev\Projects;
use Spdotdev\SplotnikovDev\Tests\TestCase;
use Spdotdev\SplotnikovDev\Toolkit;

class SiteTest extends TestCase
{
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
            $this->assertStringContainsString('class="lg', $html, $path);
            $this->assertSame(1, substr_count($html, '<h1'), $path.' has one h1');
            $this->assertSame(3, substr_count($html, 'data-th="'), $path.': three-way theme control, system, light, dark');
            $this->assertStringContainsString('href="http://splotnikov.dev/contact"', $html, $path.' links the contact page');
            $this->assertStringNotContainsString('profile.png', $html, $path.' uses the new portrait');
            $this->assertStringNotContainsString('—', $html, $path.' has no long dashes');
            $this->assertStringNotContainsString('96040947', $html, $path.' carries no Chamber of Commerce number');
            $this->assertStringNotContainsString('NL005184584B85', $html, $path.' carries no VAT number');
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

        $this->assertStringContainsString('--acc:#0d9488;--acc2:#2dd4bf', $css, 'light accent');
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
        $this->assertCount(6, Toolkit::all());
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
            ->assertRedirect('http://splotnikov.dev/contact')->assertSessionHas('contact_sent', true)->assertSessionHasNoErrors();
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->build();
            $rendered = $mail->render();

            return $mail->hasTo('stplotnikov@gmail.com') && $mail->hasReplyTo('test@example.com') && $mail->about === 'Legacy modernization' && str_contains($rendered, 'exports take hours');
        });
    }
}
