#!/usr/bin/env python3
"""Tabs through pages at 1366 px and lists every focus stop that shows no outline or ring, plus whether the skip link comes first.
Usage: python3 scripts/focus-check.py https://example.dev /,/projects,/contact   (needs: pip install playwright, playwright install chromium)"""
import sys
from playwright.sync_api import sync_playwright

base = sys.argv[1].rstrip('/')
paths = (sys.argv[2] if len(sys.argv) > 2 else '/,/cv,/projects,/toolkit,/blog,/contact').split(',')
JS = """()=>{const e=document.activeElement;if(!e||e===document.body)return null;const s=getComputedStyle(e);const r=e.getBoundingClientRect();
 return {id:e.tagName+' '+(e.getAttribute('href')||e.id||''),txt:(e.innerText||e.getAttribute('aria-label')||'').trim().slice(0,24),
 ring:(s.outlineStyle!=='none'&&parseFloat(s.outlineWidth)>0)||s.boxShadow!=='none',shown:r.width>0&&r.height>0}}"""
bad = 0
with sync_playwright() as p:
    b = p.chromium.launch()
    pg = b.new_context(viewport={'width': 1366, 'height': 800}).new_page()
    for path in paths:
        pg.goto(base + path, wait_until='load'); pg.wait_for_timeout(600)
        stops, first = [], None
        for _ in range(90):
            pg.keyboard.press('Tab'); r = pg.evaluate(JS)
            if not r: continue
            if first is None: first = r['txt']
            elif r['id'] == stops[0]['id'] and r['txt'] == stops[0]['txt']: break
            stops.append(r)
        miss = [s for s in stops if not s['ring'] or not s['shown']]
        bad += len(miss) + (0 if first and first.lower().startswith(('skip', 'naar de inhoud')) else 1)
        print(f'{path}: {len(stops)} stops, first "{first}", without visible focus: {[m["id"] + " " + m["txt"] for m in miss]}')
    b.close()
sys.exit(1 if bad else 0)
