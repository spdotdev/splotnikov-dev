# Design: `spdotdev/splotnikov-dev` — versioned Laravel package mounted into the host app

**Date:** 2026-06-22
**Status:** Approved (brainstorming) — pending implementation plan
**Author:** S Plotnikov (with Claude)

## Summary

Turn the splotnikov.dev portfolio into a **versioned Laravel package** that is installed
into the central `the host app` Laravel application via Composer. `the host app` is the single
app deployed to the DigitalOcean server (the server); it `composer require`s this package and
serves the splotnikov.dev site through **host-based routing**. The site is upgraded by
bumping a git tag and running `composer update`.

The existing static site (`spdotdev/splotnikov`, a Vite build on GitHub Pages serving
`splotnikov.dev`) stays **completely untouched and live** until the DNS A-record is later
pointed at the DO server. No live deploy or DNS change happens in the implementation of
this spec.

## Goals

- A new **public** GitHub repo `spdotdev/splotnikov-dev` containing a Laravel **package**
  (`type: library`), not a standalone app.
- **Content parity** with the current static site: portfolio landing (`/`) and CV (`/cv`).
- Installed into `the host app` via a **VCS (GitHub) Composer repository + git tag**, no Packagist.
- **Versioned**: upgrading the site = bump tag (`vX.Y.Z`) → `composer update` in the host app.
- The package **extends the host app's routing** so requests for the `splotnikov.dev` host
  render the package's templates.
- This session also **wires the package into the host app and verifies** locally (faking the
  Host header) that the domain route renders the portfolio.

## Non-goals (explicitly deferred)

- Provisioning the DigitalOcean server.
- Live deployment of the host app and the package.
- The real DNS A-record cutover from GitHub Pages to the DO droplet.
- Publishing to Packagist (using GitHub VCS + tags only — this is private to the owner).
- Extracting shared `spdotdev` packages (auth/theme/components) out of the host app. The
  package is standalone; sharing is a later, separate effort.
- Any change to the existing static `spdotdev/splotnikov` repo or its GitHub Pages deploy.

## Context (current state)

- **Static repo `spdotdev/splotnikov`** (public): Vite build, two self-contained HTML files
  (`index.html` portfolio, `cv.html` CV) with **inline `<style>`** and inline behavior
  (`src/main.js` is empty). Assets in `public/` (`profile.png`, `robots.txt`, `sitemap.xml`,
  `site.webmanifest`, plus Pages-only `CNAME` + `.nojekyll`). Auto-deploys to GitHub Pages
  on push to `main`; DNS points `splotnikov.dev` at GitHub Pages.
- **`the host app` (`<private host-app repo>`, local at `/home/dev/<host-app>`)**: Laravel 13 + Filament
  v5, **Docker-first** (host has no PHP/Composer; everything runs in the `app` service —
  `make art`, `make composer`, `make sh`). Laravel 12-style bootstrap:
  `bootstrap/app.php` `withRouting(web: routes/web.php)`, providers in `bootstrap/providers.php`.
  `routes/web.php` currently has a single **unconstrained** `Route::get('/', [LandingController::class, 'index'])`.
  CI in `.github/workflows/ci.yml` (Pint + Larastan level 5 + tests on SQLite); inert
  `deploy.yml.example`; pre-commit hooks via `.githooks` (Pint + Larastan). Filament admin at
  `/admin`, fail-closed.

## Architecture

### The package (`spdotdev/splotnikov-dev`)

A Laravel package consumed by a host Laravel app. No app skeleton, no Docker of its own —
it is exercised through the host app.

```
splotnikov-dev/
├── composer.json              # type: library, PSR-4 Spdotdev\SplotnikovDev\, auto-discovery
├── config/
│   └── splotnikov-dev.php      # 'domain' => env('SPLOTNIKOV_DOMAIN', 'splotnikov.dev')
├── routes/
│   └── web.php                 # Route::domain(config(...))->group: GET / , GET /cv
├── resources/
│   └── views/
│       ├── portfolio.blade.php # ported from index.html (near-verbatim)
│       └── cv.blade.php        # ported from cv.html (near-verbatim, keeps html2pdf CDN)
├── public/                     # static assets published into the host
│   ├── profile.png
│   ├── robots.txt
│   ├── sitemap.xml
│   └── site.webmanifest
├── src/
│   ├── SplotnikovDevServiceProvider.php
│   └── Http/Controllers/SiteController.php   # index() + cv()
├── .github/workflows/ci.yml    # Pint + Larastan against the package
├── CLAUDE.md
├── README.md                   # install + version-bump + path-repo dev instructions
└── .gitignore
```

**`composer.json`** key fields:

```json
{
  "name": "spdotdev/splotnikov-dev",
  "type": "library",
  "license": "proprietary",
  "require": { "php": "^8.3", "illuminate/support": "^12.0 || ^13.0" },
  "require-dev": { "laravel/pint": "^1.27", "larastan/larastan": "^3.0" },
  "autoload": { "psr-4": { "Spdotdev\\SplotnikovDev\\": "src/" } },
  "extra": {
    "laravel": {
      "providers": ["Spdotdev\\SplotnikovDev\\SplotnikovDevServiceProvider"]
    }
  }
}
```

> Note: `illuminate/support` constraint must match the Laravel major used by the host app
> (Laravel 13). The implementation plan verifies the exact installed major and pins
> accordingly.

**`SplotnikovDevServiceProvider`** responsibilities:
- `register()`: merge package config (`splotnikov-dev.php`).
- `boot()`:
  - `loadRoutesFrom(__DIR__.'/../routes/web.php')`
  - `loadViewsFrom(__DIR__.'/../resources/views', 'splotnikov')`
  - publish config (tag `splotnikov-dev-config`) and public assets (tag `splotnikov-dev-assets`).

**`routes/web.php`**:
```php
Route::domain(config('splotnikov-dev.domain'))->group(function () {
    Route::get('/', [SiteController::class, 'index']);
    Route::get('/cv', [SiteController::class, 'cv']);
});
```

**Views**: copied near-verbatim from `index.html` / `cv.html`. Inline `<style>` preserved.
Any Blade-significant tokens (`@`, `{{`, `}}`) escaped with `@@` / `@verbatim`. Asset URLs
rewritten to the published vendor path (e.g. `asset('vendor/splotnikov/profile.png')`).
Pages-only files (`CNAME`, `.nojekyll`) are **not** carried over.

### Content parity

`/` renders the portfolio; `/cv` renders the CV (with its html2pdf export intact). A package
feature/integration check asserts both routes return 200 and contain a known marker string
("Stanislav Plotnikov").

## Route precedence (primary integration risk)

Laravel returns the **first registered route** whose URI matches and whose domain constraint
(if present) matches. the host app's existing **unconstrained** `GET /` matches *any* host, so if
it is registered before the package's `Route::domain('splotnikov.dev') GET /`, it will
**shadow** the package on the splotnikov.dev host.

**Resolution (in priority order, decided during implementation by what the local test shows):**
1. Preferred: **domain-scope the host app's own landing route** so it no longer greedily matches
   the splotnikov.dev host (e.g. restrict it to the admin/default host, or move it behind a
   host condition). This keeps the package self-contained.
2. Alternative: ensure the package's domain routes are registered **before** the host app's
   `web.php` (boot ordering / explicit route priority).

The local verification step (below) is what confirms which is needed; the design does not
assume it "just works."

## Distribution & versioning

- Repo: `spdotdev/splotnikov-dev` (public), git tag `v0.1.0` for the first release.
- In **the host app `composer.json`**:
  ```json
  "repositories": [
    { "type": "vcs", "url": "https://github.com/spdotdev/splotnikov-dev" }
  ],
  "require": { "spdotdev/splotnikov-dev": "^0.1" }
  ```
- **Upgrade flow**: tag a new version in the package repo → `make composer cmd="update spdotdev/splotnikov-dev"` in the host app.
- **Local development override** (documented in the package README): a Composer **path
  repository** pointing at the sibling checkout with `"options": { "symlink": true }`, so
  edits to the local package are live in the host app without re-tagging. Removed/ignored for
  production installs.

## Deployment & DNS cutover (documented, executed later)

A `docs/cutover-runbook.md` in the package repo captures the eventual go-live:
1. Provision the server (DigitalOcean).
2. Deploy the host app (with `spdotdev/splotnikov-dev` required) to the server.
3. Set `SPLOTNIKOV_DOMAIN=splotnikov.dev` and real secrets in the **server `.env`** (never git).
4. Smoke-test the site against the **droplet IP** (Host header / hosts file).
5. Switch the `splotnikov.dev` **DNS A record** from GitHub Pages IPs to the droplet IP; verify TLS.
6. Archive the static `spdotdev/splotnikov` repo.

the host app's existing inert `deploy.yml.example` (build → ship over SSH/rsync → migrate →
restart php-fpm/queue) remains the deploy mechanism and is out of scope here.

## This session's deliverable (implementation scope)

1. Scaffold the package in a local sibling dir `~/splotnikov-dev`.
2. Port `index.html` → `portfolio.blade.php`, `cv.html` → `cv.blade.php`; move assets.
3. Implement provider, routes, config, controller; add package `ci.yml`, `CLAUDE.md`,
   `README.md`, `.gitignore`, `cutover-runbook.md`.
4. Create the public GitHub repo, push, tag `v0.1.0`.
5. In the host app: add the VCS repository + `require`, set `SPLOTNIKOV_DOMAIN=localhost` for dev,
   `make up`, `composer update` in-container.
6. **Verify locally**: request the app with the configured host and confirm `/` and `/cv`
   render the portfolio/CV (resolving route precedence if the landing route shadows it).
7. Commit the host app changes.

## Testing

- **Package**: `Pint --test` + `Larastan` green in the package's own CI. A minimal package
  test (using `orchestra/testbench` or a thin harness) asserts the provider registers the
  routes/views and that `/` + `/cv` return 200 with the marker string.
- **Integration (local)**: in the host app (Docker), with the package required and
  `SPLOTNIKOV_DOMAIN=localhost`, a request to `/` (Host: localhost) renders the portfolio and
  `/cv` renders the CV; the host app's default landing still serves other hosts.
- **House checks**: the host app's existing CI (Pint + Larastan + tests) stays green after wiring.

## Open questions / assumptions

- `illuminate/support` major is pinned to whatever the host app's Laravel resolves to (verified
  during implementation).
- First tag is `v0.1.0` (pre-1.0, signalling the site is still evolving); bump to `v1.0.0`
  at the DNS cutover if desired.
- `testbench` is acceptable as a dev-only dependency for package tests; if undesired, fall
  back to asserting integration purely through the host app.
