<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Makanan Pokok', 'slug' => 'makanan-pokok', 'description' => 'Beras, minyak, gula, tepung, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Bumbu Dapur', 'slug' => 'bumbu-dapur', 'description' => 'Garam, merica, saus, sambal, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Aksesoris Gadget', 'slug' => 'aksesoris-gadget', 'description' => 'Charger, casing, earphone, powerbank, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Rumah Tangga', 'slug' => 'rumah-tangga', 'description' => 'Alat kebersihan, wadah dapur, dekorasi, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Kerajinan Tangan', 'slug' => 'kerajinan-tangan', 'description' => 'Kerajinan kayu, anyaman, pernak-pernik lokal, dll.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Pakaian Lokal', 'slug' => 'pakaian-lokal', 'description' => 'Batik, tenun, kaos sablon lokal, dll.', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
