<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User; // <-- 1. Import model User
use Illuminate\Support\Facades\Hash; // <-- 2. Import Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3. Kita gunakan 'firstOrCreate'
        // Ini akan mencari user dengan email 'admin@email.com'.
        // Jika tidak ada, baru akan dibuat. Ini mencegah duplikat.
        User::firstOrCreate(
            ['email' => 'admin@mail.com'], // Kunci unik untuk dicari
            [
                'name' => 'attar',
                'password' => Hash::make('admin123'), // Password Anda
                // created_at & updated_at akan diisi otomatis
            ]
        );

        // Anda bisa tambahkan user lain di sini jika perlu
        // User::firstOrCreate(
        //     ['email' => 'user@email.com'],
        //     [
        //         'name' => 'User Biasa',
        //         'password' => Hash::make('password')
        //     ]
        // );
    }
}
