<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Support\Facades\Schema;

/**
 * The personal blog: technology, what I am busy with, why one thing beats
 * another, from my own experience. First person, no client names. Body
 * paragraphs are plain text; a line starting with "## " is a heading, a line
 * starting with "- " is a list item.
 *
 * @phpstan-type Post array{slug:string, title:string, date:string, summary:string, tags:list<string>, body:list<string>, nl:array{title:string, summary:string}}
 */
final class Posts
{
    /**
     * Published articles from sd-cms (written over MCP, reviewed in the host's
     * Filament panel) first, then the built-in ones; an article with the same slug
     * replaces the built-in one.
     *
     * @return array<string, Post>
     */
    public static function all(): array
    {
        return self::fromCms() + self::builtIn();
    }

    /**
     * @return array<string, Post>
     */
    private static function fromCms(): array
    {
        $class = 'Spdotdev\\SdCms\\Models\\CmsArticle';
        if (! class_exists($class)) {
            return [];
        }
        try {
            if (! Schema::hasTable('cms_articles')) {
                return [];
            }
            $posts = [];
            // sd-cms is an optional sibling package in the host, not a dependency of this one.
            foreach ($class::published('splotnikov')->get() as $article) {
                $post = self::fromArticle($article);
                $posts[$post['slug']] = $post;
            }

            return $posts;
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Map one sd-cms article (any object with its columns) to the Post shape.
     *
     * @return Post
     */
    public static function fromArticle(object $a): array
    {
        // One entry per non-empty line: a paragraph, a "## " heading or a "- " list item, as the view renders them.
        $body = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $a->body) ?: []), fn (string $l): bool => $l !== ''));
        $date = $a->published_at instanceof \DateTimeInterface ? $a->published_at->format('Y-m-d') : substr((string) ($a->published_at ?? date('Y-m-d')), 0, 10);

        return [
            'slug' => (string) $a->slug,
            'title' => (string) $a->title,
            'date' => $date,
            'summary' => (string) $a->summary,
            'tags' => array_values(array_map('strval', (array) ($a->tags ?? []))),
            'body' => $body,
            'nl' => ['title' => (string) ($a->title_nl ?: $a->title), 'summary' => (string) ($a->summary_nl ?: $a->summary)],
        ];
    }

    /**
     * @return array<string, Post>
     */
    public static function builtIn(): array
    {
        return [
            'one-stylesheet-one-script-a-live-terminal' => [
                'slug' => 'one-stylesheet-one-script-a-live-terminal',
                'title' => 'What I am busy with: rebuilding this site with one stylesheet, one script and a live terminal',
                'date' => '2026-10-29',
                'summary' => 'No framework, no build step, no third-party tracking. Why a personal site in 2026 is a Laravel package with two static files, and how the terminal panel on the home page works.',
                'tags' => ['Laravel', 'Front end', 'Claude Code'],
                'body' => [
                    'This site used to be a single HTML file with 27 kilobytes of inline CSS and a particle canvas. It looked fine and nobody could change it without breaking something. The version you are reading is a Laravel library package: one Blade layout, one stylesheet, one script, mounted into a host application that serves a few of my domains from one small server.',
                    '## Why a package and not an app',
                    'Every site I run shares the same host: one Docker Compose stack, one deploy pipeline, one set of backups. A site is a Composer package with a Route::domain() group, tagged by version. The host pins the tag in its lock file; a deploy is a push to main. The site cannot drift from what is committed, because the next unrelated deploy would put it back.',
                    '## Why two static files',
                    '- A page like this does not need a framework. The CSS is 62 kilobytes (14 on the wire, compressed) with the dark theme, the print layout for the CV and the terminal panel included. The script is 29 kilobytes (10 compressed) and does the language switch, the theme, the headline decrypt and the panel.',
                    '- No build step means no node_modules, no lockfile drift, no "it works on my machine". The files are served as they are in the repository.',
                    '- No third-party tracking. No analytics script from anyone else, no cookie banner, nothing from a third party except, on the CV page, the PDF library. The two fonts are served from the same host since 30 October 2026, so the page makes no request to Google.',
                    '## The terminal panel',
                    'The panel on the home page is not a video. Each scene is an array of typed lines (a prompt, a code line or a log line, with a class for the colour) and a few result rows that link to the section they belong to. The script types them with a delay, fills the progress bar, then rotates to the next scene unless you hover over it. The scenes are re-enactments from my own lab and projects with made-up hosts; nothing runs.',
                    'Adding a scene is adding an entry to one object and a button to one view. A test checks that every button has a scene. That is the whole content pipeline.',
                    '## What Claude Code did',
                    'Most of the port was done with Claude Code: copying the theme from my company site, swapping the accent to the green this site had, writing the scenes, the CV print layout and the contact form, and taking screenshots in light, dark and phone width before anything deployed. I read every diff and changed what I did not like. The rule I keep: the agent proposes, the tests and I decide.',
                ],
                'nl' => ['title' => 'Waar ik mee bezig ben: deze site herbouwen met één stylesheet, één script en een live terminal', 'summary' => 'Geen framework, geen buildstap, geen tracking door derden. Waarom een persoonlijke site in 2026 een Laravel-pakket met twee statische bestanden is, en hoe het terminalpaneel op de homepage werkt.'],
            ],
            'strangler-fig-what-the-bridge-taught-me' => [
                'slug' => 'strangler-fig-what-the-bridge-taught-me',
                'title' => 'Strangler Fig in practice: what running two frameworks in one app taught me',
                'date' => '2026-10-29',
                'summary' => 'On a whiteboard the pattern is simple: route by route, old and new side by side. The hard parts are the session, the database and the people. Notes from a migration that stayed in daily use.',
                'tags' => ['PHP', 'Architecture', 'Modernization'],
                'body' => [
                    'A rewrite is the most expensive way to replace software, because for months you pay for two systems and only one of them earns money. The Strangler Fig pattern avoids that: the new code takes over one route at a time, the old code keeps serving the rest, and the business never notices a cutover. I spent a year doing exactly this, moving a platform from an old framework on an unsupported PHP to Laravel on PHP 8.4. These are the parts the diagrams leave out.',
                    '## One session for both',
                    'The bridge is a middleware: it looks at the path, and either hands the request to the old framework or lets the new one handle it. The catch is the session. If the two frameworks keep separate sessions, a user logs in on a migrated page and is logged out on the next. The fix was one session store, read by both, with the same cookie, so the login was simply shared.',
                    '## One database, no second truth',
                    '- The new module reads and writes the same tables as the old one. No sync job, no replica, no "we will merge the data later".',
                    '- Schema changes are expand first, contract later: add the column, backfill, switch the writers, drop the old column in a later release. Never in one step.',
                    '- Tests around the old behaviour before it changes. Characterization tests, not unit tests: what does this endpoint return today, exactly.',
                    '## A flag per module',
                    'Every migrated module got a flag that routes it back to the old code. Not a feature flag framework, a line in a config file. It was used twice. Both times the business did not notice, and both times the fix shipped the same day. That is the whole argument for small releases.',
                    '## The runtime on the way',
                    'The PHP upgrade from 7.4 to 8.4 happened during the migration, not after it. Waiting would have meant another year on a runtime without security fixes. Each minor version was its own release with its own test run; the deprecation list was the roadmap.',
                    '## The people part',
                    'The developers who knew the old framework were the ones who had to learn the new one, on a system that kept running. That worked because every release was small enough to review in an afternoon and every rollback was one line. Confidence came from the flags, not from a plan.',
                    'If you are looking at a system like this, the first step is not the bridge. It is the tests around what you have, and a tested restore of last night\'s backup.',
                ],
                'nl' => ['title' => 'Strangler Fig in de praktijk: wat twee frameworks in één app me leerden', 'summary' => 'Op een whiteboard is het patroon simpel: route voor route, oud en nieuw naast elkaar. De moeilijke delen zijn de sessie, de database en de mensen. Notities van een migratie die in gebruik bleef.'],
            ],
        ];
    }

    /**
     * @return Post
     */
    public static function get(string $slug): array
    {
        return self::all()[$slug] ?? abort(404);
    }
}
