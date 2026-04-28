<?php

namespace Database\Seeders;

use App\Models\Minimarket;
use Illuminate\Database\Seeder;

class MinimarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Minimarket::query()->updateOrCreate(
            ['code' => 'MM001'],
            [
                'name' => 'Minimarket Demo 01',
                'address' => 'Jl. Contoh No. 1',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'phone' => '081234567890',
                'status' => 'active',
            ]
        );
    }
}
