<?php

namespace Database\Seeders;

use App\Models\Minimarket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserGudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $minimarket = Minimarket::query()->where('code', 'MM001')->first();

        if (! $minimarket) {
            return;
        }

        User::query()->updateOrCreate(
            ['email' => 'user@warehouse.com'],
            [
                'name' => 'User Gudang',
                'username' => 'usergudang',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'minimarket_id' => $minimarket->id,
                'is_active' => true,
            ]
        );
    }
}
