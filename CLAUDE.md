# CLAUDE.md — splotnikov-dev

## What this is
A Laravel **library package** (not an app) that serves the splotnikov.dev
portfolio + CV inside a host app via host-based routing.

## Constraints
- The host machine has no PHP/Composer. Run package commands through a
  one-off Docker image (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`)
  or inside the host app's container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → bump `spdotdev/splotnikov-dev` in the host app's own `composer.lock`, commit + push (never run a bare `composer update` directly on the production server — that's invisible to the committed lock and gets silently reverted by the next unrelated deploy).

## Layout
- `src/SplotnikovDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('splotnikov-dev.domain'))` group: `/` and `/cv`.
- `resources/views/{portfolio,cv}.blade.php` — ported near-verbatim from the static site (mostly static HTML).
- `config/splotnikov-dev.php` — `domain` via `SPLOTNIKOV_DOMAIN`.
- `public/` — assets published to the host's `public/vendor/splotnikov`.
- `.env.example` — documents env vars this package reads (currently just `SPLOTNIKOV_DOMAIN`); all have safe defaults, so it's optional, not required for install. Copy relevant lines into the host app's own `.env`.

## Integration / route precedence
The host app's landing route must be host-scoped so it does not shadow this
package's domain routes. Verified by the host app's `SplotnikovSiteTest`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover.
See `docs/cutover-runbook.md`.
