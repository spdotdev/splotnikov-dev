# splotnikov-dev

The splotnikov.dev portfolio + CV, packaged as a Laravel library for
inclusion in a host Laravel application. Host-based routing serves the site
on the configured domain alongside the host app's own routes.

## Install

Add the VCS repository and require a tagged version:

```jsonc
// composer.json
"repositories": [
    { "type": "vcs", "url": "https://github.com/spdotdev/splotnikov-dev" }
],
"require": {
    "spdotdev/splotnikov-dev": "^0.1"
}
```

```bash
composer update spdotdev/splotnikov-dev
php artisan vendor:publish --tag=splotnikov-dev-assets
```

## Configuration

This package is a library, not a standalone app, so it ships no `.env` of
its own — it reads config from whatever app installs it. See
[`.env.example`](.env.example) for the full list of variables it supports
and their defaults; copy the ones you want to override into the **host
application's** `.env`.

| Variable | Default | Purpose |
|---|---|---|
| `SPLOTNIKOV_DOMAIN` | `splotnikov.dev` | Host this package's routes answer on (`Route::domain(...)`). |

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
```

## Local development override (optional, faster loop)

To edit this package live from a sibling checkout instead of re-tagging,
mount it into the host app's container and point Composer at a path
repository (`{ "type": "path", "url": "../splotnikov-dev", "options": { "symlink": true } }`).
Keep this out of committed config — it is a dev-only convenience.
