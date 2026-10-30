"""Render design/og-image.html to public/og-image.png (1200 x 630) with Playwright. Danger: low, writes one file.
Run from the repo root: python3 design/make-og.py"""
import asyncio
import pathlib

from playwright.async_api import async_playwright

ROOT = pathlib.Path(__file__).resolve().parent.parent


async def main() -> None:
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page(viewport={'width': 1200, 'height': 630}, device_scale_factor=1)
        await page.goto((ROOT / 'design' / 'og-image.html').as_uri())
        await page.wait_for_timeout(1500)  # the fonts
        await page.screenshot(path=str(ROOT / 'public' / 'og-image.png'))
        await browser.close()
    print('written public/og-image.png')


asyncio.run(main())
