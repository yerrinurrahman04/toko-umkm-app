<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Super Admin TokoKita',
            'email' => 'admin@tokokita.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sellers (3 Sellers)
        DB::table('users')->insert([
            [
                'id' => 2,
                'name' => 'Budi Sembako',
                'email' => 'seller@tokokita.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Andi Elektronik',
                'email' => 'seller2@tokokita.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Wayan Art',
                'email' => 'seller3@tokokita.com',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Buyers (10 Buyers)
        $buyers = [];
        $names = [
            'Eko Prasetyo', 'Siti Aminah', 'Rian Hidayat', 'Dewi Lestari', 'Joko Susilo',
            'Mega Utami', 'Adi Wijaya', 'Indah Permata', 'Fajar Nugraha', 'Yuni Kartika'
        ];

        // Ensure buyer@tokokita.com exists as ID 5
        $buyers[] = [
            'id' => 5,
            'name' => $names[0],
            'email' => 'buyer@tokokita.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 1; $i < 10; $i++) {
            $buyers[] = [
                'id' => 5 + $i,
                'name' => $names[$i],
                'email' => 'buyer' . $i . '@tokokita.com',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($buyers);
    }
}
