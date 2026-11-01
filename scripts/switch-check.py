#!/usr/bin/env python3
"""Language switch on pages without a Dutch twin (blog, a post): after a click on NL the page language, the main
navigation label and the language group label are Dutch; after a click on EN they are English again.
Usage: python3 scripts/switch-check.py https://example.dev [/blog /blog/some-post]   (needs: pip install playwright, playwright install chromium)"""
import sys
from playwright.sync_api import sync_playwright

base = sys.argv[1].rstrip('/')
paths = sys.argv[2:] or ['/blog', '/blog/strangler-fig-what-the-bridge-taught-me']
JS = "()=>({lang:document.documentElement.lang,nav:document.querySelector('nav.top').getAttribute('aria-label'),seg:document.querySelector('.seg[role=group]').getAttribute('aria-label'),h:document.querySelector('nav.top .links a')?.textContent.trim()})"
problems = []
with sync_playwright() as p:
    b = p.chromium.launch()
    pg = b.new_context(viewport={'width': 1366, 'height': 768}, reduced_motion='reduce').new_page()
    for path in paths:
        pg.goto(base + path, wait_until='load', timeout=45000)
        pg.evaluate("()=>{try{localStorage.clear()}catch(e){}}")
        pg.reload(wait_until='load')
        en = pg.evaluate(JS)
        pg.click('#nl'); pg.wait_for_timeout(300)
        nl = pg.evaluate(JS)
        pg.click('#en'); pg.wait_for_timeout(300)
        back = pg.evaluate(JS)
        if (en['lang'], nl['lang'], back['lang']) != ('en', 'nl', 'en'):
            problems.append('%s html lang %s > %s > %s' % (path, en['lang'], nl['lang'], back['lang']))
        if nl['nav'] != 'Hoofdnavigatie' or nl['seg'] != 'Taal':
            problems.append('%s after NL: nav "%s", language group "%s"' % (path, nl['nav'], nl['seg']))
        if back != en:
            problems.append('%s after EN again: %s differs from the first load %s' % (path, back, en))
    b.close()
print(len(paths), 'pages,', len(problems), 'problems'); [print(x) for x in problems]
sys.exit(1 if problems else 0)
