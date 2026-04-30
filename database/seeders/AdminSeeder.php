<?php

namespace Database\Seeders;

use App\Models\Minimarket;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $markets = Minimarket::all();

        foreach ($markets as $market) {
            $suffix = strtolower(str_replace('Supermarket ', '', $market->name));
            
            User::updateOrCreate(
                ['email' => "admin_$suffix@warehouse.com"],
                [
                    'name' => "Admin " . ucfirst($suffix),
                    'username' => "admin_$suffix",
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                    'minimarket_id' => $market->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
