#!/usr/bin/env python3
"""Crawls the live site: every sitemap address answers 200 and names itself as canonical, every hreflang pair is
reciprocal and listed in the sitemap, every internal link on every sitemap page resolves (200 or 301). Free: stdlib only.
Usage: python3 scripts/check-live.py https://example.dev   (exit 1 with the list of problems)"""
import re, sys, urllib.request, urllib.error
import xml.etree.ElementTree as ET

base = sys.argv[1].rstrip('/')
NS = {'s': 'http://www.sitemaps.org/schemas/sitemap/0.9', 'x': 'http://www.w3.org/1999/xhtml'}
problems = []


def get(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'live-link-check'})

    class NoRedirect(urllib.request.HTTPRedirectHandler):
        def redirect_request(self, *a, **k):
            return None

    opener = urllib.request.build_opener(NoRedirect)
    try:
        r = opener.open(req, timeout=30)
        return r.status, r.read().decode('utf8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, ''
    except Exception as e:
        return 0, str(e)


status, body = get(base + '/sitemap.xml')
if status != 200:
    print('sitemap.xml answers', status); sys.exit(1)
root = ET.fromstring(body)
pairs, locs, htmls = {}, [], {}
for u in root.findall('s:url', NS):
    loc = u.find('s:loc', NS).text.strip()
    if loc in locs:
        problems.append(f'{loc} is listed twice')
    locs.append(loc)
    pairs[loc] = {l.get('hreflang'): l.get('href') for l in u.findall('x:link', NS)}

for loc in locs:
    st, html = get(loc)
    if st != 200:
        problems.append(f'{loc} answers {st}'); continue
    htmls[loc] = html
    if f'<link rel="canonical" href="{loc}" />' not in html:
        problems.append(f'{loc} does not name itself as canonical')
    m = re.search(r'<meta name="robots" content="([^"]*)"', html)
    if m and 'noindex' in m.group(1):
        problems.append(f'{loc} is noindex but in the sitemap')
    alts = dict(re.findall(r'<link rel="alternate" hreflang="([^"]+)" href="([^"]+)"', html))
    if pairs[loc] and pairs[loc] != alts:
        problems.append(f'{loc}: sitemap alternates differ from the page alternates')

for loc, alts in pairs.items():
    if not alts:
        continue
    for lang in ('en', 'nl'):
        other = alts.get(lang)
        if other not in pairs:
            problems.append(f'{loc} points at {other} ({lang}), which is not in the sitemap')
        elif pairs[other] != alts:
            problems.append(f'{other} does not list the same hreflang pair as {loc}')

seen = {}
for loc, html in htmls.items():
    for href in re.findall(r'<a\b[^>]*\shref="((?:' + re.escape(base) + r')?/[^"#]*)', html):
        seen.setdefault(href if href.startswith('http') else base + href, loc)
for url, frm in sorted(seen.items()):
    if re.search(r'\.(pdf|vcf|png|svg|jpg|ico|webmanifest)$', url):
        continue
    st, _ = get(url)
    if st not in (200, 301):
        problems.append(f'{url} (linked from {frm}) answers {st}')

# Outbound links: HEAD, then GET when HEAD is refused, 15 s each, redirects followed; 200 expected. Sites that refuse bots
# on purpose (LinkedIn answers 999, some hosts 403 or 429) are reported as a note, not a problem.
BOT_WALLS = ('linkedin.com',)


def status_of(url):
    last = 0
    for method in ('HEAD', 'GET'):
        try:
            req = urllib.request.Request(url, method=method, headers={'User-Agent': 'Mozilla/5.0 live-link-check'})
            return urllib.request.urlopen(req, timeout=15).status
        except urllib.error.HTTPError as e:
            last = e.code
        except Exception:
            last = 0
    return last


outbound = {}
host = re.sub(r'^https?://', '', base)
for loc, html in htmls.items():
    for href in re.findall(r'<a\b[^>]*\shref="(https?://[^"#]+)', html):
        if host not in href:
            outbound.setdefault(href, loc)
notes = []
for url, frm in sorted(outbound.items()):
    st = status_of(url)
    if st == 200:
        continue
    if st in (999, 403, 429) and any(w in url for w in BOT_WALLS):
        notes.append(f'{url} answers {st} to bots (expected, not counted)')
    else:
        problems.append(f'outbound {url} (linked from {frm}) answers {st}')

print(f'{len(locs)} sitemap addresses, {len(seen)} internal and {len(outbound)} outbound links checked, {len(problems)} problems')
for n in notes:
    print(' note:', n)
for p in problems:
    print(' -', p)
sys.exit(1 if problems else 0)
