import { test, expect } from '@playwright/test';

test.describe('Login Flow Verification', () => {
  test('should load login page successfully', async ({ page }) => {
    // Go to login page
    await page.goto('/login');

    // Assert that URL contains login
    await expect(page).toHaveURL(/login/);

    // Verify presence of core form fields
    const emailField = page.locator('input[name="email"]');
    const passwordField = page.locator('input[name="password"]');

    await expect(emailField).toBeVisible();
    await expect(passwordField).toBeVisible();
  });
});
