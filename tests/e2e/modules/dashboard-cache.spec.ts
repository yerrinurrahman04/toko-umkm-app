import { test, expect } from '../fixtures/auth-fixtures';

test.describe('Dashboard Cache Refresh Operations', () => {
  test('should clear cache and refresh Admin dashboard successfully', async ({ adminPage }) => {
    await adminPage.goto('/admin/dashboard');

    // Click 'Refresh Data' button
    await adminPage.click('text=Refresh Data');

    // Should redirect back to dashboard and be fully loaded
    await expect(adminPage).toHaveURL(/\/admin\/dashboard$/);
    await expect(adminPage.locator('text=Omzet Sistem')).toBeVisible();
  });

  test('should clear cache and refresh Seller dashboard successfully', async ({ sellerPage }) => {
    await sellerPage.goto('/seller/dashboard');

    // Click 'Refresh Data' button
    await sellerPage.click('text=Refresh Data');

    // Should redirect back to dashboard and be fully loaded
    await expect(sellerPage).toHaveURL(/\/seller\/dashboard$/);
    await expect(sellerPage.locator('text=Total Omzet')).toBeVisible();
  });
});
