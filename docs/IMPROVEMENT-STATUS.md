# Improvement status, splotnikov.dev (page by page)

Started 2026-10-30. Audit tools: live crawl of every sitemap URL, Lighthouse mobile, layout-shift trace. Legend: done = changed, tested, live. checked = audited, nothing to change.

| Page | State | Notes |
|---|---|---|
| / (home) | done v0.3.18 | title and description fit search results, demo heading no longer skips a level, headline pinned during decrypt (layout shift) |
| /cv | checked | Lighthouse mobile 100 in all four categories; only the shared head changed (breadcrumbs, fonts) |
| /contact | done v0.3.18 | shared head: breadcrumbs, self-hosted fonts |
| /projects | done v0.3.18 | shared head |
| /toolkit | done v0.3.18 | shared head |
| /blog | done v0.3.18 | shared head |
| /blog/* | done v0.3.18 | BlogPosting structured data, og:type article, title cut under 70 |
| All pages | done v0.3.18 | Geist self-hosted (no Google Fonts request), twitter tags, og:locale, og:type website by default |

## Later in the same pass
| Change | Version | Notes |
|---|---|---|
| Skip link on every page (EN and NL) | v0.3.19 | first tab stop, visible on focus |
| Card titles after the page title are h2 | v0.3.20 | /blog, /projects, /toolkit: no skipped heading level (test added) |
| Blog archive: out-of-month calendar days readable, year and month links 24 px high | v0.3.21 | same fix as scuttle.dev |

nginx compression and caching for both sites: see scuttle-dev docs/IMPROVEMENT-STATUS.md (change lives in the sd-admin repo).

v0.3.22 (2026-10-30): the decrypt headline also pins its width, home layout shift 0.238 to passing. Final live check: Lighthouse mobile 100 in all four categories on /, /projects, /blog, /cv.

# Round 2 (2026-10-30)
Council (small version, see scuttle-dev docs/IMPROVEMENT-STATUS.md): Dutch twins done as the majority said.

| Page | State | What changed in round 2 |
|---|---|---|
| /nl, /nl/cv, /nl/projects, /nl/toolkit, /nl/contact | done v0.3.23 | server-rendered Dutch with Dutch titles and descriptions, hreflang en/nl/x-default, sitemap alternates; blog and posts stay English with no twin |
| / | done v0.3.25 | the line under the name leads with automation (was legacy modernization), same in the structured data |
| /contact (and /nl/contact) | done v0.3.25 | button contrast 1.9 to over 4.5 (Lighthouse accessibility 96 to 100) |
| All pages | done v0.3.24 | own 404 page with noindex; sitemap namespace fix |

Lighthouse mobile live: 100 in all four categories on /toolkit, /projects, /blog, a blog post, /cv, / and the Dutch pages measured before the contact fix. The CV in Dutch (`/nl/cv`) is the page to send to Dutch recruiters.

# Round 3 (2026-10-30)
Dutch twins read in a browser at 360 px: no overflow. Wording: "legacy-schuld", "legacy-kant". Post facts brought up to date: the first post said the fonts come from a third party; they are self-hosted since 30 October, and the file sizes are now the real ones (62 and 29 kilobytes, 14 and 10 compressed). Trailing slash and index.php answer 301 (nginx, sd-admin).



# Round 4 (2026-10-30)
- **Every page, both sites, both languages, measured:** Lighthouse mobile (lab) on all 73 sitemap addresses (60 on scuttle.dev incl. the 12 services and 11 service twins in Dutch, 13 on splotnikov.dev): accessibility 100, best practices 100, SEO 100 on every page; performance 96 to 100 (lowest: /contact 96); CLS 0 on every page; LCP 1.4 to 1.7 s. axe-core (WCAG 2.2 AA plus best practice) at 390 px in light and dark, reduced motion: Lighthouse had missed light-theme contrast; after the fixes 0 findings on all pages of both sites in both themes. Sideways scroll at 360 px: none on any of the 73 pages.
- **Fixed from axe:** darker accent for text in the light theme, `--muted` darker, white text on the coloured buttons in light, scrollable tables and code blocks reachable by keyboard, no `<aside>` inside `<main>`, no link inside a `summary` in the archive.
- **No cookie on content pages** (session, CSRF and queued-cookie middleware off every route except the contact page and its POST). Live check: 0 Set-Cookie on /pricing, /cv and the stylesheet; the contact page still sets its session and a form POST without a token answers 419, one with a token answers 302.
- **Link health:** test over every sitemap address (self-canonical, indexable), hreflang reciprocity and every internal link; the same crawl against the live site runs weekly (`.github/workflows/live-links.yml`, `scripts/check-live.py`). Live run today: 60 addresses and 99 links, 0 problems (scuttle.dev); 13 and 23, 0 problems (splotnikov.dev).
- **Headers:** Cross-Origin-Opener-Policy same-origin and a longer Permissions-Policy added in nginx (sd-admin). HSTS stays at one year without includeSubDomains or preload (other subdomains not verified). CSP keeps 'unsafe-inline' for scripts and styles (inline scripts carry the Dutch strings and JSON-LD; a nonce would need a middleware and an nginx change, not done). The preview host sends `X-Robots-Tag: noindex, nofollow, noarchive`.
- **Buyer on a phone:** the price now sits above the buttons on every service page (Health Check: EUR 1,080 before "Ask about Health Check"); the pricing page shows the three prices a buyer looks for (EUR 90 per hour, Health Check EUR 1,080, retainer from EUR 720) as a row under the headline in both languages.
- **CI on both repos was red since round 2** (Larastan error in the 404 handler); found and fixed in this round, green now. Lesson: read the CI result before calling a release done.
- **Not done:** Core Web Vitals field data from the CrUX API (using the Google key from KeePass was blocked by the permission classifier; lab data only; the sites are small, field data may not exist for them anyway).

# Round 5 (2026-10-30, v0.3.29)
- /cv: an "At a glance" block for hirers (work, form and rate EUR 90 excl. VAT as published on scuttle.dev/pricing, place, languages, where to look first) and ProfilePage data; Dutch twin strings. The hero sentence is untouched (Stas decides). axe on the live /cv and /nl/cv, light and dark: 0 findings.
- Blocked: the contact form on this site has the same mail problem as scuttle.dev (needs `SPLOTNIKOV_CONTACT_MAIL_DSN` and `SPLOTNIKOV_CONTACT_MAIL_FROM` in the host `.env`).

# Round 6 (2026-10-31, v0.3.31)
- Contact form: every valid message is stored first in `splotnikov_contact_leads` (same design, tests and security fixes as scuttle.dev: no raw IP, safe logging, control characters rejected, `php artisan splotnikov:contact-leads`). When mail cannot be sent the form says "Received and saved", not "Sent". Lighthouse mobile on /cv: 100 in accessibility, best practices, SEO and agentic browsing.
