import { test, expect } from '@playwright/test';
import { LoginPage } from '../page-objects/LoginPage';

test.describe('Buyer Product Review and Admin Moderation Flow', () => {
  test('should allow buyer to review completed order, admin to moderate, and display on product page', async ({ browser }) => {
    // 1. Log in as Buyer and find a completed order that does NOT have a review yet
    const buyerContext = await browser.newContext();
    const buyerPage = await buyerContext.newPage();
    const buyerLogin = new LoginPage(buyerPage);
    
    await buyerLogin.goto();
    await buyerLogin.login('buyer@tokokita.com', 'password');
    
    await buyerPage.goto('/buyer/dashboard');
    await expect(buyerPage).toHaveURL(/\/buyer\/dashboard/);

    // Get all rows representing a completed ("Selesai") order
    const completedOrderRows = buyerPage.locator('tr', { hasText: 'Selesai' });
    const count = await completedOrderRows.count();
    
    let reviewFormFound = false;
    let productName = '';
    let commentText = 'Ulasan E2E Kualitas Luar Biasa ' + Math.floor(Math.random() * 10000);

    for (let i = 0; i < count; i++) {
      // Click Detail on the i-th completed order
      const row = completedOrderRows.nth(i);
      await row.locator('text=Detail').click();
      await buyerPage.waitForLoadState('networkidle');

      // Check if the review form is present
      const reviewForm = buyerPage.locator('form[action*="reviews"]').first();
      if (await reviewForm.isVisible()) {
        reviewFormFound = true;
        productName = await buyerPage.locator('strong.text-slate-800').first().innerText();
        
        // Fill the review form
        await reviewForm.locator('select[name="rating"]').selectOption('5');
        await reviewForm.locator('textarea[name="comment"]').fill(commentText);
        await reviewForm.locator('button[type="submit"]').click();
        
        // Verify flash message
        await expect(buyerPage.locator('body')).toContainText('Ulasan berhasil dikirim! Menunggu moderasi admin.');
        break;
      } else {
        // Go back to the dashboard to check the next one
        await buyerPage.goto('/buyer/dashboard');
      }
    }

    expect(reviewFormFound).toBe(true);
    await buyerContext.close();

    // 2. Log in as Admin to moderate the review
    const adminContext = await browser.newContext();
    const adminPage = await adminContext.newPage();
    const adminLogin = new LoginPage(adminPage);
    
    await adminLogin.goto();
    await adminLogin.login('admin@tokokita.com', 'password');
    
    await adminPage.goto('/admin/reviews');
    await expect(adminPage).toHaveURL(/\/admin\/reviews/);

    const reviewRow = adminPage.locator('tr', { hasText: commentText });
    await expect(reviewRow).toBeVisible();

    await reviewRow.locator('button:has-text("Approve")').click();
    await expect(adminPage.locator('body')).toContainText('Ulasan disetujui dan diterbitkan.');
    await adminContext.close();

    // 3. Verify public visibility on the product page
    const guestContext = await browser.newContext();
    const guestPage = await guestContext.newPage();
    
    await guestPage.goto('/');
    // Search for the product to bypass pagination limits
    const searchInput = guestPage.locator('input[name="search"]').first();
    await searchInput.fill(productName);
    await searchInput.press('Enter');
    
    await guestPage.click(`text=${productName}`);
    await expect(guestPage.locator('body')).toContainText(commentText);
    await guestContext.close();
  });
});
