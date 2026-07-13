# Workflow Pembuatan CRUD Baru (Generate CRUD)

Workflow ini memandu agen untuk membuat modul CRUD baru yang lengkap, aman, dan konsisten dengan konvensi proyek TokoKita.

---

## Langkah 1: Menerima Input Nama Entitas
Menerima input nama entitas dalam format **Singular PascalCase** (contoh: `Voucher`, `Review`, `Product`).
*   Tentukan nama tabel: plural snake_case (contoh: `vouchers`, `reviews`, `products`).
*   Tentukan nama controller: `[Entity]Controller` (contoh: `VoucherController`).

---

## Langkah 2: Membuat Database Migration
Buat file migrasi baru menggunakan Artisan:
```bash
php artisan make:migration create_[entity_plural]_table
```
*Aturan*: Gunakan tipe data presisi (contoh: `decimal(15,2)` untuk finansial), sertakan `timestamps()`, dan tambahkan index pencarian pada kolom yang sering di-filter.

---

## Langkah 3: Membuat Model Eloquent
Buat model di `app/Models/[Entity].php`. Pastikan menyertakan:
*   `protected $fillable` untuk mendata kolom mass-assignable.
*   `protected $casts` untuk casting tipe data (boolean, decimal, datetime).
*   Definisi hubungan relasi Eloquent (hasMany, belongsTo, dll.) dengan standard camelCase.

---

## Langkah 4: Membuat Form Requests untuk Validasi
Buat dua Form Request di `app/Http/Requests/`:
1.  `Store[Entity]Request` untuk validasi penyimpanan data baru.
2.  `Update[Entity]Request` untuk validasi pembaruan data lama.

```bash
php artisan make:request Store[Entity]Request
php artisan make:request Update[Entity]Request
```

---

## Langkah 5: Membuat Controller
Buat resource controller di `app/Http/Controllers/[Entity]Controller.php` yang menggunakan Form Requests:
```bash
php artisan make:controller [Entity]Controller --resource
```
*Aturan*:
*   Gunakan tipe data Form Request pada parameter `store` dan `update`.
*   Terapkan DB Transaction (`DB::transaction`) jika menyimpan data ke beberapa tabel sekaligus.

---

## Langkah 6: Daftarkan Route di `routes/web.php`
Daftarkan resource route di dalam grup middleware auth dan batasi dengan peran (Role) yang sesuai:
```php
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::resource('seller/[entity-plural]', [Entity]Controller::class)->names([
        'index' => 'seller.[entity-plural].index',
        'create' => 'seller.[entity-plural].create',
        'store' => 'seller.[entity-plural].store',
        'edit' => 'seller.[entity-plural].edit',
        'update' => 'seller.[entity-plural].update',
        'destroy' => 'seller.[entity-plural].destroy',
    ]);
});
```

---

## Langkah 7: Membuat Halaman Views CRUD (Blade + Tailwind)
Buat 4 file view di `resources/views/[entity_plural]/`:
1.  `index.blade.php`: Menampilkan tabel list data lengkap dengan pagination.
2.  `create.blade.php`: Formulir input data baru dilengkapi proteksi `@csrf`.
3.  `edit.blade.php`: Formulir edit data lama dilengkapi proteksi `@csrf` dan `@method('PATCH')`.
4.  `show.blade.php`: Halaman detail data tunggal.

*Aturan Desain*:
*   Extend layout master: `<x-app-layout>`.
*   Gunakan styling class premium (`glass-card`, `card-hover`, dll.) agar seragam.
