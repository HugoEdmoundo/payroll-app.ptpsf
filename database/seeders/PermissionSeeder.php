<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Karyawan Permissions
            ['name' => 'View Karyawan', 'key' => 'karyawan.view', 'group' => 'karyawan', 'description' => 'View karyawan data'],
            ['name' => 'Create Karyawan', 'key' => 'karyawan.create', 'group' => 'karyawan', 'description' => 'Create new karyawan'],
            ['name' => 'Edit Karyawan', 'key' => 'karyawan.edit', 'group' => 'karyawan', 'description' => 'Edit karyawan data'],
            ['name' => 'Delete Karyawan', 'key' => 'karyawan.delete', 'group' => 'karyawan', 'description' => 'Delete karyawan'],
            ['name' => 'Import Karyawan', 'key' => 'karyawan.import', 'group' => 'karyawan', 'description' => 'Import karyawan from Excel'],
            ['name' => 'Export Karyawan', 'key' => 'karyawan.export', 'group' => 'karyawan', 'description' => 'Export karyawan to Excel'],

            // User Management Permissions
            ['name' => 'View Users', 'key' => 'users.view', 'group' => 'users', 'description' => 'View system users'],
            ['name' => 'Create Users', 'key' => 'users.create', 'group' => 'users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'key' => 'users.edit', 'group' => 'users', 'description' => 'Edit user data'],
            ['name' => 'Delete Users', 'key' => 'users.delete', 'group' => 'users', 'description' => 'Delete users'],

            // Role Management Permissions
            ['name' => 'View Roles', 'key' => 'roles.view', 'group' => 'roles', 'description' => 'View system roles'],
            ['name' => 'Create Roles', 'key' => 'roles.create', 'group' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'key' => 'roles.edit', 'group' => 'roles', 'description' => 'Edit role permissions'],
            ['name' => 'Delete Roles', 'key' => 'roles.delete', 'group' => 'roles', 'description' => 'Delete roles'],

            // System Settings Permissions
            ['name' => 'Manage Settings', 'key' => 'settings.manage', 'group' => 'settings', 'description' => 'Manage system settings'],
            ['name' => 'View Settings', 'key' => 'settings.view', 'group' => 'settings', 'description' => 'View system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']], // cek unik berdasarkan key
                $permission
            );
        }
    }
}