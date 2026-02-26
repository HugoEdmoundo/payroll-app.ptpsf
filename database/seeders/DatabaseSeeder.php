<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Order matters! Run in this sequence:
     * 1. System Settings (master data)
     * 2. Roles & Permissions (auth system)
     * 3. Users (with roles)
     * 4. Karyawan (employee data)
     * 5. Pengaturan Gaji (salary configuration)
     * 6. Komponen Gaji (NKI, Absensi, Kasbon)
     * 7. Missing Permissions (additional permissions)
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();
        
        // 1. System Settings - JANGAN DIUBAH, ini default
        $this->command->info('📋 Step 1: System Settings');
        $this->call(SystemSettingSeeder::class);
        $this->command->newLine();
        
        // 2. Roles & Permissions
        $this->command->info('🔐 Step 2: Roles & Permissions');
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->command->newLine();
        
        // 3. Users
        $this->command->info('👥 Step 3: Users');
        $this->call(UserSeeder::class);
        $this->command->newLine();
        
        // 4. Karyawan
        $this->command->info('👔 Step 4: Karyawan (Employees)');
        $this->call(KaryawanSeeder::class);
        $this->command->newLine();
        
        // 5. Pengaturan Gaji
        $this->command->info('💰 Step 5: Pengaturan Gaji (Salary Configuration)');
        $this->call(PengaturanGajiSeeder::class);
        $this->command->newLine();
        
        // 5b. Pengaturan Gaji Status Pegawai (Harian & OJT)
        $this->command->info('💼 Step 5b: Pengaturan Gaji Status Pegawai (Harian & OJT)');
        $this->call(PengaturanGajiStatusPegawaiSeeder::class);
        $this->command->newLine();
        
        // 6. Komponen Gaji (NKI, Absensi, Kasbon)
        $this->command->info('📊 Step 6: Komponen Gaji (NKI, Absensi, Kasbon)');
        $this->call(NKISeeder::class);
        $this->call(AbsensiSeeder::class);
        $this->call(KasbonSeeder::class);
        $this->command->newLine();
        
        // 7. Additional Permissions
        $this->command->info('🔑 Step 7: Additional Permissions');
        $this->call(AddMissingPermissionsSeeder::class);
        $this->command->newLine();
        
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📝 Note: Acuan Gaji will be generated via "Generate" feature in the application.');
    }
}
