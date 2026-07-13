import { test, expect } from '../fixtures/auth-fixtures';

test.describe('Seller Product Form Real-time Validation', () => {
  test('should validate name length, positive price, and integer stock real-time', async ({ sellerPage }) => {
    // 1. Go to Create Product Page
    await sellerPage.goto('/seller/products/create');
    await expect(sellerPage).toHaveURL(/\/seller\/products\/create/);

    const submitBtn = sellerPage.locator('button[type="submit"]');

    // 2. Validate Name (should show error for length < 5)
    await sellerPage.fill('input#name', 'Madu');
    const nameError = sellerPage.locator('text=Nama produk minimal 5 karakter.');
    await expect(nameError).toBeVisible();
    await expect(submitBtn).toBeDisabled();

    // Fix name
    await sellerPage.fill('input#name', 'Madu Hutan Murni');
    await expect(nameError).toBeHidden();

    // 3. Validate Price (should show error for negative values)
    await sellerPage.fill('input#price', '-15000');
    const priceError = sellerPage.locator('text=Harga tidak boleh negatif.');
    await expect(priceError).toBeVisible();
    await expect(submitBtn).toBeDisabled();

    // Fix price
    await sellerPage.fill('input#price', '150000');
    await expect(priceError).toBeHidden();

    // 4. Validate Stock (should show error for decimal value)
    await sellerPage.fill('input#stock', '12.5');
    const stockError = sellerPage.locator('text=Stok harus berupa angka bulat.');
    await expect(stockError).toBeVisible();
    await expect(submitBtn).toBeDisabled();

    // Fix stock
    await sellerPage.fill('input#stock', '12');
    await expect(stockError).toBeHidden();

    // Submit button should be active now
    await expect(submitBtn).toBeEnabled();
  });
});
