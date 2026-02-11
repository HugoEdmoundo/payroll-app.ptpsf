<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data existing jika ada
        Role::truncate();
        
        // Buat role Super Admin
        Role::create([
            'name' => 'Super Admin',
            'description' => 'Full system access',
            'is_default' => false,
            'is_superadmin' => true,
        ]);
        
        // Buat role User default
        Role::create([
            'name' => 'User',
            'description' => 'Default user role',
            'is_default' => true,
            'is_superadmin' => false,
        ]);
    }
}