---
name: laravel-e2e-testing
description: Panduan penulisan test end-to-end (E2E) menggunakan Playwright untuk proyek TokoKita (struktur, fixture login, POM).
---

# Panduan Pengujian E2E Playwright TokoKita

Skill ini memandu agen dalam merancang, menulis, dan memelihara berkas pengujian End-to-End (E2E) menggunakan Playwright TypeScript untuk proyek TokoKita.

## 1. Struktur Folder Pengujian
Semua kode pengujian e2e diletakkan di direktori `tests/e2e/` dengan struktur sebagai berikut:

```
tests/e2e/
├── page-objects/          # File Page Object Model (POM)
│   ├── LoginPage.ts
│   └── DashboardPage.ts
├── fixtures/              # Custom test fixtures (seperti auto-auth)
│   └── auth-fixtures.ts
└── modules/               # Spesifikasi test per fitur/modul
    ├── login.spec.ts
    ├── product-crud.spec.ts
    └── checkout.spec.ts
```

*Aturan Penamaan*: Gunakan nama file `.spec.ts` dengan penulisan kebab-case yang mencerminkan nama fitur (contoh: `product-management.spec.ts`).

---

## 2. Pola Page Object Model (POM)
Selalu gunakan Page Object Model untuk membungkus elemen DOM dan interaksi halaman yang sering diulang guna mencegah duplikasi kode selector.

### Contoh POM untuk `LoginPage.ts`:
```typescript
import { Page, Locator, expect } from '@playwright/test';

export class LoginPage {
  readonly page: Page;
  readonly emailInput: Locator;
  readonly passwordInput: Locator;
  readonly loginButton: Locator;

  constructor(page: Page) {
    this.page = page;
    this.emailInput = page.locator('input[name="email"]');
    this.passwordInput = page.locator('input[name="password"]');
    this.loginButton = page.locator('button[type="submit"]');
  }

  async goto() {
    await this.page.goto('/login');
  }

  async login(email: string, pass: string) {
    await this.emailInput.fill(email);
    await this.passwordInput.fill(pass);
    await this.loginButton.click();
  }
}
```

---

## 3. Fixture Login Multi-Role (Auth Fixtures)
Gunakan custom fixtures untuk memicu autentikasi otomatis sebelum tes dijalankan berdasarkan peran pengguna (Role). Hal ini mempercepat penulisan tes per modul role.

### File `tests/e2e/fixtures/auth-fixtures.ts`:
```typescript
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
```

### Contoh Penggunaan Fixture di Spec:
```typescript
import { test, expect } from '../fixtures/auth-fixtures';

test('Seller should see product dashboard', async ({ sellerPage }) => {
  await sellerPage.goto('/seller/dashboard');
  await expect(sellerPage.locator('h2')).toContainText('Dashboard Penjual');
});
```
---

## 4. Kredensial Pengguna Seeder
Gunakan email berikut untuk memanggil data yang telah diisi oleh database seeder:
*   **Admin**: `admin@tokokita.com` (Sandi: `password`)
*   **Seller**: `seller@tokokita.com` (Sandi: `password`)
*   **Buyer**: `buyer@tokokita.com` (Sandi: `password`)
