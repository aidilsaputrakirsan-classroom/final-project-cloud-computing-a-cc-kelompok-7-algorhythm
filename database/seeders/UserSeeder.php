<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Attar Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // Set role admin
            ]
        );

        // 2. Buat Akun User Biasa (Untuk Tes Landing Page)
        User::firstOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name' => 'Pengunjung Perpus',
                'password' => Hash::make('user123'),
                'role' => 'user', // Set role user
            ]
        );
    }
}