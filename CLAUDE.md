# CLAUDE.md — splotnikov-dev

## What this is
A Laravel **library package** (not an app) that serves the splotnikov.dev
portfolio + CV inside the `sd-admin` host app via host-based routing.

## Constraints
- The host machine has no PHP/Composer. Run package commands through a
  one-off Docker image (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`)
  or inside the sd-admin `app` container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → bump `spdotdev/splotnikov-dev` in **sd-admin's own `composer.lock`**, commit + push (never a bare `composer update` run directly on the d051 container — that's invisible to the committed lock and gets silently reverted by the next unrelated deploy). See sd-admin's `CLAUDE.md` ("Updating a vcs-tracked spdotdev/* package").

## Layout
- `src/SplotnikovDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('splotnikov-dev.domain'))` group: `/` and `/cv`.
- `resources/views/{portfolio,cv}.blade.php` — ported near-verbatim from the static site (mostly static HTML).
- `config/splotnikov-dev.php` — `domain` via `SPLOTNIKOV_DOMAIN`.
- `public/` — assets published to the host's `public/vendor/splotnikov`.

## Integration / route precedence
sd-admin's landing route must be host-scoped so it does not shadow this
package's domain routes. Verified by sd-admin's `SplotnikovSiteTest`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover.
See `docs/cutover-runbook.md`.
