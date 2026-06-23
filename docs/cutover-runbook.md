# splotnikov.dev cutover runbook

> **The authoritative, full migration manual lives in `sd-admin`:**
> [`spdotdev/sd-admin` → `docs/MIGRATION.md`](https://github.com/spdotdev/sd-admin/blob/main/docs/MIGRATION.md).
> It covers provisioning `d051`, deploying sd-admin, the reverse proxy + TLS, the
> verify-before-DNS step, and rollback. This file is just the splotnikov.dev-specific
> slice. Nothing is live yet — DNS still points at GitHub Pages.

## Site facts

| | value |
|--|--|
| Domain | `splotnikov.dev` |
| Static repo (live on Pages until cutover) | [`spdotdev/splotnikov`](https://github.com/spdotdev/splotnikov) |
| Site package (this repo) | `spdotdev/splotnikov-dev` |
| Domain env var (sd-admin `.env`) | `SPLOTNIKOV_DOMAIN=splotnikov.dev` |
| Assets path | `/vendor/splotnikov/` |
| Publish tag | `splotnikov-dev-assets` |
| Current DNS apex `A` | GitHub Pages `185.199.108–111.153` → change to `<DROPLET_IP>` |

## Cutover checklist (after `d051` + sd-admin are up — see the master manual)

1. Confirm `spdotdev/splotnikov-dev` is required in sd-admin and `SPLOTNIKOV_DOMAIN=splotnikov.dev` is set in the server `.env`.
2. `php artisan vendor:publish --tag=splotnikov-dev-assets --force` on the server.
3. Verify against the droplet IP without changing DNS:
   `curl -s --resolve splotnikov.dev:443:<DROPLET_IP> https://splotnikov.dev/ | grep -o 'Stanislav Plotnikov'`
   and `…/cv`, `…/robots.txt`, `…/vendor/splotnikov/profile.png` → all 200.
4. Lower the apex `A` TTL to 300s; remove the custom domain from the static repo's Pages settings.
5. Repoint the `splotnikov.dev` apex `A` record from the Pages IPs to `<DROPLET_IP>`; remove Pages `AAAA`/`CNAME`.
6. Let TLS issue (Caddy auto / certbot), then verify `https://splotnikov.dev/` in the open.
7. Once stable, archive [`spdotdev/splotnikov`](https://github.com/spdotdev/splotnikov).

**Rollback:** revert the apex `A` to `185.199.108–111.153` and re-add the custom domain in Pages settings; the static repo is untouched and resumes within one TTL.
