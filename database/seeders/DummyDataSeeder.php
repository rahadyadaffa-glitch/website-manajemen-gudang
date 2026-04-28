<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Minimarket;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Minimarkets
        $mms = [
            [
                'code' => 'MM001',
                'name' => 'IndoMarket Kaliurang',
                'address' => 'Jl. Kaliurang KM 5',
                'city' => 'Sleman',
                'province' => 'DI Yogyakarta',
                'phone' => '081122334455',
                'status' => 'active'
            ],
            [
                'code' => 'MM002',
                'name' => 'IndoMarket Malioboro',
                'address' => 'Jl. Malioboro No. 10',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'phone' => '081122334466',
                'status' => 'active'
            ],
            [
                'code' => 'MM003',
                'name' => 'IndoMarket Seturan',
                'address' => 'Jl. Seturan Raya',
                'city' => 'Sleman',
                'province' => 'DI Yogyakarta',
                'phone' => '081122334477',
                'status' => 'active'
            ],
        ];

        $minimarketModels = [];
        foreach ($mms as $mm) {
            $minimarketModels[] = Minimarket::updateOrCreate(['code' => $mm['code']], $mm);
        }

        // 2. Create Categories
        $categories = ['Makanan', 'Minuman', 'Kebutuhan Rumah Tangga', 'Kesehatan'];
        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::firstOrCreate(['name' => $cat]);
        }

        // 3. Create Products and Inventory
        $productsData = [
            ['name' => 'Indomie Goreng', 'unit' => 'Pcs'],
            ['name' => 'Aqua 600ml', 'unit' => 'Botol'],
            ['name' => 'Coca Cola 250ml', 'unit' => 'Kaleng'],
            ['name' => 'Beras Pandan Wangi 5kg', 'unit' => 'Karung'],
            ['name' => 'Minyak Goreng 2L', 'unit' => 'Pouch'],
            ['name' => 'Sabun Mandi Lifebuoy', 'unit' => 'Batang'],
            ['name' => 'Susu Ultra Milk 1L', 'unit' => 'Kotak'],
            ['name' => 'Pringles Original', 'unit' => 'Tube'],
            ['name' => 'Pepsodent 190g', 'unit' => 'Tube'],
            ['name' => 'Roti Tawar Sari Roti', 'unit' => 'Bungkus'],
        ];

        $superadmin = User::whereHas('role', function($q) { $q->where('name', 'superadmin'); })->first();

        foreach ($minimarketModels as $mm) {
            foreach ($productsData as $index => $p) {
                $product = Product::updateOrCreate(
                    ['sku' => strtoupper($mm->code) . '-SKU-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                    [
                        'category_id' => $categoryModels[$index % count($categoryModels)]->id,
                        'name' => $p['name'],
                        'unit' => $p['unit'],
                        'barcode' => rand(1000000000, 9999999999),
                        'min_stock_threshold' => 5,
                    ]
                );

                $qty = rand(10, 100);
                InventoryItem::updateOrCreate(
                    ['minimarket_id' => $mm->id, 'product_id' => $product->id],
                    ['quantity' => $qty, 'last_updated' => now()]
                );

                // Add some transaction history
                InventoryTransaction::create([
                    'minimarket_id' => $mm->id,
                    'product_id' => $product->id,
                    'user_id' => $superadmin->id,
                    'transaction_type' => 'in',
                    'quantity' => $qty,
                    'status' => 'completed',
                    'notes' => 'Initial stock seeding',
                    'approved_by' => $superadmin->id,
                    'approved_at' => now(),
                ]);
            }
        }
    }
}
