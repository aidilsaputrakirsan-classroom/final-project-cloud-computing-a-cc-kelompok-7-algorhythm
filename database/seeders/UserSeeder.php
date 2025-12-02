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
    // 1. Buat Akun Admin
    User::firstOrCreate(
        ['email' => 'admin@mail.com'],
        [
            'name' => 'Administrator',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // Role Admin
        ]
    );

    // 2. Buat Akun User Biasa
    User::firstOrCreate(
        ['email' => 'user@mail.com'],
        [
            'name' => 'Pengunjung',
            'password' => Hash::make('user123'),
            'role' => 'user', // Role User
        ]
    );
}
}
