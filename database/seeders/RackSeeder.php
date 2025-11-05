<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rack;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rack::insert([
            [
                'name' => '1A',
                'rak' => '1',
            ],
            [
                'name' => '1B',
                'rak' => '1',
            ],
            // ... (list lengkap ada di file)
        ]);
    }
}