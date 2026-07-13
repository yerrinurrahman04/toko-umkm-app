import { test as base } from '@playwright/test';
import { LoginPage } from '../page-objects/LoginPage';

type AuthFixtures = {
  buyerPage: any;
  sellerPage: any;
  adminPage: any;
};

export const test = base.extend<AuthFixtures>({
  buyerPage: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('buyer@tokokita.com', 'password');
    await use(page);
  },

  sellerPage: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('seller@tokokita.com', 'password');
    await use(page);
  },

  adminPage: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('admin@tokokita.com', 'password');
    await use(page);
  },
});

export { expect } from '@playwright/test';
