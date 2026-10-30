# Usage: npm pack axe-core@4.10.2 && tar xzf axe-core-*.tgz; AXE_JS=package/axe.min.js python3 scripts/axe-check.py https://example.dev paths.txt  (one path per line; needs: pip install playwright, playwright install chromium)
# Runs axe-core (WCAG 2.2 AA plus best practice) at 390 px in the light and the dark theme with reduced motion, and prints every violation.
import json, sys
from playwright.sync_api import sync_playwright
import os
axe = open(os.environ.get('AXE_JS', 'package/axe.min.js')).read()
base = sys.argv[1]; paths = [l.strip() for l in open(sys.argv[2]) if l.strip()]
res = {}
with sync_playwright() as p:
    b = p.chromium.launch()
    for scheme in ['light', 'dark']:
        ctx = b.new_context(viewport={'width': 390, 'height': 844}, color_scheme=scheme, reduced_motion='reduce', is_mobile=True, device_scale_factor=2)
        pg = ctx.new_page()
        for path in paths:
            try:
                pg.goto(base + path, wait_until="load", timeout=45000)
                pg.wait_for_timeout(1200)
                pg.add_script_tag(content=axe)
                r = pg.evaluate("async () => { const r = await axe.run(document, {runOnly:{type:'tag',values:['wcag2a','wcag2aa','wcag21a','wcag21aa','wcag22aa','best-practice']}}); return r.violations.map(v => ({id:v.id, impact:v.impact, n:v.nodes.length, ex:v.nodes.slice(0,2).map(n => n.target.join(' ')+' | '+(n.failureSummary||'').split('\\n')[1])})) }")
            except Exception as e:
                r = [{'id':'ERROR','impact':'x','n':0,'ex':[str(e)[:100]]}]
            res[scheme + ' ' + path] = r
        ctx.close()
    b.close()
bad = {k: v for k, v in res.items() if v}
print(len(res), 'page runs,', len(bad), 'with findings')
for k, v in bad.items():
    for x in v: print(k, x['id'], x['impact'], x['n'], x['ex'][:1])
