# CLAUDE.md — splotnikov-dev

## What this is
A Laravel **library package** (not an app) that serves the splotnikov.dev
personal site (home, CV, contact form) inside a host app via host-based routing.
Since v0.2.0 it uses the scuttle.dev theme (same shell, stylesheet and script
shape as `spdotdev/scuttle-dev`) with a teal accent instead of orange.

## Constraints
- The host machine has no PHP/Composer. Run package commands through a
  one-off Docker image (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`)
  or inside the host app's container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → bump `spdotdev/splotnikov-dev` in the host app's own `composer.lock`, commit + push (never run a bare `composer update` directly on the production server — that's invisible to the committed lock and gets silently reverted by the next unrelated deploy).

## Layout
- `src/SplotnikovDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php`: `Route::domain(config('splotnikov-dev.domain'))` group: `/`, `/projects`, `/toolkit`, `/blog`, `/blog/{slug}`, `/cv`, `/contact` (GET and POST), plus `/robots.txt`, `/sitemap.xml` and `/site.webmanifest` at root. `SiteController` renders the views; `ContactController` + `Mail/ContactMessage` handle the form (throttle 6 a minute, honeypot plus a signed 3-second minimum, Cloudflare Turnstile when `SPLOTNIKOV_TURNSTILE_*` are set, mail to `SPLOTNIKOV_CONTACT_TO`, default stplotnikov@gmail.com).
- Data classes, each with the Dutch text next to the English: `src/Projects.php` (the public GitHub repos, personal side; adding one: here and in `public/sitemap.xml` is not needed, the cards link to GitHub), `src/Cases.php` (two anonymized client cases, bottom of the projects page), `src/Toolkit.php` (the same seven groups as the CV expertise: languages, frameworks, automation, AI, infrastructure, networking, security; `home()` is `HOME`), `src/Posts.php` (the personal blog: technology, what I am busy with, first person, no client names), `src/Cv.php` (the CV content and `nl()`, the Dutch map for the CV keys). The personal site never shows the KvK or VAT number; business goes to scuttle.dev with a link.
- Articles from sd-cms: `Posts::all()` returns the published `cms_articles` rows for site `splotnikov` (written over the sd-cms MCP server, reviewed in the host's Filament panel) ahead of the built-in posts in `Posts::builtIn()`; a row with the same slug replaces the built-in one. sd-cms is an optional sibling in the host, never a dependency here: `Posts::fromCms()` checks the class and the table and returns nothing in the package tests. `Posts::fromArticle()` maps a row to the Post shape (one body entry per non-empty line). The sitemap route appends those articles to `public/sitemap.xml`.
- `resources/views/layout.blade.php` — shared shell: nav Home, Manifesto (home anchor), Projects, Toolkit, Blog, CV (`title="Curriculum vitae"`), Contact; EN/NL switch and the three-way theme control on every page; "Contact me" button; dark footer. `partials/logo.blade.php` is the mark (a prompt in a rounded square), `partials/toolgroup.blade.php` one toolkit card.
- `portfolio.blade.php` — the home page: hero with the portrait and the name as a block (ink on light, light on dark; above 980 px the 96 px portrait sits left of the name in one row and the hero has 72/48 px of air, so the terminal panel starts at 571 px and shows output in the first screen of a 1366x768 laptop; below that the portrait is above the name), decrypt headline, `whoami` line (PHP since 2008, Linux and networks since 2005: from the persona timeline, MBO-4 at Radius College 2006 to 2009; its items are `<span>`s inside the `<b>`, joined with a middle dot by CSS on the desktop and one item per line under 600 px, the Dutch string carries the same spans), the terminal panel, Manifesto (three cards), Projects (a sideways scroller of every project, "All projects" button right of the header; under 600 px the same cards render as a vertical list of compact rows, name plus kind and stack with an arrow, descriptions and chips hidden, the button under the list and "Scroll sideways" hidden; the `.pj` grid places header, scroller and button), Toolkit (three cards, "See the whole toolkit" button), the Worked-with belts, the closing CTA. `projects.blade.php`, `toolkit.blade.php`, `blog.blade.php`, `post.blade.php`, `cv.blade.php` (two-column card, Print and Download PDF through html2pdf from cdnjs with SRI, rendered from a fixed box so the page is never cut off; `.cv.pdf` and `@media print` force a light A4 layout), `contact.blade.php` (form and the two cards side by side in `.contact-grid`; the About card links LinkedIn and GitHub only).
- The terminal panel: the sidebar buttons in `portfolio.blade.php` (`data-k`) select an entry of the `D` object in `site.js`. Every scene is `kind: 'run'`: typed lines `[column, text, class]` where the column is `$` (a prompt), a number (a code line, indentation kept), a time, or empty; then result rows linking to a page or section. Titles are marketing lines with the tool after the dot. Scenes: php artisan (migrate, PHPUnit, Playwright), npm run build, ansible-playbook, git rebase and push, nmap on his own lab, zsh history, router.ts, Bridge.php, claude -p. Re-enactments with made-up hosts; nothing runs. The test checks every `data-k` has a scene. Scrollbars inside the panel are hidden. Under 600 px the output uses a 12 px font, a 10 px indent where the time column is empty and lines that wrap, so nothing runs past the panel; under 980 px the scene list is a chip row with the grid rows `auto minmax(0,1fr)` so the chips keep their height before the terminal starts. The top menu: links keep one 22 px gap and re-centre on a language switch (no per-link width reservation since v0.3.14); the Dutch menu says Tools for Toolkit.
- Translation: every page carries `data-i` keys and pushes its Dutch map (`Object.assign(window.SCUTTLE_NL.t, ...)`, the name kept so `site.js` stays interchangeable with scuttle-dev's); the shell keys live in the layout. `test_every_page_is_translatable` checks every page.
- `public/site.css` + `public/site.js` — the only stylesheet and script, copied from scuttle-dev v0.5.1 and adapted (accent, mark, portrait, terminal columns, CV, print, contact grid, scroller, appended at the end). Accent token `--acc`: teal `#0d9488` light / `#2dd4bf` dark, `--acc2` `#2dd4bf` / `#5eead4`. Regenerate every logo asset in a new colour with `design/make-logo.sh '<accent>' '<accent light>'` (needs rsvg-convert and ImageMagick). `public/portrait.jpg` is the same photo as scuttle.dev's contact page. `public/og-image.png` is rendered from `design/og-image.html` by `python3 design/make-og.py` (Playwright); rerun it when the portrait or the headline changes.
- `tests/Feature/PreviewDumpTest.php` writes every page as static HTML when `SPLOTNIKOV_DUMP=<dir>` is set (skipped otherwise), so the theme can be screenshotted without a PHP host (Playwright on the laptop).
- `config/splotnikov-dev.php` — `domain`, `booking_url`, `contact_to`, Turnstile keys, all via `SPLOTNIKOV_*` env.
- `public/` — assets published to the host's `public/vendor/splotnikov`.
- `.env.example` — documents env vars this package reads; all have safe defaults, so it's optional, not required for install. Copy relevant lines into the host app's own `.env`.

## Writing for this site versus scuttle.dev (Stas, 2026-10-29)
splotnikov.dev is the personal side: technology, what I am busy with, why one thing beats another, my own experience; open source projects. scuttle.dev is the enterprise side: news that sells a service (security quick scan, maintenance retainer, architecture review, modernization), why choose me, what customers get. Articles from the CRM outreach end up here and on LinkedIn.

## Integration / route precedence
The host app's landing route must be host-scoped so it does not shadow this
package's domain routes. Verified by the host app's `SplotnikovSiteTest`.

## Cutover (done)
DigitalOcean provisioning, live deploy, and the DNS A-record cutover are done:
splotnikov.dev is served by the host app from this package (checked 2026-09-27,
`/` and `/cv` return 200 with `vendor/splotnikov/` assets).
`docs/cutover-runbook.md` is the record of how it was done.

## Deploy (set 2026-10-13, HARD RULE, user decision)
Production on d051 always runs the latest `main` of this project. When the user asks for a change and it is merged to main, deploy it yourself right away, without asking: push or merge, watch CI and the deploy to the end, and check the server runs that commit. If this project uses tags, deploy the latest tag every time. Anything not in main is local testing and is never deployed. A failing deploy is reported first as broken, with what it blocks. Never open PRs unless asked.
