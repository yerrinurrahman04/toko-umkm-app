---
name: laravel-migration
description: Panduan pembuatan berkas migrasi database Laravel 10 yang konsisten dengan ERD DBML dan standar industri.
---

# Panduan Pembuatan Migrasi Database Laravel 10

Skill ini memandu agen dalam menyusun berkas migrasi database MySQL/MariaDB pada Laravel 10 agar konsisten dengan struktur Entity Relationship Diagram (ERD).

## 1. Urutan Eksekusi Migrasi (Migration Ordering)
Saat membuat tabel, urutan berkas migrasi sangat krusial untuk mencegah kegagalan foreign key constraint. Ikuti aturan penamaan timestamp berkas:
1.  **Tabel Master/Independen**: Tabel tanpa foreign key (contoh: `users`, `categories`, `vouchers`) harus dibuat paling awal.
2.  **Tabel Dependen**: Tabel yang bergantung pada tabel lain (contoh: `shops` bergantung pada `users`, `products` bergantung pada `shops`) dibuat setelahnya.
3.  **Tabel Pivot & Transaksional**: Tabel persimpangan dan transaksi (`category_product`, `orders`, `order_items`, `payments`, `shipments`, `reviews`) harus diletakkan paling akhir.

---

## 2. Pemetaan Tipe Data MySQL di Laravel
Pastikan penggunaan tipe data yang tepat dan konsisten dengan database MySQL:
*   **Primary Key**: Selalu gunakan `$table->id()` (menghasilkan BIGINT UNSIGNED AUTO_INCREMENT).
*   **Foreign Key**: Selalu gunakan `$table->foreignId('column_name')->constrained('table_name')->onDelete('cascade')`. Kolom ini otomatis bertipe BIGINT UNSIGNED.
*   **Mata Uang/Keuangan**: Gunakan `$table->decimal('column_name', 15, 2)` (menghindari floating point error).
*   **Teks Panjang**: Gunakan `$table->text()` atau `$table->longText()`.
*   **Pilihan Terbatas**: Gunakan enum `$table->enum('column_name', ['value1', 'value2'])` atau string biasa dengan check constraint.
*   **Boolean**: Gunakan `$table->boolean('column_name')->default(false)`.

---

## 3. Pola Contoh Migrasi

### A. Tabel Transaksi (Transaksi Kompleks / `orders`)
Memerlukan presisi keuangan, timestamps, soft deletes, dan index pencarian pada kolom unik.

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
    $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null');
    $table->string('order_number')->unique();
    $table->decimal('total_amount', 15, 2);
    $table->decimal('discount_amount', 15, 2)->default(0.00);
    $table->decimal('shipping_cost', 15, 2)->default(0.00);
    $table->decimal('final_amount', 15, 2);
    $table->string('status')->default('pending');
    $table->text('shipping_address');
    $table->string('notes')->nullable();
    $table->softDeletes(); // Mengaktifkan Soft Delete
    $table->timestamps();  // created_at & updated_at
    
    // Index tambahan untuk optimasi query pencarian pesanan toko
    $table->index(['shop_id', 'status']);
});
```

### B. Tabel Pivot (Banyak-ke-Banyak / `category_product`)
Menghubungkan dua tabel dengan composite primary key dan relasi cascade.

```php
Schema::create('category_product', function (Blueprint $table) {
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    
    // Composite Primary Key (Mencegah duplikasi entri)
    $table->primary(['category_id', 'product_id']);
});
```

### C. Tabel dengan Index Komposit (`product_variants`)
Optimasi query untuk varian produk spesifik.

```php
Schema::create('product_variants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->string('name');
    $table->decimal('price', 15, 2);
    $table->integer('stock')->default(0);
    $table->timestamps();

    // Composite Index untuk mempercepat query produk berdasarkan nama varian
    $table->index(['product_id', 'name']);
});
```
