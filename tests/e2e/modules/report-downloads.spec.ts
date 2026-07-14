import { test, expect } from '../fixtures/auth-fixtures';
import * as path from 'path';
import * as fs from 'fs';

test.describe('Printable PDF Report Features Verification', () => {
  // Ensure the output directory exists
  const outputDir = path.join(__dirname, '../../../docs/testing/pdf-output');
  test.beforeAll(() => {
    if (!fs.existsSync(outputDir)) {
      fs.mkdirSync(outputDir, { recursive: true });
    }
  });

  test('should download Buyer Invoice PDF successfully', async ({ buyerPage }) => {
    // Navigate to buyer dashboard
    await buyerPage.goto('/buyer/dashboard');
    
    // Go to the detail of the first order (e.g. Selesai order)
    const detailBtn = buyerPage.locator('text=Detail').first();
    await detailBtn.click();
    await buyerPage.waitForLoadState('networkidle');

    // Trigger and wait for download
    const downloadPromise = buyerPage.waitForEvent('download');
    await buyerPage.click('text=Cetak Invoice (PDF)');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^invoice-.*\.pdf$/i);

    // Save download to output folder
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });

  test('should download Seller Surat Jalan PDF successfully', async ({ sellerPage }) => {
    // Navigate to seller dashboard and click orders page
    await sellerPage.goto('/seller/orders');
    
    const orderLink = sellerPage.locator('a[href*="/orders/"]').first();
    await orderLink.click();
    await sellerPage.waitForLoadState('networkidle');

    // Trigger and wait for download
    const downloadPromise = sellerPage.waitForEvent('download');
    await sellerPage.click('text=Surat Jalan (PDF)');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^surat-jalan-.*\.pdf$/i);

    // Save download
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });

  test('should download Admin System Stock PDF successfully', async ({ adminPage }) => {
    // Navigate to admin dashboard
    await adminPage.goto('/admin/dashboard');

    // Trigger and wait for download
    const downloadPromise = adminPage.waitForEvent('download');
    await adminPage.click('text=Laporan Stok Sistem (PDF)');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^laporan-stok-.*\.pdf$/i);

    // Save download
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });
});
