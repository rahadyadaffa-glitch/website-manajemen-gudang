<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->upsert([
            ['id' => 1, 'name' => 'superadmin', 'display_name' => 'Super Administrator'],
            ['id' => 2, 'name' => 'admin', 'display_name' => 'Admin Minimarket'],
            ['id' => 3, 'name' => 'user', 'display_name' => 'User Gudang'],
        ], ['id'], ['name', 'display_name']);
    }
}
