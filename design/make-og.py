"""Render design/og-image.html to public/og-image.png (1200 x 630) with Playwright. Danger: low, writes one file.
Run from the repo root: python3 design/make-og.py"""
import asyncio
import hashlib
import pathlib

from playwright.async_api import async_playwright

ROOT = pathlib.Path(__file__).resolve().parent.parent


def source_hash() -> str:
    """Hash of everything the card is rendered from: the page and the portrait. OgCardTest recomputes it."""
    h = hashlib.sha256()
    for name in ('design/og-image.html', 'public/portrait.jpg'):
        h.update((ROOT / name).read_bytes())
    return h.hexdigest()


async def main() -> None:
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page(viewport={'width': 1200, 'height': 630}, device_scale_factor=1)
        await page.goto((ROOT / 'design' / 'og-image.html').as_uri())
        await page.wait_for_timeout(1500)  # the fonts
        await page.screenshot(path=str(ROOT / 'public' / 'og-image.png'))
        await browser.close()
    (ROOT / 'public' / 'og-image.source-hash').write_text(source_hash() + '\n')
    print('written public/og-image.png and public/og-image.source-hash')


asyncio.run(main())
