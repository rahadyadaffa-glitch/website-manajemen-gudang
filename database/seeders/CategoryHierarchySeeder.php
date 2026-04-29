<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryHierarchySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Clear all categories
        Category::truncate();

        $data = [
            [
                'name' => 'Food (Makanan Kering & Awet)',
                'storage_note' => 'Suhu ruang, area kering, jauh dari bahan kimia.',
                'subs' => [
                    'Sembako (Basic Food)',
                    'Mie & Instan',
                    'Bumbu & Bahan Masak',
                    'Makanan Kaleng',
                    'Snack & Biskuit',
                    'Confectionery',
                    'Breakfast & Bakery'
                ]
            ],
            [
                'name' => 'Beverages (Minuman)',
                'storage_note' => 'Suhu ruang, namun memerlukan kekuatan rak yang tinggi karena bobotnya berat (cairan).',
                'subs' => [
                    'Air Mineral',
                    'Minuman Berperisa',
                    'Minuman Bubuk',
                    'Susu & Olahan (UHT)',
                    'Minuman Kesehatan'
                ]
            ],
            [
                'name' => 'Fresh & Frozen (Suhu Khusus)',
                'storage_note' => 'Memerlukan Chiller (2-8°C) atau Freezer (-18°C).',
                'subs' => [
                    'Dairy Product',
                    'Frozen Food',
                    'Ice Cream',
                    'Fresh Produce'
                ]
            ],
            [
                'name' => 'Personal Care (Perawatan Diri)',
                'storage_note' => 'Terpisah dari makanan karena memiliki aroma kuat.',
                'subs' => [
                    'Hair Care',
                    'Body Care',
                    'Oral Care',
                    'Skin Care & Cosmetics',
                    'Sanitary'
                ]
            ],
            [
                'name' => 'Baby & Kids (Kebutuhan Anak)',
                'storage_note' => 'Penyimpanan: Area bersih dan higienis.',
                'subs' => [
                    'Baby Food',
                    'Baby Care',
                    'Toys'
                ]
            ],
            [
                'name' => 'Household (Kebutuhan Rumah Tangga)',
                'storage_note' => 'Area khusus barang kimia, harus berada di rak paling bawah jika berdekatan dengan makanan.',
                'subs' => [
                    'Cleaning Supplies',
                    'Pesticides',
                    'Kitchenware',
                    'Air Freshener'
                ]
            ],
            [
                'name' => 'Medicine & Health (Farmasi Terbatas)',
                'storage_note' => 'Rak bersih, kering, dan biasanya dalam pantauan kasir.',
                'subs' => [
                    'Obat Bebas (OTC)',
                    'P3K & Sanitasi',
                    'Vitamin & Suplemen'
                ]
            ],
            [
                'name' => 'General Merchandise & Digital',
                'storage_note' => 'Penyimpanan: Rak standar retail.',
                'subs' => [
                    'Stationery (ATK)',
                    'Electronics & Hardware',
                    'Apparel',
                    'Digital Goods'
                ]
            ],
            [
                'name' => 'Kategori Khusus: High Value & Regulated',
                'storage_note' => 'Area paling aman (biasanya di balik meja kasir atau lemari terkunci).',
                'subs' => [
                    'Tobacco',
                    'Alcoholic Beverages'
                ]
            ],
        ];

        $firstSubId = null;

        foreach ($data as $item) {
            $parent = Category::create([
                'name' => $item['name'],
                'storage_note' => $item['storage_note']
            ]);

            foreach ($item['subs'] as $subName) {
                $child = Category::create([
                    'name' => $subName,
                    'parent_id' => $parent->id,
                    'storage_note' => $item['storage_note']
                ]);
                
                if (!$firstSubId) $firstSubId = $child->id;
            }
        }

        // 3. Re-assign all products to the first available sub-category 
        // to satisfy NOT NULL constraint and keep them in the system.
        if ($firstSubId) {
            Product::query()->update(['category_id' => $firstSubId]);
        }

        // 4. Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
