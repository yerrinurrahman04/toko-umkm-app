# Daftar Diagram UML Aplikasi "TokoKita"

Berkas ini memuat daftar dan penjelasan singkat mengenai pemodelan sistem menggunakan bahasa pemodelan terpadu (Unified Modeling Language - UML) yang dikembangkan untuk rancangan skripsi.

---

## 1. Use Case Diagram
*   **Nama Berkas**: [use-case.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/use-case.puml)
*   **Deskripsi**: Menggambarkan batasan sistem, aktor-aktor (`Pengguna Umum`, `Pembeli`, `Penjual`, dan `Admin`), serta hubungan keterkaitan asosiasi aktor terhadap fungsionalitas sistem (use cases) yang diimplementasikan.

---

## 2. Activity Diagrams

Berikut adalah daftar diagram alir aktivitas (Activity Diagram) yang merincikan logika proses bisnis utama di dalam sistem TokoKita:

### A. Registrasi Akun & Autentikasi (Auth)
*   **Nama Berkas**: [activity-register-auth.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-register-auth.puml)
*   **Penjelasan**: Menggambarkan alur pendaftaran akun baru berdasarkan pemilihan peran (Role) pembeli/penjual, proses validasi email unik oleh sistem, dan pengalihan dinamis ke dashboard masing-masing setelah login sukses.

### B. Keranjang Belanja & Checkout Pesanan
*   **Nama Berkas**: [activity-checkout.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-checkout.puml)
*   **Penjelasan**: Menggambarkan alur penambahan produk/varian ke dalam keranjang belanja berbasis session, proses validasi voucher promosi, penguncian stok (*stock locking*), hingga pembuatan invoice order baru.

### C. Konfirmasi & Verifikasi Pembayaran
*   **Nama Berkas**: [activity-payment-verification.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-payment-verification.puml)
*   **Penjelasan**: Menggambarkan alur pembeli mengunggah bukti bayar transfer bank manual dan proses pemeriksaan serta persetujuan/penolakan data transfer pembayaran oleh pihak penjual.

### D. Pemrosesan & Pengiriman Pesanan
*   **Nama Berkas**: [activity-order-shipping.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-order-shipping.puml)
*   **Penjelasan**: Menggambarkan alur kerja logistik penjual mulai dari pengemasan produk (*Processed*), penyerahan barang ke ekspedisi beserta input nomor resi pelacakan (*Shipping*), hingga konfirmasi penerimaan barang oleh pembeli.

### E. Penulisan Ulasan & Moderasi Ulasan
*   **Nama Berkas**: [activity-review-moderation.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/activity-review-moderation.puml)
*   **Penjelasan**: Menggambarkan alur pengiriman ulasan produk (rating + teks ulasan) oleh pembeli yang ditahan dalam status 'pending' untuk kemudian disetujui (Approved) atau dihapus (Deleted) oleh Admin sistem pada panel moderasi.

---

## 3. Sequence Diagrams

Berikut adalah daftar diagram urutan (Sequence Diagram) teknis yang memetakan aktivitas ke dalam komponen Model-View-Controller (MVC) di Laravel:

*   **Pendaftaran Akun & Autentikasi**: [sequence-register-auth.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-register-auth.puml)
    *   *Penjelasan*: Memetakan interaksi input dari pengguna dengan `RegisteredUserController` dan `AuthenticatedSessionController` untuk memvalidasi kredensial dan mencatat riwayat login di database.
*   **Keranjang Belanja & Pemesanan (Checkout)**: [sequence-checkout.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-checkout.puml)
    *   *Penjelasan*: Menjelaskan pemrosesan diskon voucher via `CartController` dan alur transaksi *stock locking* pada database produk melalui `OrderController`.
*   **Konfirmasi & Verifikasi Pembayaran**: [sequence-payment-verification.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-payment-verification.puml)
    *   *Penjelasan*: Rincian interaksi proses unggah struk bayar melalui `PaymentController` serta persetujuan status order oleh penjual.
*   **Pengiriman & Nomor Resi**: [sequence-order-shipping.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-order-shipping.puml)
    *   *Penjelasan*: Alur logistik internal pemrosesan order pada database `Order` dan `Shipment` oleh penjual dan pembeli.
*   **Ulasan & Moderasi**: [sequence-review-moderation.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/sequence-review-moderation.puml)
    *   *Penjelasan*: Menjelaskan mekanisme penyimpanan ulasan tidak aktif dan proses penyaringan ulasan oleh `AdminController`.

---

## 4. Class Diagram

*   **Nama Berkas**: [class-diagram.puml](file:///c:/laragon/www/toko-umkm-app/docs/uml/class-diagram.puml)
*   **Penjelasan**: Menyajikan visualisasi struktur data statis aplikasi TokoKita. Diagram ini mendefinisikan relasi asosiasi dan dependensi antara kelas-kelas **Model** (seperti `User`, `Shop`, `Product`, `Order`, `Voucher`) dan kelas-kelas **Controller** (seperti `OrderController`, `CartController`, `ReportController`) di dalam arsitektur Model-View-Controller (MVC) Laravel. Kelas dilengkapi dengan atribut tipe data dan definisi metode (method) operasional.
