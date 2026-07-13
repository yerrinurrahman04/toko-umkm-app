# Workflow Pengisian Tabel Ringkasan (Daily Sales Summaries)

Workflow ini digunakan untuk memicu proses kalkulasi ulang (*recalculation*) dan pengisian data agregat penjualan harian pada tabel `daily_sales_summaries` berdasarkan data riwayat transaksi riil dari tabel `orders` yang berstatus `completed`.

---

## 1. Query SQL Agregasi Utama
Query berikut melakukan agregasi data total omzet dan jumlah transaksi sukses per toko per hari, lalu memasukkannya ke tabel `daily_sales_summaries` menggunakan metode *Upsert* untuk menghindari duplikasi data.

```sql
INSERT INTO daily_sales_summaries (shop_id, date, total_sales, total_orders, created_at, updated_at)
SELECT 
    shop_id, 
    DATE(created_at) AS date, 
    SUM(final_amount) AS total_sales, 
    COUNT(*) AS total_orders, 
    NOW() AS created_at, 
    NOW() AS updated_at
FROM orders
WHERE status = 'completed'
GROUP BY shop_id, DATE(created_at)
ON DUPLICATE KEY UPDATE 
    total_sales = VALUES(total_sales),
    total_orders = VALUES(total_orders),
    updated_at = NOW();
```

---

## 2. Cara Menjalankan Menggunakan PHP / Laravel Tinker

Anda dapat memicu query di atas melalui terminal menggunakan **Laravel Tinker** atau **PHP One-Liner CLI**:

### Metode A: Menggunakan Laravel Tinker
Jalankan `php artisan tinker`, lalu paste kode berikut:

```php
DB::statement("
    INSERT INTO daily_sales_summaries (shop_id, date, total_sales, total_orders, created_at, updated_at)
    SELECT 
        shop_id, 
        DATE(created_at) AS date, 
        SUM(final_amount) AS total_sales, 
        COUNT(*) AS total_orders, 
        NOW(), 
        NOW()
    FROM orders
    WHERE status = 'completed'
    GROUP BY shop_id, DATE(created_at)
    ON DUPLICATE KEY UPDATE 
        total_sales = VALUES(total_sales),
        total_orders = VALUES(total_orders),
        updated_at = NOW();
");
```

### Metode B: Menggunakan PHP CLI Direct Script
Jalankan perintah shell satu baris berikut untuk melakukan pengisian/sinkronisasi instan:

```bash
php -r "
try {
    \$pdo = new PDO('mysql:host=127.0.0.1;dbname=tokokita', 'root', '');
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$sql = \"
        INSERT INTO daily_sales_summaries (shop_id, date, total_sales, total_orders, created_at, updated_at)
        SELECT 
            shop_id, 
            DATE(created_at) AS date, 
            SUM(final_amount) AS total_sales, 
            COUNT(*) AS total_orders, 
            NOW(), 
            NOW()
        FROM orders
        WHERE status = 'completed'
        GROUP BY shop_id, DATE(created_at)
        ON DUPLICATE KEY UPDATE 
            total_sales = VALUES(total_sales),
            total_orders = VALUES(total_orders),
            updated_at = NOW();
    \";
    \$affected = \$pdo->exec(\$sql);
    echo \"Sinkronisasi selesai! Berhasil memperbarui/mengisi data ringkasan harian.\n\";
} catch (Exception \$e) {
    echo \"Gagal sinkronisasi: \" . \$e->getMessage() . \"\n\";
}
"
```
