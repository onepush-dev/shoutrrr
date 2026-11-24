# Shoutrrr (Coolify social scheduler) on Railway

[Shoutrrr](https://github.com/coollabsio/shoutrrr) (Apache-2.0), from the team behind Coolify: write a post once and schedule it to X, LinkedIn, Bluesky, Mastodon, Threads, Instagram and more, with workspaces, an approval-friendly calendar, analytics, a REST API and MCP. Deploy it on Railway with one click. (Not the containrrr/shoutrrr notification library.)

[![Deploy on Railway](https://railway.com/button.svg)](https://railway.com/deploy/TEMPLATE_CODE)

## What this template runs
- **shoutrrr**: `ghcr.io/onepush-dev/shoutrrr:1.5.9`, the official `ghcr.io/coollabsio/shoutrrr:1.5.9` image (application unmodified). One container runs the FrankenPHP/Octane web server on 8080 plus the queue worker and scheduler. One volume at `/var/www/html/storage` holds uploads, Passport keys, logs and the SQLite database (`storage/database/database.sqlite`). Health check `/up`.

On first boot an entrypoint step creates the **instance owner** `admin@example.com` (email pre-verified, default workspace provisioned, same steps as upstream's sign-up) with the password from `SHOUTRRR_ADMIN_PASSWORD`. Public registration stays closed (upstream default), so nobody else can claim the instance; invite teammates from the app.

## Variables
| Variable | Value |
|---|---|
| `APP_KEY` | `${{secret(32)}}`: Laravel encryption key |
| `APP_URL` | `https://${{RAILWAY_PUBLIC_DOMAIN}}` |
| `SHOUTRRR_ADMIN_PASSWORD` | `${{secret(24)}}`: password of the owner account `admin@example.com` (first boot only) |

Baked defaults: `APP_ENV=production`, `OCTANE_HTTPS=true`, `TRUSTED_PROXIES=*`, `DB_CONNECTION=sqlite`, database cache/queue/session, `MAIL_MAILER=log`, `SELF_HOSTED=true`.

## After deploy
1. Sign in at the Railway URL as `admin@example.com` with `SHOUTRRR_ADMIN_PASSWORD`, then change the email and password under Settings.
2. Connect accounts: Bluesky and Mastodon work right away; X, LinkedIn, Meta (Instagram/Facebook/Threads) etc. need your own free developer-app credentials (`X_CLIENT_ID`/`X_CLIENT_SECRET`, `LINKEDIN_*`, …) added as service variables. Use `https://<your-domain>` callback URLs. See https://github.com/coollabsio/shoutrrr#readme
3. Optional: real email via `MAIL_MAILER=smtp` + `MAIL_*`, or Postgres via `DB_CONNECTION=pgsql` + `DB_*`.

Not affiliated with Shoutrrr or coolLabs.
