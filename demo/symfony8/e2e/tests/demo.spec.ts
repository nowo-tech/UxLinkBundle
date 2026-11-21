import { test, expect } from '@playwright/test';

/** REQ-DEMO-013 — UX Link demo happy path: contact, share, map and download links render with real URLs. */
test.describe('UX Link demo', () => {
  test('demo page renders every link family', async ({ page }) => {
    const response = await page.goto('/');
    expect(response?.ok()).toBeTruthy();
    await expect(page.getByTestId('demo-platform')).toBeVisible();
    await expect(page.getByRole('heading', { level: 1, name: 'UX Link Bundle Demo' })).toBeVisible();

    const whatsapp = page.getByTestId('contact-whatsapp').locator('a').first();
    await expect(whatsapp).toBeVisible();
    await expect(whatsapp).toHaveAttribute('href', /wa\.me\/34600111222|whatsapp/);

    const share = page.getByTestId('share-links').locator('.ux-share-links__item a');
    await expect(share).toHaveCount(5);
    await expect(share.first()).toHaveAttribute('href', /linkedin\.com/);

    await expect(page.getByTestId('map-link').locator('a').first()).toHaveAttribute('href', /openstreetmap\.org/);

    const download = page.getByTestId('download-link').locator('a.ux-download-link__anchor');
    await expect(download).toBeVisible();
    await expect(download).toHaveAttribute('download', 'sample.pdf');
    await expect(download.locator('.ux-download-link__size')).toBeVisible();
  });
});
