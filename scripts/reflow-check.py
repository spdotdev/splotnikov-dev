# Usage: [REFLOW_WIDTHS=320,390,1366 REFLOW_SIZES=100%,125%,200%] python3 scripts/reflow-check.py https://example.dev paths.txt
# WCAG 1.4.10 and 1.4.4: at 320 px wide (set REFLOW_WIDTHS and REFLOW_SIZES for others; the default sizes are as is and doubled), no page scrolls sideways
# and no element with its own text is clipped by overflow:hidden or clip. Lists every finding, exit 1 on any.
import os, sys
from playwright.sync_api import sync_playwright
base = sys.argv[1].rstrip('/'); paths = [l.strip() for l in open(sys.argv[2]) if l.strip()]
CLIP = """()=>{const out=[];for(const e of document.querySelectorAll('body *')){const cs=getComputedStyle(e);
 if(cs.display==='none'||cs.visibility==='hidden'||e.closest('script,style,noscript,svg,[aria-hidden=true]'))continue;
 if(![...e.childNodes].some(n=>n.nodeType===3&&n.textContent.trim()))continue;
 if(['hidden','clip'].includes(cs.overflowX)&&e.scrollWidth>e.clientWidth+1)out.push(e.tagName+'.'+String(e.className).slice(0,30)+' '+e.scrollWidth+'>'+e.clientWidth)}
 return out.slice(0,5)}"""
bad = []
with sync_playwright() as p:
    b = p.chromium.launch()
    for fs in os.environ.get('REFLOW_SIZES', '100%,200%').split(','):
      for vw in [int(x) for x in os.environ.get('REFLOW_WIDTHS', '320').split(',')]:
        pg = b.new_context(viewport={'width': vw, 'height': 700}, is_mobile=vw < 600, reduced_motion='reduce').new_page()
        for path in paths:
            pg.goto(base + path, wait_until='load', timeout=45000)
            pg.evaluate("s=>document.documentElement.style.fontSize=s", fs); pg.wait_for_timeout(500)
            w = pg.evaluate("document.documentElement.scrollWidth"); clip = pg.evaluate(CLIP)
            if w > vw or clip:
                bad.append((fs, vw, path, w, clip))
    b.close()
print(len(paths), 'pages x sizes x widths,', len(bad), 'findings'); [print(x) for x in bad]
sys.exit(1 if bad else 0)
