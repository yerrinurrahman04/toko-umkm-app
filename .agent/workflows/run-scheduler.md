# Workflow Pengujian Scheduler & Summary Command

Workflow ini memandu agen untuk melakukan pengujian manual terhadap Artisan Command `php artisan summary` serta penjadwalan (Scheduler) di Laravel.

## Langkah 1: Pengujian Manual Command Summary
Jalankan Artisan Command secara langsung untuk memverifikasi data terhitung dan disimpan ke dalam database.

1. **Jalankan untuk Tanggal Kemarin (Default)**:
   ```bash
   php artisan summary
   ```

2. **Jalankan untuk Tanggal Spesifik**:
   Gunakan parameter opsional untuk tanggal tertentu (misalnya, tanggal input order selesai):
   ```bash
   php artisan summary 2026-07-14
   ```

## Langkah 2: Pengujian Scheduler (Laravel Scheduler)
Untuk menguji apakah pendaftaran command di `app/Console/Kernel.php` sudah benar, jalankan perintah scheduler test:

1. **Jalankan schedule secara instan (one-time)**:
   Perintah ini akan menjalankan semua task scheduler yang siap dieksekusi:
   ```bash
   php artisan schedule:run
   ```

2. **Jalankan scheduler worker (continuous loop)**:
   Di lingkungan lokal, Anda dapat memicu scheduler berjalan di background setiap menit:
   ```bash
   php artisan schedule:work
   ```

## Langkah 3: Verifikasi Hasil ke Database
Untuk memeriksa data yang berhasil di-summary, jalankan query berikut via php tinker:

```bash
php artisan tinker --execute="print_r(\App\Models\DailySalesSummary::latest()->take(5)->get()->toArray())"
```

Pastikan kolom `total_sales` dan `total_orders` telah terisi sesuai data pesanan yang bersatus `completed` pada tanggal tersebut.
