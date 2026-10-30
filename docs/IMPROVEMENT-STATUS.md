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

