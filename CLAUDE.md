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
- `routes/web.php`: `Route::domain(config('splotnikov-dev.domain'))` group: `/`, `/cv`, `/contact` (GET and POST), plus `/robots.txt`, `/sitemap.xml` and `/site.webmanifest` at root.
- `src/Http/Controllers/SiteController.php`: renders `splotnikov::portfolio`, `splotnikov::cv`, `splotnikov::contact` and serves the three root files straight from the package's `public/`.
- `src/Http/Controllers/ContactController.php` + `src/Mail/ContactMessage.php`: the contact form (throttle 6 a minute, honeypot plus a signed 3-second minimum, Cloudflare Turnstile when `SPLOTNIKOV_TURNSTILE_SITE_KEY` and `SPLOTNIKOV_TURNSTILE_SECRET` are set in the host's .env, mail to `SPLOTNIKOV_CONTACT_TO`, default stplotnikov@gmail.com).
- `resources/views/layout.blade.php` — shared shell: nav (Home, Manifesto, Proof, Toolkit, CV, Contact), EN/NL switch on the home page only, three-way theme control System / Light / Dark, "Contact me" button, dark footer. Dutch strings for the shell live in `window.SCUTTLE_NL` there (the name is kept so `site.js` stays interchangeable with scuttle-dev's); the home page pushes its own into the `nl` stack. `partials/logo.blade.php` is the mark: a prompt (chevron and underscore) in a rounded square, inline SVG with the same `r1/r2/r3` fade-in classes as scuttle's.
- `resources/views/portfolio.blade.php` — the home page: hero with the portrait, decrypt headline, `whoami` command line; the interactive terminal panel (`#demo`); Manifesto, Proof (two case cards), Toolkit, the Worked-with belts, the closing CTA. `cv.blade.php` — the CV in a two-column card, Print (`window.print()`) and Download PDF (html2pdf from cdnjs, pinned with SRI; the `.cv.pdf` class and the `@media print` block force a light, chrome-free A4 layout with the name inside the document). `contact.blade.php` — the form first, then two cards (reach me, about) and the photo modal.
- The terminal panel: the sidebar buttons in `portfolio.blade.php` (`data-k`) select an entry of the `D` object in `site.js`. Every scene is `kind: 'run'`: typed lines `[column, text, class]` where the column is `$` (a prompt), a number (a code line), a time (a log line) or empty, then result rows that link to a section or an outside page. Scenes are re-enactments from Stas's own lab and projects with made-up hosts; security scenes are summaries (an inventory of his own lab network, a hardening check), never operational transcripts. The test checks that every `data-k` has a scene.
- `public/site.css` + `public/site.js` — the only stylesheet and script, copied from scuttle-dev v0.5.1 and adapted (accent, mark, portrait, terminal columns, CV and print styles appended at the end). Accent token `--acc`: teal `#0d9488` light / `#2dd4bf` dark, `--acc2` `#2dd4bf` / `#5eead4` (the greens the old site used). Regenerate every logo asset in a new colour with `design/make-logo.sh '<accent>' '<accent light>'` (needs rsvg-convert and ImageMagick). `public/portrait.jpg` is the same photo as scuttle.dev's contact page. `public/og-image.png` is still to be made (see GitHub issues).
- `tests/Feature/PreviewDumpTest.php` writes every page as static HTML when `SPLOTNIKOV_DUMP=<dir>` is set (skipped otherwise), so the theme can be screenshotted without a PHP host (Playwright on the laptop).
- `config/splotnikov-dev.php` — `domain`, `booking_url`, `contact_to`, Turnstile keys, all via `SPLOTNIKOV_*` env.
- `public/` — assets published to the host's `public/vendor/splotnikov`.
- `.env.example` — documents env vars this package reads; all have safe defaults, so it's optional, not required for install. Copy relevant lines into the host app's own `.env`.

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
