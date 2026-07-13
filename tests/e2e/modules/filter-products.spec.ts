import { test, expect } from '@playwright/test';

test.describe('Product Catalog Filter and Search Verification', () => {
  test('should filter catalog by name, category, and price range successfully', async ({ page }) => {
    // 1. Go to homepage
    await page.goto('/');
    await expect(page).toHaveURL(/http:\/\/127.0.0.1:8000/);

    // 2. Test Name Search inside the filter card form
    const searchInput = page.locator('.glass-card input[name="search"]').first();
    await searchInput.fill('Beras');
    await page.click('button:has-text("Terapkan Filter")');
    
    // Assert only Beras related items are visible
    const productGrid = page.locator('.grid').nth(2); // Since we added another grid block or filter container, nth(2) or target specific class
    await expect(page.locator('body')).toContainText('Beras');
    await expect(page.locator('body')).not.toContainText('Mouse Wireless');

    // Reset filter
    await page.click('text=Reset');

    // 3. Test Category Filter
    await page.click('text=Makanan Pokok');
    // Verify category query param in url
    await expect(page).toHaveURL(/category=makanan-pokok/);

    // 4. Test Price Filter (min=30000, max=100000)
    await page.fill('.glass-card input[name="min_price"]', '30000');
    await page.fill('.glass-card input[name="max_price"]', '100000');
    await page.click('button:has-text("Terapkan Filter")');

    // Inspect price outputs on screen
    const productPrices = await page.locator('span.font-extrabold').allTextContents();
    for (const rawPrice of productPrices) {
      if (rawPrice.includes('Rp')) {
        const priceVal = parseInt(rawPrice.replace(/[^\d]/g, ''), 10);
        // Exclude footer text or other elements
        if (priceVal > 0 && priceVal < 5000000) {
          expect(priceVal).toBeGreaterThanOrEqual(30000);
          expect(priceVal).toBeLessThanOrEqual(100000);
        }
      }
    }
  });
});
