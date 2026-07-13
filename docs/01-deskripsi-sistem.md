# Analisis Kebutuhan Sistem & Deskripsi Aplikasi "TokoKita"

Aplikasi **TokoKita** adalah sebuah platform marketplace e-commerce yang dirancang khusus untuk memfasilitasi Usaha Mikro, Kecil, dan Menengah (UMKM) dalam menjual produk mereka secara online, mengelola inventaris, memproses transaksi secara transparan, serta menyajikan laporan performa usaha untuk keperluan pengambilan keputusan.

---

## 1. Aktor Sistem & Hak Akses (User Roles)

Sistem mengidentifikasi tiga aktor utama dengan batasan hak akses yang jelas:

### A. Sistem Admin (Super Admin)
*   **Tujuan**: Memantau kesehatan platform secara keseluruhan, melakukan moderasi konten, dan mengelola pengguna.
*   **Fungsionalitas Utama**:
    *   Mengakses Dashboard Admin dengan ringkasan KPI sistem (Total Omzet platform, Total Pengguna, Pesanan Aktif).
    *   Manajemen akun pengguna (mengubah role user antara Admin, Seller, dan Buyer).
    *   Moderasi ulasan dan rating produk dari pembeli untuk mencegah konten spam atau tidak pantas.

### B. Pemilik UMKM (Penjual / Seller)
*   **Tujuan**: Mengelola toko pribadi, produk, harga varian, diskon, memproses pesanan masuk, dan menganalisis omzet penjualan.
*   **Fungsionalitas Utama**:
    *   Manajemen Profil Toko (Nama, jam operasional, area pengiriman, logo toko).
    *   Manajemen Produk (CRUD produk, kategori, manajemen stok utama, input varian harga & stok spesifik, serta diskon langsung).
    *   Manajemen Pesanan Masuk (Memproses status order, input nomor resi pengiriman, konfirmasi penyelesaian order).
    *   Verifikasi Pembayaran (Menerima/menolak bukti transfer yang diunggah oleh pembeli).
    *   Manajemen Promosi & Voucher (Membuat dan mematikan kode promo potongan harga).
    *   Akses Laporan internal Toko (Unduh Laporan Stok PDF/Excel, Unduh Rekap Penjualan Excel, dan visualisasi grafik tren Chart.js).

### C. Pembeli Umum (Buyer)
*   **Tujuan**: Mencari produk berkualitas dari UMKM, melakukan transaksi belanja, melacak pesanan, dan memberikan ulasan produk.
*   **Fungsionalitas Utama**:
    *   Menjelajahi katalog produk berdasarkan pencarian kata kunci dan kategori.
    *   Manajemen Keranjang Belanja (menambah produk/varian, update kuantitas, menghapus item).
    *   Penerapan kode voucher potongan harga secara dinamis.
    *   Proses Checkout (memilih kurir ekspedisi dengan estimasi waktu & tarif).
    *   Konfirmasi Pembayaran (mengunggah foto struk/bukti transfer bank manual).
    *   Pelacakan status pengiriman dan penyelesaian pesanan.
    *   Pengajuan retur barang / pengembalian.
    *   Menulis ulasan berupa rating (1-5 bintang) dan komentar tertulis.

---

## 2. Modul Pengelolaan Sistem

### A. Manajemen Produk & Inventaris
*   Mendukung penentuan kategori produk secara dinamis.
*   Mendukung varian harga (misalnya perbedaan harga berdasarkan ukuran kemasan atau warna produk).
*   Sistem pemotongan stok otomatis (stock decrement) saat pesanan dibuat, serta pengembalian stok (stock increment) saat pesanan dibatalkan.

### B. Manajemen Toko & Operasional
*   Mengatur jangkauan wilayah pengiriman (shipping area) dan jam operasional aktif toko.

### C. Manajemen Pesanan & Alur Pengiriman
*   Setiap pesanan memiliki status perjalanan terstruktur: `Pending` (menunggu bayar) -> `Paid` (pembayaran diverifikasi) -> `Processed` (sedang dikemas) -> `Shipping` (dalam pengiriman) -> `Completed` (pesanan selesai).
*   Integrasi informasi kurir pengiriman (nama ekspedisi, estimasi waktu, tarif tetap).

### D. Manajemen Pembayaran & Rekonsiliasi
*   Konfirmasi transfer bank manual dengan bukti upload berkas gambar (JPEG/PNG).
*   Rekonsiliasi data pembayaran oleh penjual sebelum pesanan diproses untuk menghindari fraud.

### E. Manajemen Voucher & Promosi
*   Pembuatan kupon promosi berbasis nilai tetap (Fixed/Nominal) maupun persentase (Percent).
*   Validasi masa aktif voucher (start & end date) serta batas minimum belanja (minimum spend).

---

## 3. Modul Laporan & Analisis (Analisis Minimal 10 Jenis)

Sistem dirancang untuk menyajikan laporan terformat PDF, spreadsheet Excel, dan grafik interaktif (Chart.js):

1.  **Cetak: Invoice Pesanan (PDF)**: Dokumen tagihan resmi berisi rincian item, diskon, biaya kirim, dan grand total untuk pembeli dan penjual.
2.  **Cetak: Surat Jalan Pengiriman (PDF)**: Dokumen fisik untuk kurir berisi alamat penerima, daftar kuantitas produk, dan tanda tangan bukti terima.
3.  **Cetak: Laporan Stok Produk (PDF)**: Dokumen ringkasan stok produk utama beserta varian detail per toko.
4.  **Cetak: Laporan Stok Produk (Excel)**: Spreadsheet detail stok produk untuk kebutuhan audit inventaris periodik.
5.  **Excel: Rekap Penjualan Harian/Mingguan/Bulanan**: Data komprehensif transaksi berstatus 'Completed' untuk analisis omzet bersih.
6.  **Excel: Ekspor Data Pesanan & Pembeli**: Ekspor daftar pembeli beserta detail alamat dan nomor kontak untuk database marketing toko.
7.  **Grafik: Tren Penjualan per Periode (Line Chart)**: Grafik perkembangan omzet kumulatif harian/mingguan.
8.  **Grafik: Produk Terlaris (Pie/Donut Chart)**: Visualisasi porsi penjualan unit produk teratas.
9.  **Grafik: Performa Penjual (Bar Chart Perbandingan)**: Komparasi penjualan produk, penyerapan stok, dan kepuasan rating per toko.
10. **Bagan: Status Pesanan (Funnel/Flowchart)**: Penelusuran visual status transaksi berjalan pembeli di halaman detail pesanan.
11. **Dashboard: Ringkasan KPI Real-Time**: Kartu metrik omzet, total pesanan, pelanggan unik, dan notifikasi warning stok kritis (< 5 unit).
12. **Grafik: Analisis Rating & Ulasan (Bar Chart)**: Komposisi rating bintang 1-5 untuk evaluasi kualitas produk dan kepuasan pelanggan.
