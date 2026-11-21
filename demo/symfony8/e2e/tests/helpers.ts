import { expect, type Page } from '@playwright/test';

/** The Symfony WebProfiler toolbar is dev chrome, never part of a README capture: hide it and wait for the platform shell. */
export async function hideToolbar(page: Page): Promise<void> {
  await page.addStyleTag({ content: '.sf-toolbar,.sf-minitoolbar,[id^="sfwdt"]{display:none!important}' });
  await expect(page.getByTestId('demo-platform')).toBeVisible();
  await page.waitForTimeout(400);
}
