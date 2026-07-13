# splotnikov.dev cutover runbook

> This file is the splotnikov.dev-specific slice of the cutover to the
> Laravel-served version of the site. Nothing is live yet — DNS still points
> at GitHub Pages.

## Site facts

| | value |
|--|--|
| Domain | `splotnikov.dev` |
| Static repo (live on Pages until cutover) | [`spdotdev/splotnikov`](https://github.com/spdotdev/splotnikov) |
| Site package (this repo) | `spdotdev/splotnikov-dev` |
| Domain env var (host app `.env`) | `SPLOTNIKOV_DOMAIN=splotnikov.dev` |
| Assets path | `/vendor/splotnikov/` |
| Publish tag | `splotnikov-dev-assets` |
| Current DNS apex `A` | GitHub Pages `185.199.108–111.153` → change to `<DROPLET_IP>` |

## Cutover checklist (after the server + host app are up)

1. Confirm `spdotdev/splotnikov-dev` is required in the host app and `SPLOTNIKOV_DOMAIN=splotnikov.dev` is set in the server `.env`.
2. `php artisan vendor:publish --tag=splotnikov-dev-assets --force` on the server.
3. Verify against the droplet IP without changing DNS:
   `curl -s --resolve splotnikov.dev:443:<DROPLET_IP> https://splotnikov.dev/ | grep -o 'Stanislav Plotnikov'`
   and `…/cv`, `…/robots.txt`, `…/vendor/splotnikov/profile.png` → all 200.
4. Lower the apex `A` TTL to 300s; remove the custom domain from the static repo's Pages settings.
5. Repoint the `splotnikov.dev` apex `A` record from the Pages IPs to `<DROPLET_IP>`; remove Pages `AAAA`/`CNAME`.
6. Let TLS issue (Caddy auto / certbot), then verify `https://splotnikov.dev/` in the open.
7. Once stable, archive [`spdotdev/splotnikov`](https://github.com/spdotdev/splotnikov).

**Rollback:** revert the apex `A` to `185.199.108–111.153` and re-add the custom domain in Pages settings; the static repo is untouched and resumes within one TTL.
