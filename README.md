# FBVideo Downloader (core PHP)

A framework-free PHP 8 rewrite of the FBVideo Downloader landing page and download flow. No Composer packages, no build step — upload it to any PHP host and it runs.

## Requirements

- PHP 8.1+ with the `curl`, `json`, `mbstring`, and `fileinfo` extensions (all enabled by default on most hosts)
- Apache with `mod_rewrite` and `mod_headers` (recommended), or Nginx with an equivalent config (see below)
- Outbound internet access from the server (to resolve Facebook video links)

## Setup

1. Copy `config/.env.example` to `config/.env` and fill in real values (site URL, contact emails, AdSense info once approved).
2. Provision the video-extraction engine (a standalone `yt-dlp` binary, no Python required):
   ```
   php scripts/setup-ytdlp.php
   ```
   This downloads the correct binary for your OS into `/bin`. Re-run it any time to check for a newer version.
3. (Optional) Regenerate the Apple touch icon / Open Graph image after changing the brand mark:
   ```
   php scripts/generate-images.php
   ```
4. Serve the app:
   - **Apache**: point the vhost/docroot at the project root. The included `.htaccess` files handle routing and directory protection.
   - **Local testing**: `php -S localhost:8000 scripts/dev-server-router.php` (the router mirrors the `.htaccess` protections, since PHP's built-in server ignores `.htaccess`).
   - **Nginx**: there's no `.htaccess` support, so add equivalent rules — deny `/config/`, `/includes/`, `/helpers/`, `/services/`, `/controllers/`, `/templates/`, `/bin/`, `/cache/`, `/scripts/`, and rewrite `/robots.txt`, `/sitemap.xml`, `/ads.txt` to their `.php` counterparts.

## Running with Docker

```
docker compose up --build
```

This builds a PHP 8.2 + Apache image (`mod_rewrite`/`mod_headers` enabled, `.htaccess` honored), downloads the Linux `yt-dlp` binary at build time, and serves the app at `http://localhost:8090`. Configuration is passed via environment variables in `docker-compose.yml` (edit those, or override with `docker compose run -e KEY=value`) — no `.env` file is baked into the image.

## Folder structure

```
config/       env loading + app constants (denied from direct web access)
includes/     bootstrap, session, CSRF, shared content data
helpers/      sanitize, http/JSON, validation, rate limiting, icon set
services/     YtDlpService (proc_open wrapper) + VideoResolver
controllers/  thin classes that gather data and hand off to templates
templates/    partials/ (header, footer) + sections/ (hero, FAQ, ...) + pages/
api/          JSON endpoints: download.php, media.php, contact.php
assets/       css/, js/, images/ — no build step, edit directly
bin/          yt-dlp binary (gitignored, provisioned by scripts/setup-ytdlp.php)
cache/        file-based rate-limit buckets (gitignored contents)
```

Each of `config/`, `includes/`, `helpers/`, `services/`, `controllers/`, `templates/`, `bin/`, `cache/`, and `scripts/` ships its own `.htaccess` denying direct web access — only files at the project root and inside `assets/`/`api/` are reachable by URL.

## How video resolution works

`api/download.php` validates the submitted Facebook URL, then calls `services/VideoResolver.php`, which shells out to the `yt-dlp` binary (`services/YtDlpService.php`, via `proc_open`) to fetch metadata and available qualities. `api/media.php` re-invokes `yt-dlp` to stream the actual file straight through to the browser — nothing is written to disk on the server, and there's no CDN-URL-expiry problem since the download always uses a freshly resolved link.

## Security notes

- CSRF: a per-session token is embedded in a `<meta>` tag and sent as `X-CSRF-Token` on every fetch from `assets/js/app.js`; both `api/download.php`, `api/media.php`, and `api/contact.php` verify it.
- Sessions use `HttpOnly`, `SameSite=Lax`, and `Secure` (when served over HTTPS) cookies.
- All user input is validated server-side (`helpers/validation.php`) before touching the filesystem or the `yt-dlp` process; format IDs are restricted to a strict allow-list pattern.
- Rate limiting is file-based (`helpers/rate_limit.php`) and keyed by client IP, applied to all three API endpoints.
- All dynamic values rendered into HTML go through `e()` (an `htmlspecialchars` wrapper); the small amount of client-side DOM building in `app.js` uses `textContent`/property assignment rather than string-concatenated `innerHTML`, since video titles/thumbnails originate from a third party.

## Making the site AdSense-ready

Google's review looks at policy compliance and content quality, not just technical setup, so:

- `Privacy Policy` (`/privacy-policy.php`), `Terms of Use` (`/terms.php`), and `About` (`/about.php`) pages are included with substantive, non-boilerplate content — thin/generic policy pages are one of the most common rejection reasons.
- A `/guides/` section (4 full articles: iPhone, Android, Reels, troubleshooting) adds genuine original content beyond the tool itself — "insufficient content" is the other most common rejection reason, especially for single-purpose tool sites.
- `ads.php` (served at `/ads.txt`) automatically emits the correct `ads.txt` line once you set `ADSENSE_CLIENT_ID` in `.env` — leave `ADSENSE_ENABLED=false` (the default) and the file stays empty, which is fine pre-approval.
- Set `ADSENSE_ENABLED=true` and `ADSENSE_CLIENT_ID=ca-pub-...` in `.env` once you're ready to load the AdSense script and enable `templates/partials/ad-slot.php`. **We deliberately don't pre-place ad units** — reviewers generally see a clean, ad-free site during initial review, and empty ad slots before approval can look worse than no ads at all.
- A cookie-consent banner (`templates/partials/consent-banner.php` + `assets/js/consent.js`) only renders once `ADSENSE_ENABLED` is true, and gates the AdSense script behind explicit Accept — required for EEA/UK traffic. **Caveat:** this is a good-faith, functional consent gate, not a *Google-certified CMP*; Google's Consent Management Platform policy (Jan 2024+) technically requires an integration from [Google's certified CMP list](https://support.google.com/adsense/answer/13554116) for EEA/UK traffic at scale — swap this out for one of those (CookieYes, Cookiebot, etc.) if that applies to you.
- `sitemap.php` (served at `/sitemap.xml`, includes all guide pages) and `robots.php` (served at `/robots.txt`) are generated from your configured `SITE_URL`.
- Performance: gzip/deflate compression, 1-year immutable cache headers on static assets with automatic cache-busting (`assetUrl()` in `helpers/assets.php` appends `?v=<mtime>`), and Organization/BreadcrumbList/Article structured data throughout.

One honest caveat, unchanged by any of the above: download tools are a category Google scrutinizes closely, since they can be used against copyrighted content. The Terms of Use page states an acceptable-use policy and the app never stores video files, but approval isn't guaranteed by technical readiness alone — that risk is inherent to the category, not something code changes can fully offset.
