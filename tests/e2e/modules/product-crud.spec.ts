import { test, expect } from '../fixtures/auth-fixtures';

test.describe('Seller Product CRUD Operations', () => {
  test('should create, edit, and delete a product successfully', async ({ sellerPage }) => {
    // 1. Go to Seller Products List
    await sellerPage.goto('/seller/products');
    await expect(sellerPage).toHaveURL(/\/seller\/products/);

    // 2. Navigate to Create Product Page
    await sellerPage.click('text=Tambah Produk Baru');
    await expect(sellerPage).toHaveURL(/\/seller\/products\/create/);

    // 3. Fill in Form
    const productName = 'Produk Test E2E ' + Math.floor(Math.random() * 10000);
    await sellerPage.fill('input#name', productName);
    await sellerPage.selectOption('select#category_id', { index: 0 });
    await sellerPage.fill('input#price', '150000');
    await sellerPage.fill('input#stock', '35');
    await sellerPage.fill('input#discount_percentage', '5');
    await sellerPage.fill('textarea#description', 'Deskripsi produk hasil uji coba otomatis end-to-end dengan Playwright.');
    
    // Submit
    await sellerPage.click('button[type="submit"]');

    // 4. Verify Creation in List
    await expect(sellerPage).toHaveURL(/\/seller\/products/);
    await expect(sellerPage.locator('table')).toContainText(productName);

    // 5. Navigate to Edit Page
    // Find the row containing our product, then click its Edit button
    const productRow = sellerPage.locator('tr', { hasText: productName });
    await productRow.locator('text=Edit & Varian').click();
    await expect(sellerPage).toHaveURL(/\/seller\/products\/\d+\/edit/);

    // Modify Name & Price
    const updatedName = productName + ' Updated';
    await sellerPage.fill('input#name', updatedName);
    await sellerPage.fill('input#price', '175000');

    // Submit edit
    await sellerPage.click('button[type="submit"]');

    // 6. Verify Update in List
    await expect(sellerPage).toHaveURL(/\/seller\/products/);
    await expect(sellerPage.locator('table')).toContainText(updatedName);

    // 7. Delete the Product
    // We register dialog handler because of confirm('Hapus produk ini?')
    sellerPage.on('dialog', async (dialog) => {
      expect(dialog.message()).toContain('Hapus produk ini');
      await dialog.accept();
    });

    const updatedRow = sellerPage.locator('tr', { hasText: updatedName });
    await updatedRow.locator('button:has-text("Hapus")').click();

    // Verify product name is gone from list
    await expect(sellerPage.locator('table')).not.toContainText(updatedName);
  });
});
