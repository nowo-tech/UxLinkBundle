import { test, expect } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';
import { waitForToolbar } from './helpers';

/** REQ-DEMO-013 — README screenshot (captured as PNG, converted to WebP by scripts/to-webp.mjs). */
const outDir = process.env.SCREENSHOT_DIR
  ? resolve(process.env.SCREENSHOT_DIR)
  : resolve(__dirname, '../../../../docs/images/demo');

test.beforeAll(() => {
  mkdirSync(outDir, { recursive: true });
});

test.describe('UX Link screenshots', () => {
  test.use({ viewport: { width: 1280, height: 1000 } });

  test('link-components — contact, share, map and download links', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('#download-link a.ux-download-link__anchor')).toBeVisible();
    await waitForToolbar(page);
    await page.screenshot({ path: resolve(outDir, 'link-components.png') });
  });
});
