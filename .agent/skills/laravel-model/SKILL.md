---
name: laravel-model
description: Dokumentasi konvensi dan pola desain Model Eloquent untuk proyek TokoKita (fillable, casts, relationships, accessors).
---

# Konvensi & Pola Desain Model Eloquent TokoKita

Skill ini mendokumentasikan konvensi penulisan dan pola desain Model Eloquent di proyek Laravel 10 untuk TokoKita guna menjaga konsistensi arsitektur MVC.

## 1. Aturan Penamaan & Struktur Berkas
*   **Nama Model**: Selalu gunakan kata benda tunggal berbentuk **PascalCase** (contoh: `User`, `OrderItem`, `DailySalesSummary`).
*   **Lokasi Berkas**: Disimpan di direktori `app/Models/`.
*   **Namespace**: `namespace App\Models;`.

---

## 2. Definisi Properti Utama

### A. Mass Assignment Protection (`$fillable`)
Selalu definisikan kolom database yang diizinkan untuk diisi secara massal. Hindari penggunaan `$guarded = []`.
```php
protected $fillable = [
    'shop_id',
    'name',
    'price',
    'stock',
];
```

### B. Casting Tipe Data (`$casts`)
Gunakan properti `$casts` untuk mengubah tipe data mentah database ke tipe data PHP/Carbon secara otomatis:
```php
protected $casts = [
    'date' => 'date',
    'total_sales' => 'decimal:2',
    'is_active' => 'boolean',
    'email_verified_at' => 'datetime',
];
```

### C. Custom Append Attributes (`$appends`)
Jika model memerlukan atribut tambahan di luar kolom tabel (contoh: perhitungan dinamis), daftarkan di `$appends`:
```php
protected $appends = ['discounted_price'];
```

---

## 3. Konvensi Relasi (Relationships)
Tulis metode relasi menggunakan penamaan **camelCase** yang mencerminkan kardinalitas relasi:

### A. One-to-Many (Inverse) & One-to-One (Inverse) ➔ `belongsTo()`
Gunakan kata tunggal:
```php
public function shop()
{
    return $this->belongsTo(Shop::class);
}
```

### B. One-to-Many ➔ `hasMany()`
Gunakan kata jamak:
```php
public function items()
{
    return $this->hasMany(OrderItem::class);
}
```

### C. Many-to-Many ➔ `belongsToMany()`
Gunakan kata jamak dan tentukan nama tabel pivot jika tidak mengikuti alfabetis default Laravel:
```php
public function wishlistProducts()
{
    return $this->belongsToMany(Product::class, 'wishlists');
}
```

---

## 4. Custom Accessors (Laravel 10 Style)
Gunakan metode format lama `get[Name]Attribute` atau format baru `Attribute::make` untuk mendapatkan nilai kalkulasi dinamis:
```php
public function getDiscountedPriceAttribute()
{
    if ($this->discount_percentage > 0) {
        return $this->price * (1 - ($this->discount_percentage / 100));
    }
    return $this->price;
}
```
*Aturan: Pastikan custom attribute diakses menggunakan format snake_case (`$product->discounted_price`).*
