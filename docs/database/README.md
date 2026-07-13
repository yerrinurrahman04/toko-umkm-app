# Deskripsi Relasi Basis Data (Database ERD) TokoKita

Berkas ini memuat penjelasan hubungan keterkaitan (relationships) antar tabel basis data MySQL pada aplikasi TokoKita berdasarkan standar ORM Eloquent Laravel.

---

## 1. Tabel Utama & Relasi Identitas

### users ➔ shops (1-to-1 / hasOne)
*   Setiap pengguna (`users`) dengan peran `seller` dapat memiliki tepat satu profil toko (`shops`) melalui foreign key `shops.user_id` yang merujuk pada `users.id`.

### shops ➔ products (1-to-many / hasMany)
*   Satu profil toko (`shops`) dapat mempublikasikan banyak produk (`products`) melalui foreign key `products.shop_id`. Jika toko dihapus, semua produknya ikut terhapus (*cascade*).

### categories ➔ products (Many-to-Many / belongsToMany)
*   Satu produk dapat termasuk ke dalam beberapa kategori, dan satu kategori dapat memiliki banyak produk. Hubungan ini diatur oleh tabel pivot **`category_product`** yang menyimpan pasangan `product_id` dan `category_id`.

---

## 2. Tabel Produk & Varian Detail

### products ➔ product_variants (1-to-many / hasMany)
*   Satu produk utama (`products`) dapat memiliki banyak varian harga dan stok (`product_variants`) seperti ukuran, warna, atau kemasan yang berbeda melalui foreign key `product_variants.product_id`.

---

## 3. Tabel Transaksi & Pengiriman

### users (buyer) ➔ orders (1-to-many / hasMany)
*   Seorang pengguna (`users`) dengan peran `buyer` dapat melakukan banyak pesanan (`orders`) melalui foreign key `orders.buyer_id`.

### shops ➔ orders (1-to-many / hasMany)
*   Setiap toko (`shops`) menerima banyak pesanan masuk (`orders`) melalui foreign key `orders.shop_id`.

### vouchers ➔ orders (1-to-many / nullable)
*   Satu kupon promo (`vouchers`) dapat digunakan di banyak transaksi (`orders`) melalui foreign key `orders.voucher_id`. Relasi bersifat opsional (*nullable*).

### orders ➔ order_items (1-to-many / hasMany)
*   Satu pesanan (`orders`) berisi satu atau lebih item produk yang dibeli (`order_items`) melalui foreign key `order_items.order_id`.

### order_items ➔ products & product_variants (belongsTo)
*   Setiap baris item belanja (`order_items`) merujuk pada satu produk utama (`product_id`) dan satu pilihan varian opsional (`product_variant_id`).

### orders ➔ payments (1-to-1 / hasOne)
*   Satu pesanan (`orders`) memiliki tepat satu data pembayaran konfirmasi transfer (`payments`) melalui foreign key `payments.order_id`.

### orders ➔ shipments (1-to-1 / hasOne)
*   Satu pesanan (`orders`) memiliki tepat satu berkas informasi kurir dan nomor resi pengiriman (`shipments`) melalui foreign key `shipments.order_id`.

---

## 4. Tabel Ulasan Konten

### products ➔ reviews (1-to-many / hasMany)
*   Satu produk (`products`) menerima banyak rating ulasan dari pembeli (`reviews`) melalui foreign key `reviews.product_id`.

### users (buyer) ➔ reviews (1-to-many)
*   Satu pembeli (`users`) dapat menulis banyak ulasan (`reviews`) melalui foreign key `reviews.buyer_id`.

### order_items ➔ reviews (1-to-1)
*   Satu ulasan (`reviews`) merujuk secara unik pada satu baris transaksi item belanja (`reviews.order_item_id`) untuk memastikan pembeli hanya dapat memberikan ulasan satu kali per item pesanan selesai.
