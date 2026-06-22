# splotnikov.dev cutover runbook

Go-live: move splotnikov.dev from GitHub Pages (static repo) to the sd-admin
app on the DigitalOcean server. Nothing here is automated yet.

1. Provision `d051` (DigitalOcean). See the `d0-admin` project.
2. Deploy sd-admin (with `spdotdev/splotnikov-dev` required) to the server.
3. In the **server** `.env` (never git): set `SPLOTNIKOV_DOMAIN=splotnikov.dev`
   and real secrets (`APP_KEY`, `DB_PASSWORD`, `ADMIN_PASSWORD`).
4. Run `php artisan vendor:publish --tag=splotnikov-dev-assets` on the server.
5. Smoke-test against the droplet IP using a hosts-file entry or `curl --resolve`:
   `curl --resolve splotnikov.dev:443:<DROPLET_IP> https://splotnikov.dev/`.
6. Switch the `splotnikov.dev` **DNS A record** from the GitHub Pages IPs to the
   droplet IP. Verify TLS issuance.
7. Once stable, archive the static repo `spdotdev/splotnikov`.
