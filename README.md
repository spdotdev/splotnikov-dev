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

Set the host it answers on (defaults to `splotnikov.dev`):

```dotenv
SPLOTNIKOV_DOMAIN=splotnikov.dev
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
