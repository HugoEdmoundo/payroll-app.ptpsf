<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'karyawan',
                'display_name' => 'Karyawan Management',
                'icon' => 'fa-users',
                'description' => 'Manage employee data, import/export, and employee information',
                'is_active' => true,
                'is_system' => true,
                'order' => 1,
            ],
            [
                'name' => 'users',
                'display_name' => 'User Management',
                'icon' => 'fa-user-shield',
                'description' => 'Manage system users, roles, and permissions',
                'is_active' => true,
                'is_system' => true,
                'order' => 2,
            ],
            [
                'name' => 'roles',
                'display_name' => 'Role Management',
                'icon' => 'fa-user-tag',
                'description' => 'Manage user roles and role permissions',
                'is_active' => true,
                'is_system' => true,
                'order' => 3,
            ],
            [
                'name' => 'settings',
                'display_name' => 'System Settings',
                'icon' => 'fa-cog',
                'description' => 'Configure system settings and options',
                'is_active' => true,
                'is_system' => true,
                'order' => 4,
            ],
            [
                'name' => 'dashboard',
                'display_name' => 'Dashboard',
                'icon' => 'fa-tachometer-alt',
                'description' => 'Main dashboard and analytics',
                'is_active' => true,
                'is_system' => true,
                'order' => 0,
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(
                ['name' => $module['name']],
                $module
            );
        }
    }
}
