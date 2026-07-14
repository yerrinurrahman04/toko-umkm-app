import { test, expect } from '../fixtures/auth-fixtures';

test.describe('Admin Seller Performance Page Verification', () => {
  test('should load seller performance page with chart and table successfully', async ({ adminPage }) => {
    // 1. Navigate to admin dashboard
    await adminPage.goto('/admin/dashboard');

    // 2. Click "Performa Penjual" link in the sidebar
    await adminPage.click('text=Performa Penjual');
    await expect(adminPage).toHaveURL(/\/admin\/seller-performance/);

    // 3. Verify page content
    await expect(adminPage.locator('text=Performa Penjual UMKM (3 Bulan Terakhir)')).toBeVisible();
    
    // 4. Verify chart canvas exists
    const chartCanvas = adminPage.locator('#sellerRevenueChart');
    await expect(chartCanvas).toBeVisible();

    // 5. Verify performance summary table is rendered
    await expect(adminPage.locator('text=Tabel Ringkasan Performa')).toBeVisible();
    const tableRows = adminPage.locator('tbody tr');
    expect(await tableRows.count()).toBeGreaterThan(0);
  });
});
