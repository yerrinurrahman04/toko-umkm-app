---
name: laravel-setup
description: Panduan instalasi dan konfigurasi dasar proyek Laravel 10 untuk TokoKita (Auth Breeze, PDF, Excel, & Database).
---

# Panduan Setup Proyek Laravel 10 (TokoKita)

Skill ini memberikan panduan langkah demi langkah bagi agen untuk mempersiapkan lingkungan pengembangan, melakukan instalasi package wajib, dan mengonfigurasi autentikasi Breeze pada proyek TokoKita.

## 1. Persyaratan Lingkungan (Environment Requirements)
*   **Versi PHP**: Minimum PHP 8.1 ke atas (sangat disarankan PHP 8.2+).
*   **Versi Laravel**: Laravel 10.x.
*   **Database**: MySQL 5.7+ atau MariaDB 10.3+.

---

## 2. Instalasi Package Wajib (Required Packages)
Aplikasi TokoKita menggunakan beberapa library tambahan untuk menangani ekspor laporan dan cetak dokumen:
1.  **Laravel Breeze** (Scaffolding Autentikasi):
    ```bash
    composer require laravel/breeze --dev
    ```
2.  **Barryvdh DomPDF** (Cetak Invoice & Surat Jalan PDF):
    ```bash
    composer require barryvdh/laravel-dompdf
    ```
3.  **Maatwebsite Excel** (Ekspor Laporan Penjualan Excel):
    ```bash
    composer require maatwebsite/excel
    ```

---

## 3. Konfigurasi Database & .env
Salin berkas `.env.example` menjadi `.env` lalu sesuaikan baris koneksi basis data dengan kredensial server lokal Anda:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokokita
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Setup Autentikasi Laravel Breeze (Blade + Tailwind)
Ikuti langkah berikut untuk menginisialisasi scaffolding autentikasi:

### Langkah A: Jalankan Generator Breeze
Gunakan perintah interaktif Breeze untuk memilih stack **Blade** (default):
```bash
php artisan breeze:install blade
```
*Catatan: Pilih opsi penulisan default (Dark Mode: No, Pest: No jika ditanya).*

### Langkah B: Instal & Compile Node Modules
Kompilasi file assets Tailwind CSS menggunakan bundler Vite:
```bash
npm install
npm run build
```

### Langkah C: Jalankan Migrasi Awal
Jalankan migrasi awal untuk mempersiapkan tabel `users` bawaan Breeze:
```bash
php artisan migrate
```
*   Jika database `tokokita` belum ada, ketik **Yes** saat sistem menawarkan pembuatan database otomatis.
*   Setelah langkah ini selesai, jalankan seeder dengan `php artisan db:seed` jika ingin mengisi data awal UMKM.
