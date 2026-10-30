# Usage: python3 scripts/overflow-check.py https://example.dev paths.txt : lists pages that scroll sideways at 360 px.
import sys
from playwright.sync_api import sync_playwright
base=sys.argv[1]; paths=[l.strip() for l in open(sys.argv[2]) if l.strip()]
bad=[]
with sync_playwright() as p:
    b=p.chromium.launch(); ctx=b.new_context(viewport={'width':360,'height':800},is_mobile=True,reduced_motion='reduce'); pg=ctx.new_page()
    for path in paths:
        pg.goto(base+path,wait_until='load',timeout=45000); pg.wait_for_timeout(600)
        w=pg.evaluate("document.documentElement.scrollWidth")
        if w>360:
            el=pg.evaluate("[...document.querySelectorAll('body *')].filter(e=>{const r=e.getBoundingClientRect();return r.right>362&&r.width>0&&getComputedStyle(e).position!=='fixed'}).slice(0,3).map(e=>(e.id||String(e.className)||e.tagName).slice(0,30))")
            bad.append((path,w,el))
    b.close()
print(len(paths),'pages,',len(bad),'overflow'); [print(x) for x in bad]
