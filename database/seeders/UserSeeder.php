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
            ['email' => 'superadmin@ptpsf.com'],
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@ptpsf.com',
                'password' => Hash::make('password123'),
                'role_id' => $superadminRole->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Regular User
        DB::table('users')->updateOrInsert(
            ['email' => 'user@ptpsf.com'],
            [
                'name' => 'Regular User',
                'email' => 'user@ptpsf.com',
                'password' => Hash::make('password123'),
                'role_id' => $userRole->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
