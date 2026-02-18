<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Karyawan Module
            ['name' => 'View Karyawan', 'key' => 'karyawan.view', 'action_type' => 'view', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can view karyawan list and details'],
            ['name' => 'Create Karyawan', 'key' => 'karyawan.create', 'action_type' => 'create', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can create new karyawan'],
            ['name' => 'Edit Karyawan', 'key' => 'karyawan.edit', 'action_type' => 'edit', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can edit karyawan data'],
            ['name' => 'Delete Karyawan', 'key' => 'karyawan.delete', 'action_type' => 'delete', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can delete karyawan'],
            ['name' => 'Import Karyawan', 'key' => 'karyawan.import', 'action_type' => 'create', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can import karyawan data'],
            ['name' => 'Export Karyawan', 'key' => 'karyawan.export', 'action_type' => 'view', 'module' => 'karyawan', 'group' => 'Karyawan', 'description' => 'Can export karyawan data'],
            
            // Users Module
            ['name' => 'View Users', 'key' => 'users.view', 'action_type' => 'view', 'module' => 'users', 'group' => 'Users', 'description' => 'Can view users list and details'],
            ['name' => 'Create Users', 'key' => 'users.create', 'action_type' => 'create', 'module' => 'users', 'group' => 'Users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'key' => 'users.edit', 'action_type' => 'edit', 'module' => 'users', 'group' => 'Users', 'description' => 'Can edit user data'],
            ['name' => 'Delete Users', 'key' => 'users.delete', 'action_type' => 'delete', 'module' => 'users', 'group' => 'Users', 'description' => 'Can delete users'],
            ['name' => 'Manage User Permissions', 'key' => 'users.permissions', 'action_type' => 'edit', 'module' => 'users', 'group' => 'Users', 'description' => 'Can manage user-specific permissions'],
            
            // Roles Module
            ['name' => 'View Roles', 'key' => 'roles.view', 'action_type' => 'view', 'module' => 'roles', 'group' => 'Roles', 'description' => 'Can view roles list and details'],
            ['name' => 'Create Roles', 'key' => 'roles.create', 'action_type' => 'create', 'module' => 'roles', 'group' => 'Roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'key' => 'roles.edit', 'action_type' => 'edit', 'module' => 'roles', 'group' => 'Roles', 'description' => 'Can edit role data'],
            ['name' => 'Delete Roles', 'key' => 'roles.delete', 'action_type' => 'delete', 'module' => 'roles', 'group' => 'Roles', 'description' => 'Can delete roles'],
            
            // Settings Module
            ['name' => 'View Settings', 'key' => 'settings.view', 'action_type' => 'view', 'module' => 'settings', 'group' => 'Settings', 'description' => 'Can view system settings'],
            ['name' => 'Edit Settings', 'key' => 'settings.edit', 'action_type' => 'edit', 'module' => 'settings', 'group' => 'Settings', 'description' => 'Can edit system settings'],
            
            // Dashboard Module
            ['name' => 'View Dashboard', 'key' => 'dashboard.view', 'action_type' => 'view', 'module' => 'dashboard', 'group' => 'Dashboard', 'description' => 'Can view dashboard'],
            ['name' => 'View Reports', 'key' => 'dashboard.reports', 'action_type' => 'view', 'module' => 'dashboard', 'group' => 'Dashboard', 'description' => 'Can view reports and analytics'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
