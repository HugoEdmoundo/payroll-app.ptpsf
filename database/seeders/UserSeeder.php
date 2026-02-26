<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = \App\Models\Role::where('is_superadmin', true)->first();
        $userRole = \App\Models\Role::where('name', 'user')->first();

        if (!$superadminRole || !$userRole) {
            throw new \Exception('Roles not found. Please run RoleSeeder first.');
        }

        // Superadmin
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@hugoedm.fun'],
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@hugoedm.fun',
                'password' => Hash::make('password123'),
                'role_id' => $superadminRole->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Regular User
        DB::table('users')->updateOrInsert(
            ['email' => 'user@hugoedm.fun'],
            [
                'name' => 'Regular User',
                'email' => 'user@hugoedm.fun',
                'password' => Hash::make('password123'),
                'role_id' => $userRole->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
