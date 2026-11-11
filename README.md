# splotnikov-dev

The splotnikov.dev personal site (home with an interactive terminal panel, a printable CV and a contact form), packaged as a Laravel library for
inclusion in a host Laravel application. Same theme as scuttle.dev, teal accent. Host-based routing serves the site
on the configured domain alongside the host app's own routes.

## Requirements

- PHP 8.3+
- A host app on Laravel 13 (`illuminate/support ^13.0`)

## Install

Add the VCS repository and require a tagged version:

```jsonc
// composer.json
"repositories": [
    { "type": "vcs", "url": "https://github.com/spdotdev/splotnikov-dev" }
],
"require": {
    "spdotdev/splotnikov-dev": "^0.2"
}
```

```bash
composer update spdotdev/splotnikov-dev
php artisan vendor:publish --tag=splotnikov-dev-assets
```

The service provider is auto-discovered. It registers these routes on the
configured domain: `/` (home), `/cv`, `/projects`, `/toolkit`, `/blog` with its posts and `/feed.xml`, `/privacy`,
`/contact` (GET and POST), the Dutch twins under `/nl`, `/robots.txt`, `/sitemap.xml` and `/site.webmanifest`. The
contact form stores every valid message in the `splotnikov_contact_leads` table (migration included, run it in the
host) before it tries to mail it; `php artisan splotnikov:contact-leads` lists them. The assets tag copies `public/` to the host's
`public/vendor/splotnikov`.

The host app's own landing route must be host-scoped, or it will shadow this
package's `/` route.

## Configuration

This package is a library, not a standalone app, so it ships no `.env` of
its own, it reads config from whatever app installs it. See
[`.env.example`](.env.example) for the full list of variables it supports
and their defaults; copy the ones you want to override into the **host
application's** `.env`.

| Variable | Default | Purpose |
|---|---|---|
| `SPLOTNIKOV_DOMAIN` | `splotnikov.dev` | Host this package's routes answer on (`Route::domain(...)`). |
| `SPLOTNIKOV_BOOKING_URL` | `https://plan.scuttle.dev/book/stas` | The booking page behind "Or book a call" on the contact page. |
| `SPLOTNIKOV_GEOIP_DB` | empty | Path to the free DB-IP Lite country file (`dbip-country-lite-YYYY-MM.mmdb`, CC BY 4.0, no account). Empty: the Dutch offer uses the browser language only. |
| `SPLOTNIKOV_CONTACT_TO` | `stplotnikov@gmail.com` | Where the contact form delivers (sent by the host app's mailer). |
| `SPLOTNIKOV_TURNSTILE_SITE_KEY` | empty | Cloudflare Turnstile site key on the contact form; empty means honeypot plus a signed 3-second minimum only. |
| `SPLOTNIKOV_TURNSTILE_SECRET` | empty | Cloudflare Turnstile secret, used server side to check the answer. |
| `SPLOTNIKOV_REQUIRE_REAL_MAILER` | `true` | The form refuses to say "Sent" when the host's default mailer is `log` or `array`. Set `false` only in tests. |
| `SPLOTNIKOV_CONTACT_MAIL_DSN` | empty | A Symfony mailer DSN for the form's own mailer. Empty means the host's default mailer. Keep it in the host's `.env`. |
| `SPLOTNIKOV_CONTACT_MAIL_FROM` | empty | The address the form's mail is sent from. |
| `SPLOTNIKOV_ANALYTICS_KEY` | empty | Site key in sd-analytics (cookieless visit counting). Empty means nothing is counted. |

Every variable has a safe default baked into `config/splotnikov-dev.php`,
so the package works out of the box even if none of these are set.
`.env.example` only documents the overrides available to you. If you want
the config file itself editable in the host app, publish it:

```bash
php artisan vendor:publish --tag=splotnikov-dev-config
```

## Upgrading

Bump the git tag here (`vX.Y.Z`), then in the host application:

```bash
composer update spdotdev/splotnikov-dev
php artisan vendor:publish --tag=splotnikov-dev-assets --force
```

Commit the host app's updated `composer.lock`. `--force` is needed so changed
assets overwrite the previously published copies.

## Development

```bash
composer install
composer test                 # phpunit
./vendor/bin/pint --test      # code style
./vendor/bin/phpstan analyse  # static analysis
```

The live checks run against a deployed site and use only the Python standard library or Playwright:

```bash
python3 scripts/check-live.py https://splotnikov.dev   # sitemap, hreflang, internal and outbound links
AXE_JS=package/axe.min.js python3 scripts/axe-check.py <base-url> paths.txt   # axe-core, light and dark (needs Playwright; paths.txt: one path per line)
python3 scripts/overflow-check.py <base-url> paths.txt                        # pages that scroll sideways at 360 px
```

`SPLOTNIKOV_DUMP=<dir> vendor/bin/phpunit tests/Feature/PreviewDumpTest.php` writes every page as static HTML for those
scripts. What changed per release is in [CHANGELOG.md](CHANGELOG.md). Security reports: see [SECURITY.md](SECURITY.md).

CI runs the same three checks on PHP 8.3 for pushes to `main` and pull requests.
`composer audit` runs in the security workflow (same triggers plus weekly).

## Local development override (optional, faster loop)

To edit this package live from a sibling checkout instead of re-tagging,
mount it into the host app's container and point Composer at a path
repository (`{ "type": "path", "url": "../splotnikov-dev", "options": { "symlink": true } }`).
Keep this out of committed config, it is a dev-only convenience.
