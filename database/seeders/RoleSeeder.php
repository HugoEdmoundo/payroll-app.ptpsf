<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara (opsional, aman di dev)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data role tanpa TRUNCATE
        Role::query()->delete();

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat role Superadmin
        Role::create([
            'name' => 'Superadmin',
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