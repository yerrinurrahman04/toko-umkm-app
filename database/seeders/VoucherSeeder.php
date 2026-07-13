<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vouchers')->insert([
            [
                'id' => 1,
                'code' => 'DISKON10',
                'type' => 'percent',
                'value' => 10.00,
                'min_spend' => 50000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
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
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'code' => 'HEMATBANYAK',
                'type' => 'percent',
                'value' => 20.00,
                'min_spend' => 200000.00,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
