<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role Superadmin (updateOrCreate untuk avoid duplicate)
        Role::updateOrCreate(
            ['name' => 'Superadmin'],
            [
                'description' => 'Full system access',
                'is_default' => false,
                'is_superadmin' => true,
            ]
        );

        // Buat role User default
        Role::updateOrCreate(
            ['name' => 'User'],
            [
                'description' => 'Default user role',
                'is_default' => true,
                'is_superadmin' => false,
            ]
        );
    }
}