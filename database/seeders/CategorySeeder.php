<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Ringan', 'description' => 'Snack, biskuit, dan keripik'],
            ['name' => 'Minuman', 'description' => 'Air mineral, soda, dan jus'],
            ['name' => 'Sembako', 'description' => 'Beras, minyak, gula, dan telur'],
            ['name' => 'Kebutuhan Rumah Tangga', 'description' => 'Sabun, deterjen, dan pembersih'],
            ['name' => 'Obat-obatan', 'description' => 'Obat umum dan P3K'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
