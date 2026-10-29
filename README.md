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
configured domain: `/` (home), `/cv`, `/contact` (GET and POST), `/robots.txt`, `/sitemap.xml` and
`/site.webmanifest`. The assets tag copies `public/` to the host's
`public/vendor/splotnikov`.

The host app's own landing route must be host-scoped, or it will shadow this
package's `/` route.

## Configuration

This package is a library, not a standalone app, so it ships no `.env` of
its own — it reads config from whatever app installs it. See
[`.env.example`](.env.example) for the full list of variables it supports
and their defaults; copy the ones you want to override into the **host
application's** `.env`.

| Variable | Default | Purpose |
|---|---|---|
| `SPLOTNIKOV_DOMAIN` | `splotnikov.dev` | Host this package's routes answer on (`Route::domain(...)`). |
| `SPLOTNIKOV_BOOKING_URL` | `https://plan.scuttle.dev/book/stas` | The booking page behind "Or book a call" on the contact page. |
| `SPLOTNIKOV_CONTACT_TO` | `stplotnikov@gmail.com` | Where the contact form delivers (sent by the host app's mailer). |
| `SPLOTNIKOV_TURNSTILE_SITE_KEY`, `SPLOTNIKOV_TURNSTILE_SECRET` | empty | Cloudflare Turnstile on the contact form; empty means honeypot plus a signed 3-second minimum only. |

Every variable has a safe default baked into `config/splotnikov-dev.php`,
so the package works out of the box even if none of these are set —
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

CI runs the same three checks on PHP 8.3 for pushes to `main` and pull requests.
`composer audit` runs in the security workflow (same triggers plus weekly).

## Local development override (optional, faster loop)

To edit this package live from a sibling checkout instead of re-tagging,
mount it into the host app's container and point Composer at a path
repository (`{ "type": "path", "url": "../splotnikov-dev", "options": { "symlink": true } }`).
Keep this out of committed config — it is a dev-only convenience.
