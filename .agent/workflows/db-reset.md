# Workflow Reset & Verifikasi Database TokoKita

Workflow ini memandu agen untuk mereset seluruh database aplikasi TokoKita ke kondisi awal bersih dan terisi data dummy seeder, serta melakukan verifikasi integritas data.

## Langkah 1: Reset Database & Seeding
Jalankan perintah Artisan berikut pada root direktori proyek untuk membersihkan semua tabel dan menjalankan seluruh seeder:

```bash
php artisan migrate:fresh --seed
```

## Langkah 2: Verifikasi Eksistensi & Jumlah Record Tabel
Jalankan script php satu-baris berikut (tinker/one-liner) untuk memeriksa ketersediaan tabel utama dan mencetak jumlah record terdaftar sebagai laporan konfirmasi keberhasilan:

```bash
php -r "
\$tables = ['users', 'shops', 'categories', 'products', 'product_variants', 'category_product', 'orders', 'order_items', 'payments', 'shipments', 'reviews', 'daily_sales_summaries'];
\$pdo = new PDO('mysql:host=127.0.0.1;dbname=tokokita', 'root', '');
foreach (\$tables as \$table) {
    try {
        \$q = \$pdo->query(\"SELECT COUNT(*) FROM \$table\");
        \$count = \$q ? \$q->fetchColumn() : 'Error';
        echo \"Tabel \$table: \$count record\n\";
    } catch (Exception \$e) {
        echo \"Tabel \$table: GAGAL DISELIDIKI\n\";
    }
}
"
```

## Langkah 3: Pelaporan
Laporkan hasil eksekusi dalam bentuk tabel ringkasan ke user. Pastikan data seeder memenuhi batas minimum:
- users >= 14
- shops >= 3
- products >= 50
- orders >= 100
