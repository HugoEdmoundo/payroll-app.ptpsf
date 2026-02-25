<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua permission lama
        Permission::query()->delete();
        
        // Define permissions berdasarkan modul yang ada
        $permissions = [
            // ==================== DASHBOARD ====================
            ['name' => 'View Dashboard', 'key' => 'dashboard.view', 'group' => 'Dashboard', 'module' => 'dashboard', 'action_type' => 'view', 'description' => 'View dashboard'],

            // ==================== KARYAWAN ====================
            ['name' => 'View Karyawan', 'key' => 'karyawan.view', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'view', 'description' => 'View karyawan data'],
            ['name' => 'Create Karyawan', 'key' => 'karyawan.create', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'create', 'description' => 'Create new karyawan'],
            ['name' => 'Edit Karyawan', 'key' => 'karyawan.edit', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'edit', 'description' => 'Edit karyawan data'],
            ['name' => 'Delete Karyawan', 'key' => 'karyawan.delete', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'delete', 'description' => 'Delete karyawan'],
            ['name' => 'Import Karyawan', 'key' => 'karyawan.import', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'import', 'description' => 'Import karyawan from Excel'],
            ['name' => 'Export Karyawan', 'key' => 'karyawan.export', 'group' => 'Karyawan', 'module' => 'karyawan', 'action_type' => 'export', 'description' => 'Export karyawan to Excel'],

            // ==================== PENGATURAN GAJI ====================
            ['name' => 'View Pengaturan Gaji', 'key' => 'pengaturan_gaji.view', 'group' => 'Payroll', 'module' => 'pengaturan_gaji', 'action_type' => 'view', 'description' => 'View pengaturan gaji data'],
            ['name' => 'Create Pengaturan Gaji', 'key' => 'pengaturan_gaji.create', 'group' => 'Payroll', 'module' => 'pengaturan_gaji', 'action_type' => 'create', 'description' => 'Create new pengaturan gaji'],
            ['name' => 'Edit Pengaturan Gaji', 'key' => 'pengaturan_gaji.edit', 'group' => 'Payroll', 'module' => 'pengaturan_gaji', 'action_type' => 'edit', 'description' => 'Edit pengaturan gaji data'],
            ['name' => 'Delete Pengaturan Gaji', 'key' => 'pengaturan_gaji.delete', 'group' => 'Payroll', 'module' => 'pengaturan_gaji', 'action_type' => 'delete', 'description' => 'Delete pengaturan gaji'],

            // ==================== NKI ====================
            ['name' => 'View NKI', 'key' => 'nki.view', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'view', 'description' => 'View NKI data'],
            ['name' => 'Create NKI', 'key' => 'nki.create', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'create', 'description' => 'Create new NKI'],
            ['name' => 'Edit NKI', 'key' => 'nki.edit', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'edit', 'description' => 'Edit NKI data'],
            ['name' => 'Delete NKI', 'key' => 'nki.delete', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'delete', 'description' => 'Delete NKI'],
            ['name' => 'Import NKI', 'key' => 'nki.import', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'import', 'description' => 'Import NKI from Excel'],
            ['name' => 'Export NKI', 'key' => 'nki.export', 'group' => 'Payroll', 'module' => 'nki', 'action_type' => 'export', 'description' => 'Export NKI to Excel'],

            // ==================== ABSENSI ====================
            ['name' => 'View Absensi', 'key' => 'absensi.view', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'view', 'description' => 'View absensi data'],
            ['name' => 'Create Absensi', 'key' => 'absensi.create', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'create', 'description' => 'Create new absensi'],
            ['name' => 'Edit Absensi', 'key' => 'absensi.edit', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'edit', 'description' => 'Edit absensi data'],
            ['name' => 'Delete Absensi', 'key' => 'absensi.delete', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'delete', 'description' => 'Delete absensi'],
            ['name' => 'Import Absensi', 'key' => 'absensi.import', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'import', 'description' => 'Import absensi from Excel'],
            ['name' => 'Export Absensi', 'key' => 'absensi.export', 'group' => 'Payroll', 'module' => 'absensi', 'action_type' => 'export', 'description' => 'Export absensi to Excel'],

            // ==================== KASBON ====================
            ['name' => 'View Kasbon', 'key' => 'kasbon.view', 'group' => 'Payroll', 'module' => 'kasbon', 'action_type' => 'view', 'description' => 'View kasbon data'],
            ['name' => 'Create Kasbon', 'key' => 'kasbon.create', 'group' => 'Payroll', 'module' => 'kasbon', 'action_type' => 'create', 'description' => 'Create new kasbon'],
            ['name' => 'Edit Kasbon', 'key' => 'kasbon.edit', 'group' => 'Payroll', 'module' => 'kasbon', 'action_type' => 'edit', 'description' => 'Edit kasbon data'],
            ['name' => 'Delete Kasbon', 'key' => 'kasbon.delete', 'group' => 'Payroll', 'module' => 'kasbon', 'action_type' => 'delete', 'description' => 'Delete kasbon'],
            ['name' => 'Export Kasbon', 'key' => 'kasbon.export', 'group' => 'Payroll', 'module' => 'kasbon', 'action_type' => 'export', 'description' => 'Export kasbon to Excel'],

            // ==================== ACUAN GAJI ====================
            ['name' => 'View Acuan Gaji', 'key' => 'acuan_gaji.view', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'view', 'description' => 'View acuan gaji data'],
            ['name' => 'Create Acuan Gaji', 'key' => 'acuan_gaji.create', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'create', 'description' => 'Create new acuan gaji'],
            ['name' => 'Edit Acuan Gaji', 'key' => 'acuan_gaji.edit', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'edit', 'description' => 'Edit acuan gaji data'],
            ['name' => 'Delete Acuan Gaji', 'key' => 'acuan_gaji.delete', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'delete', 'description' => 'Delete acuan gaji'],
            ['name' => 'Manage Periode Acuan Gaji', 'key' => 'acuan_gaji.manage_periode', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'manage', 'description' => 'Manage periode acuan gaji'],
            ['name' => 'Import Acuan Gaji', 'key' => 'acuan_gaji.import', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'import', 'description' => 'Import acuan gaji from Excel'],
            ['name' => 'Export Acuan Gaji', 'key' => 'acuan_gaji.export', 'group' => 'Payroll', 'module' => 'acuan_gaji', 'action_type' => 'export', 'description' => 'Export acuan gaji to Excel'],

 // ==================== HITUNG GAJI ====================
            ['name' => 'View Hitung Gaji', 'key' => 'hitung_gaji.view', 'group' => 'Payroll', 'module' => 'hitung_gaji', 'action_type' => 'view', 'description' => 'View hitung gaji data'],
            ['name' => 'Create Hitung Gaji', 'key' => 'hitung_gaji.create', 'group' => 'Payroll', 'module' => 'hitung_gaji', 'action_type' => 'create', 'description' => 'Calculate salary'],
            ['name' => 'Edit Hitung Gaji', 'key' => 'hitung_gaji.edit', 'group' => 'Payroll', 'module' => 'hitung_gaji', 'action_type' => 'edit', 'description' => 'Edit hitung gaji adjustments'],
            ['name' => 'Delete Hitung Gaji', 'key' => 'hitung_gaji.delete', 'group' => 'Payroll', 'module' => 'hitung_gaji', 'action_type' => 'delete', 'description' => 'Delete hitung gaji'],
            ['name' => 'Export Hitung Gaji', 'key' => 'hitung_gaji.export', 'group' => 'Payroll', 'module' => 'hitung_gaji', 'action_type' => 'export', 'description' => 'Export hitung gaji to Excel'],

       // ==================== SLIP GAJI ====================
            ['name' => 'View Slip Gaji', 'key' => 'slip_gaji.view', 'group' => 'Payroll', 'module' => 'slip_gaji', 'action_type' => 'view', 'description' => 'View slip gaji'],
            ['name' => 'Download Slip Gaji', 'key' => 'slip_gaji.download', 'group' => 'Payroll', 'module' => 'slip_gaji', 'action_type' => 'download', 'description' => 'Download slip gaji PDF/PNG'],
            ['name' => 'Export Slip Gaji', 'key' => 'slip_gaji.export', 'group' => 'Payroll', 'module' => 'slip_gaji', 'action_type' => 'export', 'description' => 'Export slip gaji to Excel'],

            // ==================== USER MANAGEMENT ====================
            ['name' => 'View Users', 'key' => 'users.view', 'group' => 'Admin', 'module' => 'users', 'action_type' => 'view', 'description' => 'View system users'],
            ['name' => 'Create Users', 'key' => 'users.create', 'group' => 'Admin', 'module' => 'users', 'action_type' => 'create', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'key' => 'users.edit', 'group' => 'Admin', 'module' => 'users', 'action_type' => 'edit', 'description' => 'Edit user data'],
            ['name' => 'Delete Users', 'key' => 'users.delete', 'group' => 'Admin', 'module' => 'users', 'action_type' => 'delete', 'description' => 'Delete users'],

            // ==================== ROLE MANAGEMENT ====================
            ['name' => 'View Roles', 'key' => 'roles.view', 'group' => 'Admin', 'module' => 'roles', 'action_type' => 'view', 'description' => 'View system roles'],
            ['name' => 'Create Roles', 'key' => 'roles.create', 'group' => 'Admin', 'module' => 'roles', 'action_type' => 'create', 'description' => 'Create new roles'],
            ['name' => 'Edit Roles', 'key' => 'roles.edit', 'group' => 'Admin', 'module' => 'roles', 'action_type' => 'edit', 'description' => 'Edit role permissions'],
            ['name' => 'Delete Roles', 'key' => 'roles.delete', 'group' => 'Admin', 'module' => 'roles', 'action_type' => 'delete', 'description' => 'Delete roles'],

            // ==================== SYSTEM SETTINGS ====================
            ['name' => 'View Settings', 'key' => 'settings.view', 'group' => 'Admin', 'module' => 'settings', 'action_type' => 'view', 'description' => 'View system settings'],
            ['name' => 'Manage Settings', 'key' => 'settings.manage', 'group' => 'Admin', 'module' => 'settings', 'action_type' => 'manage', 'description' => 'Manage system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }
    }
}
