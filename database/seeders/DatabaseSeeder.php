<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        \DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Sistem Admin',
                'email' => 'admin@tokokita.com',
                'password' => \Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Budi Penjual (Toko Sembako)',
                'email' => 'seller@tokokita.com',
                'password' => \Hash::make('password'),
                'role' => 'seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Siti Penjual (Toko Elektronik)',
                'email' => 'seller2@tokokita.com',
                'password' => \Hash::make('password'),
                'role' => 'seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Andi Pembeli',
                'email' => 'buyer@tokokita.com',
                'password' => \Hash::make('password'),
                'role' => 'buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Dewi Pembeli',
                'email' => 'buyer2@tokokita.com',
                'password' => \Hash::make('password'),
                'role' => 'buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Shops
        \DB::table('shops')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'name' => 'Toko Sembako Jaya',
                'slug' => 'toko-sembako-jaya',
                'description' => 'Menyediakan kebutuhan pokok harian murah dan berkualitas.',
                'logo' => null,
                'address' => 'Jl. Kebon Jati No. 45, Bandung',
                'operating_hours' => '08:00 - 20:00',
                'shipping_area' => 'Kota Bandung, Kota Cimahi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'name' => 'Electro Tech',
                'slug' => 'electro-tech',
                'description' => 'Pusat aksesoris dan gawai terkini.',
                'logo' => null,
                'address' => 'Mall BEC Lantai 2, Bandung',
                'operating_hours' => '10:00 - 21:00',
                'shipping_area' => 'Nasional',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Categories
        \DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Makanan Pokok', 'slug' => 'makanan-pokok', 'description' => 'Beras, minyak, gula, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Bumbu Dapur', 'slug' => 'bumbu-dapur', 'description' => 'Garam, merica, saus, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Aksesoris Gadget', 'slug' => 'aksesoris-gadget', 'description' => 'Charger, casing, earphone, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Rumah Tangga', 'slug' => 'rumah-tangga', 'description' => 'Alat kebersihan, dekorasi, dll.', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // 4. Products
        \DB::table('products')->insert([
            // Shop 1 (Sembako)
            [
                'id' => 1,
                'shop_id' => 1,
                'category_id' => 1,
                'name' => 'Beras Premium Pandan Wangi 5kg',
                'slug' => 'beras-premium-pandan-wangi-5kg',
                'description' => 'Beras premium asli Cianjur dengan aroma khas pandan wangi.',
                'image' => null,
                'price' => 85000.00,
                'stock' => 50,
                'discount_percentage' => 5.00, // 5% off
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'shop_id' => 1,
                'category_id' => 1,
                'name' => 'Minyak Goreng SunCo 2 Liter',
                'slug' => 'minyak-goreng-sunco-2-liter',
                'description' => 'Minyak goreng kelapa sawit bermutu tinggi, bening dan sehat.',
                'image' => null,
                'price' => 38000.00,
                'stock' => 100,
                'discount_percentage' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'shop_id' => 1,
                'category_id' => 2,
                'name' => 'Kecap Manis Bango 550ml',
                'slug' => 'kecap-manis-bango-550ml',
                'description' => 'Kecap manis pilihan terbuat dari kedelai hitam berkualitas.',
                'image' => null,
                'price' => 24000.00,
                'stock' => 120,
                'discount_percentage' => 10.00, // 10% off
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Shop 2 (Electro)
            [
                'id' => 4,
                'shop_id' => 2,
                'category_id' => 3,
                'name' => 'Powerbank Anker PowerCore 10000mAh',
                'slug' => 'powerbank-anker-powercore-10000mah',
                'description' => 'Powerbank tipis berkapasitas besar dengan pengisian daya cepat.',
                'image' => null,
                'price' => 299000.00,
                'stock' => 30,
                'discount_percentage' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'shop_id' => 2,
                'category_id' => 3,
                'name' => 'Kabel Data Type-C to Lightning Baseus 1m',
                'slug' => 'kabel-data-type-c-to-lightning-baseus-1m',
                'description' => 'Kabel data kuat berlapis nilon rajut untuk pengisian cepat iPhone.',
                'image' => null,
                'price' => 89000.00,
                'stock' => 75,
                'discount_percentage' => 15.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 5. Product Variants
        \DB::table('product_variants')->insert([
            // Beras Premium (Product ID 1) variants
            ['id' => 1, 'product_id' => 1, 'name' => 'Kemasan 5kg', 'price' => 85000.00, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'product_id' => 1, 'name' => 'Kemasan 10kg', 'price' => 165000.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()],
            // Powerbank (Product ID 4) variants
            ['id' => 3, 'product_id' => 4, 'name' => 'Warna Hitam', 'price' => 299000.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'product_id' => 4, 'name' => 'Warna Putih', 'price' => 299000.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Vouchers
        \DB::table('vouchers')->insert([
            [
                'id' => 1,
                'code' => 'DISKON10',
                'type' => 'percent',
                'value' => 10.00,
                'min_spend' => 50000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'code' => 'UMKMCERIA',
                'type' => 'fixed',
                'value' => 15000.00,
                'min_spend' => 100000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Orders
        // Order 1: Completed Order (Toko Sembako)
        \DB::table('orders')->insert([
            'id' => 1,
            'buyer_id' => 4,
            'shop_id' => 1,
            'voucher_id' => 1,
            'order_number' => 'ORD-20260701-0001',
            'total_amount' => 123000.00, // (85000 + 38000)
            'discount_amount' => 12300.00, // 10% diskon voucher
            'shipping_cost' => 15000.00,
            'final_amount' => 125700.00,
            'status' => 'completed',
            'shipping_address' => 'Jl. Merdeka No. 12, Bandung',
            'notes' => 'Tolong beras yang rapi kemasannya.',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8),
        ]);

        \DB::table('order_items')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'product_id' => 1,
                'product_variant_id' => 1,
                'price' => 85000.00,
                'quantity' => 1,
                'discount_amount' => 4250.00, // 5% discount dari harga produk
                'total' => 80750.00,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'id' => 2,
                'order_id' => 1,
                'product_id' => 2,
                'product_variant_id' => null,
                'price' => 38000.00,
                'quantity' => 1,
                'discount_amount' => 0.00,
                'total' => 38000.00,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]
        ]);

        \DB::table('payments')->insert([
            'id' => 1,
            'order_id' => 1,
            'amount' => 125700.00,
            'payment_method' => 'Bank Transfer BNI',
            'receipt_image' => 'receipts/dummy-receipt.png',
            'status' => 'approved',
            'notes' => 'Pembayaran lunas terverifikasi otomatis.',
            'verified_at' => now()->subDays(9),
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(9),
        ]);

        \DB::table('shipments')->insert([
            'id' => 1,
            'order_id' => 1,
            'courier_name' => 'JNE Reguler',
            'tracking_number' => 'JNE1234567890',
            'status' => 'delivered',
            'shipping_cost' => 15000.00,
            'estimation_time' => '1-2 Days',
            'shipped_at' => now()->subDays(9),
            'delivered_at' => now()->subDays(8),
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8),
        ]);

        \DB::table('reviews')->insert([
            [
                'id' => 1,
                'product_id' => 1,
                'buyer_id' => 4,
                'order_item_id' => 1,
                'rating' => 5,
                'comment' => 'Beras wangi dan pulen banget, recommended!',
                'is_moderated' => true,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ]
        ]);

        // Order 2: Shipping Order (Toko Sembako)
        \DB::table('orders')->insert([
            'id' => 2,
            'buyer_id' => 5,
            'shop_id' => 1,
            'voucher_id' => null,
            'order_number' => 'ORD-20260705-0002',
            'total_amount' => 76000.00, // 2x Minyak Goreng
            'discount_amount' => 0.00,
            'shipping_cost' => 10000.00,
            'final_amount' => 86000.00,
            'status' => 'shipping',
            'shipping_address' => 'Gg. Asri No. 5, Cimahi',
            'notes' => null,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        \DB::table('order_items')->insert([
            [
                'id' => 3,
                'order_id' => 2,
                'product_id' => 2,
                'product_variant_id' => null,
                'price' => 38000.00,
                'quantity' => 2,
                'discount_amount' => 0.00,
                'total' => 76000.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);

        \DB::table('payments')->insert([
            'id' => 2,
            'order_id' => 2,
            'amount' => 86000.00,
            'payment_method' => 'Bank Transfer BCA',
            'receipt_image' => 'receipts/dummy-receipt-2.png',
            'status' => 'approved',
            'notes' => 'Pembayaran lunas.',
            'verified_at' => now()->subDays(4),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        \DB::table('shipments')->insert([
            'id' => 2,
            'order_id' => 2,
            'courier_name' => 'Sicepat',
            'tracking_number' => 'REG-SCP-987654',
            'status' => 'shipped',
            'shipping_cost' => 10000.00,
            'estimation_time' => '1 Day',
            'shipped_at' => now()->subDays(4),
            'delivered_at' => null,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        // Order 3: Pending Order (Toko Elektronik)
        \DB::table('orders')->insert([
            'id' => 3,
            'buyer_id' => 4,
            'shop_id' => 2,
            'voucher_id' => 2, // UMKMCERIA (15000 diskon)
            'order_number' => 'ORD-20260710-0003',
            'total_amount' => 299000.00, // Powerbank
            'discount_amount' => 15000.00,
            'shipping_cost' => 20000.00,
            'final_amount' => 304000.00,
            'status' => 'pending',
            'shipping_address' => 'Jl. Gatot Subroto No. 100, Bandung',
            'notes' => 'Warna Hitam ya gan.',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        \DB::table('order_items')->insert([
            [
                'id' => 4,
                'order_id' => 3,
                'product_id' => 4,
                'product_variant_id' => 3, // Warna Hitam
                'price' => 299000.00,
                'quantity' => 1,
                'discount_amount' => 0.00,
                'total' => 299000.00,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]
        ]);
    }
}
