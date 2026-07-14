import { test, expect } from '../fixtures/auth-fixtures';
import * as path from 'path';
import * as fs from 'fs';

test.describe('Printable Excel Report Features Verification', () => {
  // Ensure output directory exists
  const outputDir = path.join(__dirname, '../../../docs/testing/spreadsheet-output');
  test.beforeAll(() => {
    if (!fs.existsSync(outputDir)) {
      fs.mkdirSync(outputDir, { recursive: true });
    }
  });

  test('should download Seller Stock Excel successfully', async ({ sellerPage }) => {
    await sellerPage.goto('/seller/dashboard');

    // Trigger and wait for download
    const downloadPromise = sellerPage.waitForEvent('download');
    await sellerPage.click('text=Ekspor Stok (Excel)');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^laporan-stok-.*\.xlsx$/i);

    // Save download
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });

  test('should download Seller Sales Recap Excel successfully', async ({ sellerPage }) => {
    await sellerPage.goto('/seller/dashboard');

    // Trigger and wait for download
    const downloadPromise = sellerPage.waitForEvent('download');
    await sellerPage.click('text=Rekap Penjualan (Excel)');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^rekap-penjualan-.*\.xlsx$/i);

    // Save download
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });

  test('should download Admin Orders & Buyers Excel successfully', async ({ adminPage }) => {
    await adminPage.goto('/admin/dashboard');

    // Trigger and wait for download
    const downloadPromise = adminPage.waitForEvent('download');
    await adminPage.click('text=Ekspor Pesanan & Pembeli Excel');
    const download = await downloadPromise;

    // Verify pattern and extension
    const filename = download.suggestedFilename();
    expect(filename).toMatch(/^ekspor-pesanan-pembeli-.*\.xlsx$/i);

    // Save download
    const targetPath = path.join(outputDir, filename);
    await download.saveAs(targetPath);
    expect(fs.existsSync(targetPath)).toBe(true);
  });
});
