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
        $userRole = \App\Models\Role::where('name', 'User')->first();

        if (!$superadminRole) {
            throw new \Exception('Superadmin role not found. Please run RoleSeeder first.');
        }
        
        if (!$userRole) {
            throw new \Exception('User role not found. Please run RoleSeeder first.');
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
        
        $this->command->info('✓ Created 2 users:');
        $this->command->info('  - superadmin@ptpsf.com (Superadmin)');
        $this->command->info('  - user@ptpsf.com (User)');
    }
}
