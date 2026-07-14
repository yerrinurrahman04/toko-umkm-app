import { test, expect } from '@playwright/test';
import { LoginPage } from '../page-objects/LoginPage';

test.describe('Admin Category CRUD Operations', () => {
  test('should create, read, edit, and delete a category successfully', async ({ page }) => {
    const loginPage = new LoginPage(page);

    // 1. Login as Admin
    await loginPage.goto();
    await loginPage.login('admin@tokokita.com', 'password');
    await page.goto('/admin/dashboard');

    // 2. Navigate to Category Index
    await page.click('text=Kelola Kategori');
    await expect(page).toHaveURL(/\/admin\/categories/);

    // 3. Create Category
    await page.click('text=+ Tambah Kategori');
    await expect(page).toHaveURL(/\/admin\/categories\/create/);

    const testCatName = 'Seni Pahat E2E ' + Math.floor(Math.random() * 10000);
    const testCatDesc = 'Koleksi seni ukir pahat kayu tepercaya.';

    await page.fill('input#name', testCatName);
    await page.fill('textarea#description', testCatDesc);
    await page.click('button[type="submit"]');

    // Check success flash message and new item in list
    await expect(page.locator('body')).toContainText('Kategori baru berhasil dibuat!');
    await expect(page.locator('body')).toContainText(testCatName);

    // 4. Read Category Detail
    await page.click(`text=${testCatName}`);
    await expect(page.locator('body')).toContainText(testCatDesc);

    // 5. Edit Category
    await page.click('text=Edit Kategori');
    const updatedName = testCatName + ' Updated';
    const updatedDesc = testCatDesc + ' Updated desc.';
    await page.fill('input#name', updatedName);
    await page.fill('textarea#description', updatedDesc);
    await page.click('button[type="submit"]');

    await expect(page.locator('body')).toContainText('Kategori berhasil diperbarui!');
    await expect(page.locator('body')).toContainText(updatedName);

    // 6. Delete Category
    page.on('dialog', async dialog => {
      expect(dialog.message()).toContain('Apakah Anda yakin ingin menghapus kategori ini?');
      await dialog.accept();
    });

    const categoryRow = page.locator('tr', { hasText: updatedName });
    await categoryRow.locator('button:has-text("Hapus")').click();

    await expect(page.locator('body')).toContainText('Kategori berhasil dihapus!');
    await expect(page.locator('body')).not.toContainText(updatedName);
  });
});
