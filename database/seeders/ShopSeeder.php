<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shops')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'name' => 'Sembako Jaya Abadi',
                'slug' => 'sembako-jaya-abadi',
                'description' => 'Menyediakan aneka bahan makanan pokok dan bumbu dapur lengkap dengan harga terjangkau.',
                'logo' => null,
                'address' => 'Pasar Baru Bandung K-10, Kota Bandung',
                'operating_hours' => '07:00 - 17:00',
                'shipping_area' => 'Kota Bandung, Cimahi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'name' => 'Glow Gadget & Accessories',
                'slug' => 'glow-gadget-accessories',
                'description' => 'Toko aksesoris handphone dan gadget berkualitas tinggi, bergaransi lokal.',
                'logo' => null,
                'address' => 'BEC Mall Lantai 2 Block B-05, Kota Bandung',
                'operating_hours' => '10:00 - 21:00',
                'shipping_area' => 'Seluruh Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 4,
                'name' => 'Dewata Art Craft',
                'slug' => 'dewata-art-craft',
                'description' => 'Kerajinan tangan anyaman bambu, kayu ukir, dan batik khas langsung dari perajin lokal Bali.',
                'logo' => null,
                'address' => 'Jl. Raya Ubud No. 45, Gianyar, Bali',
                'operating_hours' => '09:00 - 19:00',
                'shipping_area' => 'Seluruh Indonesia & Global',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
