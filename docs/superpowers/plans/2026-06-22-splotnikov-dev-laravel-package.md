# splotnikov-dev Laravel Package Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build `spdotdev/splotnikov-dev`, a versioned Laravel package that serves the splotnikov.dev portfolio + CV, and wire it into the `the host app` host app via host-based routing.

**Architecture:** A Composer `library` package (PSR-4 `Spdotdev\SplotnikovDev\`) whose auto-discovered service provider registers a `Route::domain(...)` group rendering two ported HTML pages. the host app installs it via a GitHub VCS Composer repository pinned to a git tag; the splotnikov.dev host resolves to the package's routes while the host app's own landing stays on its host.

**Tech Stack:** PHP 8.3+, Laravel 13 (`illuminate/support`), Composer (VCS repo + git tags), Docker (the host app's `app` container; one-off `composer:2` image for package-only commands), Pint, Larastan.

**Spec:** `~/splotnikov-dev/docs/superpowers/specs/2026-06-22-splotnikov-dev-laravel-package-design.md`

## Global Constraints

- The package is **`spdotdev/splotnikov-dev`**, `type: library`, namespace **`Spdotdev\SplotnikovDev\`**, PSR-4 root `src/`.
- PHP floor **`^8.3`**; framework constraint **`illuminate/support: ^13.0`** (must match the host app's resolved Laravel major — verify in Task 6, repin if different).
- Distribution is **GitHub VCS + git tags only — no Packagist**. First tag **`v0.1.0`**.
- The new repo is **public**, owner `spdotdev`.
- **Host has no PHP/Composer.** Never run `php`/`composer`/`artisan` on the host. Use either the host app's container (`make composer cmd="..."`, `make art cmd="..."`, `docker compose exec app ...` from `/home/dev/<host-app>`) or a one-off image: `docker run --rm -v /home/dev/splotnikov-dev:/app -w /app composer:2 <cmd>`.
- The existing static repo **`/home/dev/splotnikov` is never modified or committed to** by this plan.
- Route/view marker string for assertions: **`Stanislav Plotnikov`**.
- Domain is config-driven: `config('splotnikov-dev.domain')` ← `env('SPLOTNIKOV_DOMAIN', 'splotnikov.dev')`.
- Package working directory: **`/home/dev/splotnikov-dev`** (already exists; contains only `docs/`).

---

### Task 1: Package skeleton + composer.json

**Files:**
- Create: `/home/dev/splotnikov-dev/composer.json`
- Create: `/home/dev/splotnikov-dev/.gitignore`
- Create: `/home/dev/splotnikov-dev/pint.json`
- Create: `/home/dev/splotnikov-dev/phpstan.neon`

**Interfaces:**
- Produces: package name `spdotdev/splotnikov-dev`, autoload `Spdotdev\SplotnikovDev\` → `src/`, auto-discovered provider `Spdotdev\SplotnikovDev\SplotnikovDevServiceProvider` (class created in Task 3).

- [ ] **Step 1: Write `composer.json`**

```json
{
    "name": "spdotdev/splotnikov-dev",
    "description": "splotnikov.dev portfolio site, packaged for the the host app host app.",
    "type": "library",
    "license": "proprietary",
    "require": {
        "php": "^8.3",
        "illuminate/support": "^13.0"
    },
    "require-dev": {
        "laravel/pint": "^1.27",
        "larastan/larastan": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "Spdotdev\\SplotnikovDev\\": "src/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Spdotdev\\SplotnikovDev\\SplotnikovDevServiceProvider"
            ]
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true,
    "config": {
        "sort-packages": true,
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

- [ ] **Step 2: Write `.gitignore`**

```gitignore
/vendor/
composer.lock
.phpunit.result.cache
.DS_Store
/.idea
/.vscode
```

- [ ] **Step 3: Write `pint.json`**

```json
{ "preset": "laravel" }
```

- [ ] **Step 4: Write `phpstan.neon`**

```neon
includes:
    - vendor/larastan/larastan/extension.neon
parameters:
    level: 5
    paths:
        - src
```

- [ ] **Step 5: Validate the manifest**

Run: `docker run --rm -v /home/dev/splotnikov-dev:/app -w /app composer:2 validate --no-check-publish`
Expected: `./composer.json is valid` (a warning that `composer.lock` is missing is fine — there are no deps installed yet).

- [ ] **Step 6: Initialise git and commit**

```bash
cd /home/dev/splotnikov-dev
git init -q
git add composer.json .gitignore pint.json phpstan.neon docs
git commit -q -m "chore: package skeleton and tooling config"
```

---

### Task 2: Port portfolio + CV content as views and assets

**Files:**
- Create: `/home/dev/splotnikov-dev/resources/views/portfolio.blade.php` (copied from `/home/dev/splotnikov/index.html`)
- Create: `/home/dev/splotnikov-dev/resources/views/cv.blade.php` (copied from `/home/dev/splotnikov/cv.html`)
- Create: `/home/dev/splotnikov-dev/public/profile.png` (copied)
- Create: `/home/dev/splotnikov-dev/public/robots.txt`, `sitemap.xml`, `site.webmanifest` (copied)

**Interfaces:**
- Produces: Blade views `splotnikov::portfolio` and `splotnikov::cv` (view namespace registered in Task 3); both reference the image via `{{ asset('vendor/splotnikov/profile.png') }}`.

- [ ] **Step 1: Copy the two pages into the views directory**

```bash
cd /home/dev/splotnikov-dev
mkdir -p resources/views public
cp /home/dev/splotnikov/index.html resources/views/portfolio.blade.php
cp /home/dev/splotnikov/cv.html    resources/views/cv.blade.php
```

- [ ] **Step 2: Rewrite the local image reference to the published asset path**

```bash
cd /home/dev/splotnikov-dev
sed -i "s|profile\.png|{{ asset('vendor/splotnikov/profile.png') }}|g" resources/views/portfolio.blade.php resources/views/cv.blade.php
```

- [ ] **Step 3: Verify the rewrite and that no `{{ }}` pre-existed to clash**

Run:
```bash
cd /home/dev/splotnikov-dev
grep -c "asset('vendor/splotnikov/profile.png')" resources/views/portfolio.blade.php resources/views/cv.blade.php
grep -c "Stanislav Plotnikov" resources/views/portfolio.blade.php resources/views/cv.blade.php
```
Expected: first command reports `1` for portfolio and `≥1` for cv; second reports `≥1` for each. (If a view shows `0` for the marker, stop — the wrong source file was copied.)

- [ ] **Step 4: Copy the static assets**

```bash
cd /home/dev/splotnikov-dev
cp /home/dev/splotnikov/public/profile.png        public/profile.png
cp /home/dev/splotnikov/public/robots.txt         public/robots.txt
cp /home/dev/splotnikov/public/sitemap.xml        public/sitemap.xml
cp /home/dev/splotnikov/public/site.webmanifest   public/site.webmanifest
```

> Note: `CNAME` and `.nojekyll` are GitHub-Pages-only and are intentionally NOT copied. `robots.txt`/`sitemap.xml` are carried for completeness but serving them at the host root is a cutover concern (see runbook), not wired here.

- [ ] **Step 5: Commit**

```bash
cd /home/dev/splotnikov-dev
git add resources public
git commit -q -m "feat: port portfolio and CV pages and assets"
```

---

### Task 3: Service provider, config, routes, controller

**Files:**
- Create: `/home/dev/splotnikov-dev/config/splotnikov-dev.php`
- Create: `/home/dev/splotnikov-dev/src/Http/Controllers/SiteController.php`
- Create: `/home/dev/splotnikov-dev/routes/web.php`
- Create: `/home/dev/splotnikov-dev/src/SplotnikovDevServiceProvider.php`

**Interfaces:**
- Consumes: views `splotnikov::portfolio`, `splotnikov::cv` (Task 2); config key `splotnikov-dev.domain`.
- Produces: provider boots routes + views + publishable config (tag `splotnikov-dev-config`) and assets (tag `splotnikov-dev-assets`, source `public/` → `public_path('vendor/splotnikov')`). Controller methods `SiteController::index()` and `SiteController::cv()` returning `Illuminate\View\View`.

- [ ] **Step 1: Write the config file**

```php
<?php

return [
    // Host that this site answers on. Override per-environment with
    // SPLOTNIKOV_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SPLOTNIKOV_DOMAIN', 'splotnikov.dev'),
];
```

- [ ] **Step 2: Write the controller**

```php
<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\View\View;

class SiteController
{
    public function index(): View
    {
        return view('splotnikov::portfolio');
    }

    public function cv(): View
    {
        return view('splotnikov::cv');
    }
}
```

- [ ] **Step 3: Write the package routes**

```php
<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\SplotnikovDev\Http\Controllers\SiteController;

Route::domain(config('splotnikov-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('splotnikov.home');
        Route::get('/cv', [SiteController::class, 'cv'])->name('splotnikov.cv');
    });
```

- [ ] **Step 4: Write the service provider**

```php
<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Support\ServiceProvider;

class SplotnikovDevServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/splotnikov-dev.php', 'splotnikov-dev');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'splotnikov');

        $this->publishes([
            __DIR__.'/../config/splotnikov-dev.php' => config_path('splotnikov-dev.php'),
        ], 'splotnikov-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/splotnikov'),
        ], 'splotnikov-dev-assets');
    }
}
```

- [ ] **Step 5: Lint all PHP for syntax errors**

Run: `docker run --rm -v /home/dev/splotnikov-dev:/app -w /app composer:2 sh -c "find src config routes -name '*.php' -print0 | xargs -0 -n1 php -l"`
Expected: `No syntax errors detected` for every file.

- [ ] **Step 6: Commit**

```bash
cd /home/dev/splotnikov-dev
git add src config routes
git commit -q -m "feat: service provider, config, domain routes, controller"
```

---

### Task 4: Package CI, README, CLAUDE.md, cutover runbook

**Files:**
- Create: `/home/dev/splotnikov-dev/.github/workflows/ci.yml`
- Create: `/home/dev/splotnikov-dev/README.md`
- Create: `/home/dev/splotnikov-dev/CLAUDE.md`
- Create: `/home/dev/splotnikov-dev/docs/cutover-runbook.md`

**Interfaces:**
- Consumes: `pint.json`, `phpstan.neon` (Task 1).
- Produces: CI gate (Pint + Larastan) on push/PR; human docs for install, version bumps, and DNS cutover.

- [ ] **Step 1: Write `.github/workflows/ci.yml`**

```yaml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:

jobs:
  quality:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Pint (style check)
        run: ./vendor/bin/pint --test

      - name: Larastan (static analysis)
        run: ./vendor/bin/phpstan analyse --no-progress --memory-limit=512M
```

- [ ] **Step 2: Write `README.md`**

````markdown
# splotnikov-dev

The splotnikov.dev portfolio + CV, packaged as a Laravel library for the
`the host app` host application. Host-based routing serves the site on the
configured domain; the host app is the single deployed app.

## Install (in the host app)

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
make composer cmd="update spdotdev/splotnikov-dev"
make art cmd="vendor:publish --tag=splotnikov-dev-assets"
```

Set the host it answers on (defaults to `splotnikov.dev`):

```dotenv
SPLOTNIKOV_DOMAIN=splotnikov.dev
```

## Upgrading

Bump the git tag here (`vX.Y.Z`), then in the host app:

```bash
make composer cmd="update spdotdev/splotnikov-dev"
```

## Local development override (optional, faster loop)

To edit this package live from a sibling checkout instead of re-tagging,
mount it into the the host app container and point Composer at a path
repository (`{ "type": "path", "url": "../splotnikov-dev", "options": { "symlink": true } }`).
Keep this out of committed config — it is a dev-only convenience.
````

- [ ] **Step 3: Write `CLAUDE.md`**

```markdown
# CLAUDE.md — splotnikov-dev

## What this is
A Laravel **library package** (not an app) that serves the splotnikov.dev
portfolio + CV inside the `the host app` host app via host-based routing.

## Constraints
- The host machine has no PHP/Composer. Run package commands through a
  one-off Docker image (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`)
  or inside the the host app `app` container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → `composer update` in the host app.

## Layout
- `src/SplotnikovDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('splotnikov-dev.domain'))` group: `/` and `/cv`.
- `resources/views/{portfolio,cv}.blade.php` — ported near-verbatim from the static site (mostly static HTML).
- `config/splotnikov-dev.php` — `domain` via `SPLOTNIKOV_DOMAIN`.
- `public/` — assets published to the host's `public/vendor/splotnikov`.

## Integration / route precedence
the host app's landing route must be host-scoped so it does not shadow this
package's domain routes. Verified by the host app's `SplotnikovSiteTest`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover.
See `docs/cutover-runbook.md`.
```

- [ ] **Step 4: Write `docs/cutover-runbook.md`**

```markdown
# splotnikov.dev cutover runbook

Go-live: move splotnikov.dev from GitHub Pages (static repo) to the the host app
app on the DigitalOcean server. Nothing here is automated yet.

1. Provision the server (DigitalOcean).
2. Deploy the host app (with `spdotdev/splotnikov-dev` required) to the server.
3. In the **server** `.env` (never git): set `SPLOTNIKOV_DOMAIN=splotnikov.dev`
   and real secrets (`APP_KEY`, `DB_PASSWORD`, `ADMIN_PASSWORD`).
4. Run `php artisan vendor:publish --tag=splotnikov-dev-assets` on the server.
5. Smoke-test against the droplet IP using a hosts-file entry or `curl --resolve`:
   `curl --resolve splotnikov.dev:443:<DROPLET_IP> https://splotnikov.dev/`.
6. Switch the `splotnikov.dev` **DNS A record** from the GitHub Pages IPs to the
   droplet IP. Verify TLS issuance.
7. Once stable, archive the static repo `spdotdev/splotnikov`.
```

- [ ] **Step 5: Commit**

```bash
cd /home/dev/splotnikov-dev
git add .github README.md CLAUDE.md docs/cutover-runbook.md
git commit -q -m "docs: CI, README, CLAUDE.md, cutover runbook"
```

---

### Task 5: Publish to GitHub and tag v0.1.0

**Files:** none (publishing only).

**Interfaces:**
- Produces: public repo `https://github.com/spdotdev/splotnikov-dev` with tag `v0.1.0` reachable by Composer's VCS driver.

- [ ] **Step 1: Create the public GitHub repo and push `main`**

```bash
cd /home/dev/splotnikov-dev
gh repo create spdotdev/splotnikov-dev --public --source=. --remote=origin --push
```
Expected: repo created; `main` pushed. Verify: `gh repo view spdotdev/splotnikov-dev --json visibility,name`.

- [ ] **Step 2: Tag and push the release**

```bash
cd /home/dev/splotnikov-dev
git tag v0.1.0
git push origin v0.1.0
```

- [ ] **Step 3: Confirm the tag is visible**

Run: `gh release list -R spdotdev/splotnikov-dev; git ls-remote --tags origin`
Expected: `refs/tags/v0.1.0` listed on the remote.

---

### Task 6: Wire into the host app and verify routing (integration TDD)

**Files (all under `/home/dev/<host-app>`):**
- Create: `tests/Feature/SplotnikovSiteTest.php`
- Modify: `composer.json` (add `repositories` + `require`)
- Modify: `routes/web.php` (host-scope the existing landing route)
- Modify: `.env.example` (document `SPLOTNIKOV_DOMAIN`) and local `.env` (set it)

**Interfaces:**
- Consumes: package routes `splotnikov.home` (`/`) and `splotnikov.cv` (`/cv`) on host `config('splotnikov-dev.domain')`.
- Produces: a passing the host app test proving the splotnikov.dev host renders the portfolio/CV while the host app's own landing still answers on its host.

- [ ] **Step 1: Write the failing feature test**

Create `/home/dev/<host-app>/tests/Feature/SplotnikovSiteTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class SplotnikovSiteTest extends TestCase
{
    public function test_portfolio_renders_on_the_splotnikov_host(): void
    {
        config(['splotnikov-dev.domain' => 'splotnikov.dev']);

        $this->get('http://splotnikov.dev/')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_cv_renders_on_the_splotnikov_host(): void
    {
        config(['splotnikov-dev.domain' => 'splotnikov.dev']);

        $this->get('http://splotnikov.dev/cv')
            ->assertOk()
            ->assertSee('Stanislav Plotnikov');
    }

    public function test_sd_admin_landing_still_answers_on_its_own_host(): void
    {
        // Default test host derives from APP_URL (localhost).
        $this->get('/')->assertOk();
    }
}
```

- [ ] **Step 2: Run it and confirm it fails (package not installed yet)**

Run (from `/home/dev/<host-app>`): `make art cmd="test --filter=SplotnikovSiteTest"`
Expected: FAIL — the two splotnikov tests 404 (no route for that host); the landing test may pass.

- [ ] **Step 3: Add the VCS repository and require the package**

Edit `/home/dev/<host-app>/composer.json`: add a top-level `repositories` array and the require line.
```jsonc
"repositories": [
    { "type": "vcs", "url": "https://github.com/spdotdev/splotnikov-dev" }
],
```
Then, from `/home/dev/<host-app>`:
```bash
make composer cmd="require spdotdev/splotnikov-dev:^0.1"
```
Expected: Composer resolves `v0.1.0` from GitHub and installs it.

> If Composer reports a platform/version conflict on `illuminate/support`, note the host app's Laravel major (`make composer cmd="show laravel/framework | grep versions"`), update the package's `composer.json` constraint accordingly, re-tag (`v0.1.1`), push, and require `^0.1.1`.

- [ ] **Step 4: Publish the package assets**

Run (from `/home/dev/<host-app>`): `make art cmd="vendor:publish --tag=splotnikov-dev-assets --force"`
Expected: `profile.png` (and the other files) copied to `public/vendor/splotnikov/`.

- [ ] **Step 5: Host-scope the host app's landing route so it cannot shadow the package**

Edit `/home/dev/<host-app>/routes/web.php` to:
```php
<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Scope the default landing to the host app's own host so site packages
// (e.g. splotnikov-dev) own their domains without being shadowed.
Route::domain(parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost')
    ->group(function () {
        Route::get('/', [LandingController::class, 'index']);
    });
```

- [ ] **Step 6: Point the dev environment at the splotnikov host**

Append to `/home/dev/<host-app>/.env.example`:
```dotenv

# Host that the splotnikov-dev site package answers on.
SPLOTNIKOV_DOMAIN=splotnikov.dev
```
And ensure the same line exists in `/home/dev/<host-app>/.env`. Then clear config cache:
```bash
make art cmd="config:clear"
```

- [ ] **Step 7: Run the splotnikov tests — expect green**

Run (from `/home/dev/<host-app>`): `make art cmd="test --filter=SplotnikovSiteTest"`
Expected: PASS (3 tests). If a splotnikov route returns 500, it is almost certainly a Blade-compile error from a CSS at-rule in the ported view — escape the offending `@rule` (`@@rule`) or wrap it in `@verbatim ... @endverbatim` in the package view, re-tag (`v0.1.1`), `make composer cmd="update spdotdev/splotnikov-dev"`, and re-run.

- [ ] **Step 8: Run the host app's full suite to confirm no regression**

Run (from `/home/dev/<host-app>`): `make art cmd="test"`
Expected: PASS — in particular `LandingPageTest` still green (its requests use the default `localhost` host, which the scoped landing route still matches).

- [ ] **Step 9: Keep Pint/Larastan green and commit the host app**

```bash
cd /home/dev/<host-app>
docker compose exec app ./vendor/bin/pint
docker compose exec app ./vendor/bin/phpstan analyse --memory-limit=512M
git add composer.json composer.lock routes/web.php tests/Feature/SplotnikovSiteTest.php .env.example
git commit -m "feat: mount splotnikov-dev site package via host-based routing"
```
Expected: pre-commit hook (Pint + Larastan) passes; commit succeeds. Do not commit `.env` or `public/vendor/splotnikov` (assets are republished on install).

---

## Self-Review

**Spec coverage:**
- Versioned package, `type: library`, namespace → Task 1. ✓
- Content parity (portfolio `/`, CV `/cv`) → Task 2 + Task 3 routes/controller. ✓
- Extends the host app routing via auto-discovered provider + `Route::domain` → Task 3, Task 6. ✓
- VCS + git tag distribution, no Packagist → Task 5, Task 6 Step 3. ✓
- Upgrade by version bump → README (Task 4), runbook references. ✓
- Wire into the host app + verify splotnikov.dev routes to template (local) → Task 6. ✓
- Route precedence resolution (host-scope landing) → Task 6 Step 5, regression check Step 8. ✓
- Static repo untouched → Global Constraints; no task writes to `/home/dev/splotnikov`. ✓
- Deferred (server, deploy, DNS, Packagist, shared packages) → cutover-runbook (Task 4), not implemented. ✓

**Placeholder scan:** No TBD/TODO; every code/command step shows concrete content. View porting is a concrete `cp` + `sed` (not a placeholder). ✓

**Type/name consistency:** Provider `Spdotdev\SplotnikovDev\SplotnikovDevServiceProvider`, controller `Spdotdev\SplotnikovDev\Http\Controllers\SiteController` with `index()`/`cv()`, view namespace `splotnikov::`, config key `splotnikov-dev.domain`, publish tags `splotnikov-dev-config`/`splotnikov-dev-assets`, asset path `vendor/splotnikov/profile.png`, package name `spdotdev/splotnikov-dev` — all consistent across Tasks 1–6. ✓
