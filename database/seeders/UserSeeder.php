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
        
        if (!$superadminRole) {
            throw new \Exception('Superadmin role not found. Please run RoleSeeder first.');
        }
        
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@hugedm.fun'],
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@hugedm.fun',
                'password' => Hash::make('password123'),
                'role_id' => $superadminRole->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}