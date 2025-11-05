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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 4. Hapus komentar (uncomment) baris ini
        // untuk memanggil UserSeeder yang baru saja kita buat
        $this->call([
            UserSeeder::class,
            // Anda bisa tambahkan seeder lain di sini nanti
        ]);
    }
}
