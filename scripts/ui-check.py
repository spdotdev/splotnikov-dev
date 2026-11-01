#!/usr/bin/env python3
"""Phone UI checks at 390 px: every visible .btn is at least 44 px tall (touch target), and the first .btn of each
money page sits above 760 px (inside the first screen). Both languages. Exit 1 with the list of findings.
Usage: python3 scripts/ui-check.py https://example.dev   (needs: pip install playwright, playwright install chromium)"""
import sys
from playwright.sync_api import sync_playwright

base = sys.argv[1].rstrip('/')
ALL = ['/', '/cv', '/projects', '/toolkit', '/blog', '/contact', '/privacy']
MONEY = ['/', '/cv', '/projects']
MIN_H, MAX_TOP = 44, 760
paths = [(p, False) for p in ALL] + [('/nl' + ('' if p == '/' else p), True) for p in ALL]
JS = """()=>[...document.querySelectorAll('.btn')].filter(e=>{const r=e.getBoundingClientRect();const s=getComputedStyle(e);
 return r.width>0&&r.height>0&&s.visibility!=='hidden'&&s.display!=='none'}).map(e=>{const r=e.getBoundingClientRect();
 return {t:(e.innerText||'').trim().slice(0,28),h:Math.round(r.height*10)/10,top:Math.round(r.top+scrollY)}})"""
problems = []
with sync_playwright() as p:
    b = p.chromium.launch()
    pg = b.new_context(viewport={'width': 390, 'height': 844}, is_mobile=True, reduced_motion='reduce').new_page()
    for path, nl in paths:
        for attempt in (1, 2, 3):
            try:
                pg.goto(base + path, wait_until='load', timeout=45000)
                break
            except Exception:
                if attempt == 3:
                    raise
                pg.wait_for_timeout(1500)
        pg.wait_for_timeout(500)
        btns = pg.evaluate(JS)
        for x in btns:
            if x['h'] < MIN_H:
                problems.append('%s button "%s" is %s px tall (min %s)' % (path, x['t'], x['h'], MIN_H))
        base_path = path[3:] or '/' if nl else path
        if base_path in MONEY:
            if not btns:
                problems.append('%s has no visible button' % path)
            elif btns[0]['top'] > MAX_TOP:
                problems.append('%s first button "%s" at %s px (max %s)' % (path, btns[0]['t'], btns[0]['top'], MAX_TOP))
    b.close()
print(len(paths), 'pages,', len(problems), 'findings')
for x in problems:
    print(x)
sys.exit(1 if problems else 0)
