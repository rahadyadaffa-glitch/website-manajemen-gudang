<?php

namespace Database\Seeders;

use App\Models\Minimarket;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserGudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::where('name', 'user')->first();
        $markets = Minimarket::all();

        foreach ($markets as $market) {
            $suffix = strtolower(str_replace('Supermarket ', '', $market->name));
            
            User::updateOrCreate(
                ['email' => "user_$suffix@warehouse.com"],
                [
                    'name' => "User " . ucfirst($suffix),
                    'username' => "user_$suffix",
                    'password' => Hash::make('password'),
                    'role_id' => $userRole->id,
                    'minimarket_id' => $market->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
