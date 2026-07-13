# Analisis Kebutuhan Query & Optimasi Pelaporan TokoKita

Dokumen ini menganalisis kebutuhan query database untuk menyajikan ke-10 jenis laporan di aplikasi **TokoKita** dan mendefinisikan strategi optimasi menggunakan indeks komposit dan tabel ringkasan (*Summary Table*).

---

## 1. Analisis Kebutuhan Query per Laporan

### 1. Invoice Pesanan (PDF)
*   **Query**: Memilih satu baris pesanan spesifik beserta detail pembeli, toko, item produk, status pengiriman, dan pembayaran.
*   **SQL**:
    ```sql
    SELECT * FROM orders 
    LEFT JOIN users ON orders.buyer_id = users.id 
    LEFT JOIN shops ON orders.shop_id = shops.id 
    LEFT JOIN payments ON payments.order_id = orders.id 
    LEFT JOIN shipments ON shipments.order_id = orders.id
    WHERE orders.id = ?;
    ```
*   **Optimasi**: Cukup dengan Primary Key indeks pada `orders.id` (otomatis).

### 2. Surat Jalan Pengiriman (PDF)
*   **Query**: Memilih data pengiriman barang dari pesanan aktif untuk kebutuhan kurir.
*   **SQL**:
    ```sql
    SELECT * FROM orders 
    LEFT JOIN shipments ON shipments.order_id = orders.id 
    LEFT JOIN order_items ON order_items.order_id = orders.id
    WHERE orders.id = ?;
    ```
*   **Optimasi**: Indeks asing (foreign key) `shipments.order_id` (otomatis).

### 3. Laporan Stok Produk (PDF/Excel)
*   **Query**: Menampilkan daftar produk beserta sisa stok utama dan variannya per toko.
*   **SQL**:
    ```sql
    SELECT * FROM products 
    LEFT JOIN product_variants ON product_variants.product_id = products.id
    WHERE products.shop_id = ?;
    ```
*   **Optimasi**: Indeks komposit pada `product_variants(product_id, name)` untuk penyaringan variasi produk secara efisien.

### 4. Rekap Penjualan Harian/Mingguan (Excel) & Grafik Tren Penjualan (Line Chart)
*   **Query**: Menghitung akumulasi nilai penjualan bersih dari transaksi selesai per hari/minggu untuk toko tertentu.
*   **SQL**:
    ```sql
    SELECT DATE(created_at) as date, SUM(final_amount) as total 
    FROM orders 
    WHERE shop_id = ? AND status = 'completed' 
    GROUP BY DATE(created_at);
    ```
*   **Optimasi**: Memerlukan indeks komposit pada `orders(shop_id, status, created_at)`. Namun, untuk skala data historis besar, agregasi harian lambat. Diperlukan tabel ringkasan **`daily_sales_summaries`**.

### 5. Ekspor Data Pesanan & Pembeli (Excel)
*   **Query**: Menampilkan daftar seluruh pembeli yang bertransaksi di suatu toko.
*   **SQL**:
    ```sql
    SELECT * FROM orders 
    JOIN users ON orders.buyer_id = users.id 
    WHERE orders.shop_id = ?;
    ```
*   **Optimasi**: Indeks asing `orders.shop_id`.

### 6. Produk Terlaris (Pie Chart)
*   **Query**: Mengakumulasikan jumlah kuantitas produk terjual per toko.
*   **SQL**:
    ```sql
    SELECT products.name, SUM(order_items.quantity) as qty 
    FROM order_items 
    JOIN orders ON order_items.order_id = orders.id 
    JOIN products ON order_items.product_id = products.id 
    WHERE orders.shop_id = ? AND orders.status = 'completed' 
    GROUP BY products.name 
    ORDER BY qty DESC;
    ```
*   **Optimasi**: Indeks pada `order_items.product_id` dan indeks komposit `orders(shop_id, status)`.

### 7. Performa Penjual (Bar Chart Perbandingan)
*   **Query**: Membandingkan total omzet antar toko di platform.
*   **SQL**:
    ```sql
    SELECT shop_id, SUM(final_amount) as total 
    FROM orders 
    WHERE status = 'completed' 
    GROUP BY shop_id;
    ```
*   **Optimasi**: Indeks komposit `orders(status, shop_id, final_amount)`.

### 8. Status Pesanan (Funnel/Flow Chart)
*   **Query**: Menghitung jumlah pesanan berdasarkan setiap kategori status perjalanan belanja.
*   **SQL**:
    ```sql
    SELECT status, COUNT(*) as count 
    FROM orders 
    WHERE shop_id = ? 
    GROUP BY status;
    ```
*   **Optimasi**: Indeks komposit `orders(shop_id, status)`.

### 9. Analisis Rating & Ulasan (Bar Chart)
*   **Query**: Menampilkan statistik ulasan bintang 1-5 per produk/toko.
*   **SQL**:
    ```sql
    SELECT rating, COUNT(*) as count 
    FROM reviews 
    JOIN products ON reviews.product_id = products.id 
    WHERE products.shop_id = ? 
    GROUP BY rating;
    ```
*   **Optimasi**: Indeks asing `reviews.product_id` dan indeks kolom `reviews.rating`.

---

## 2. Struktur Tabel Ringkasan (Summary Table)

Untuk mempercepat query tren penjualan harian (modul laporan 4 dan 6), kita memperkenalkan tabel **`daily_sales_summaries`**. Tabel ini menyimpan data agregat penjualan per toko setiap harinya.

### Skema Tabel `daily_sales_summaries`:
*   `id`: BIGINT (Primary Key)
*   `shop_id`: BIGINT (Foreign Key ke `shops.id`)
*   `date`: DATE (Tanggal penjualan)
*   `total_sales`: DECIMAL(15,2) (Total omzet hari tersebut)
*   `total_orders`: INT (Jumlah transaksi sukses hari tersebut)
*   **Indeks Komposit**: `(shop_id, date)` untuk pencarian instan per rentang tanggal.
