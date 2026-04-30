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
        $markets = [
            [
                'code' => 'CAHAYA',
                'name' => 'Supermarket Cahaya',
                'address' => 'Jl. Cahaya No. 1',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'phone' => '081234567891',
                'status' => 'active',
            ],
            [
                'code' => 'BINTANG',
                'name' => 'Supermarket Bintang',
                'address' => 'Jl. Bintang No. 2',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'phone' => '081234567892',
                'status' => 'active',
            ],
            [
                'code' => 'BULAN',
                'name' => 'Supermarket Bulan',
                'address' => 'Jl. Bulan No. 3',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'phone' => '081234567893',
                'status' => 'active',
            ],
        ];

        foreach ($markets as $market) {
            Minimarket::updateOrCreate(['code' => $market['code']], $market);
        }
    }
}
