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

## Leads nobody sees (round 7)
A lead that is stored but not mailed logs one `critical` line (lead id and the list command, no visitor data), so the existing log alerting can pick it up. The exact commands to list and purge leads on the server are kept in the private operations notes, not in this public repository.

# Round 7 (2026-10-31, v0.3.32)
- Three more blog drafts in sd-cms, status review, none published: the contact form broken in three layers, Lighthouse versus axe (with contrast ratios the agent calculated), escaping JSON inside HTML. Each carries the author note about agent-done work.

# Round 8 (2026-10-31, v0.3.33)
- Full live sweep with scuttle.dev: Lighthouse mobile on the 13 sitemap addresses incl. Dutch twins, 100 in accessibility, best practices and SEO and 95 to 100 in performance; axe 0 findings in light and dark. Public hygiene: no IP, token or key in HEAD or the last 50 commits; server nickname and home paths removed from HEAD. /nl/cv "At a glance" read in Dutch by the agent (not a native editor), left unchanged.

# Round 9 (2026-10-31, autonomous stability and marketing run)
- **Baseline:** phpunit 38 tests (1 skipped), 1380 assertions, 1.3 s; pint, phpstan and composer audit clean; no outdated direct dependency. **End:** phpunit 50 tests (1 skipped), 2151 assertions, 1.5 s; pint, phpstan and composer audit clean. CI run 41242848167 green.
- **Contact form, measured at 390 px (local dump of the previous version, then the new one):** the lead went from 49 to 31 words (EN) and 46 to 32 (NL), 201 to 96 px (EN) and 229 to 120 px (NL); the first field from 508 to 359 px (EN) and 502 to 349 px (NL); the Send button from 929 to 750 px (EN) and 923 to 740 px (NL), so it is in the first screen of an 844 px phone. Labels 11 px to 12 px. Both buttons 34 px to 44 px tall and full width under 600 px.
- **Sideways scroll:** the decrypt headline pinned its width while random letters settled, so the Dutch contact page reached 429 px (light) and 425 px (dark) at 390 px, and the Dutch CV 366 to 396 px. The headline is now clipped and capped at the screen width while it settles; sampled during the animation on 12 Dutch and English pages at 360 and 390 px, light and dark: none above the viewport.
- **Dutch on the Dutch contact page:** the seven topics are translated (the server still receives the English value), and every validation message, the bot-check message and the save-failed message come in Dutch when the form was sent from a Dutch page. All errors are listed, not only the first, and they were unreadable in the light theme (axe found 1.34:1 contrast); fixed.
- **After a send or an error** the message scrolls into view below the menu bar (82 to 105 px from the top at 390 px, 159 to 171 px at 1366 px) and the form is empty again. The menu bar is more opaque (94 percent) because axe measured 4.26:1 for the inactive language button when a message sat under it.
- **Static rules now tested on every page in both languages:** every image has width, height and alt; every external link has rel noopener (found and fixed: five links on the CV and the book-a-call button); no duplicate id; exactly one h1 and no skipped level; every footer link answers 200 or 301. Odd requests (404 in both languages, HEAD, POST to a GET route, a 6000 character query, a script in `?about=`) and odd CMS articles (HTML in title, summary and body, a very long title, an empty body, no date) are tested.
- **Search snippets:** titles now say what the page is (for example "CV: senior developer and architect"), descriptions end at a sentence end and none is cut with an ellipsis (home, CV, contact, projects, toolkit, blog, English and Dutch); the home description keeps the availability line. A test pins this. Open: the title of a long blog post is still cut (issue 6).
- **Calls to action:** the projects page had its first button at 5218 px (bottom); it now has "Contact me" above the project list, first button at 463 px. The CV closing button opens the form with the topic "Freelance or contract role" selected. Home 562 px, CV 413 px.
- **Trust block (CV, at a glance):** "On site in Brabant, remote for the rest of the Netherlands and Europe" appeared nowhere else; it now says what the contact page says. The work line lists automation first, as the positioning says. The repository count (16) was checked today; issue 7 covers keeping it true.
- **axe and overflow at the end,** local dump, 18 pages including the contact page in the sent, saved and error states, English and Dutch, 390 px, light and dark: 0 findings. Overflow at 360 px: 0.
- **Cut from the contact lead:** the sentence "A hard question gets what I need to look at before I answer it, not a dodge" (the brief asked for one sentence plus the promise). The same promise is kept on the page of the services on scuttle.dev.
