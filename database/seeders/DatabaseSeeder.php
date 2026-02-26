<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * COMPLETE SEEDER - All master data except Acuan/Hitung/Slip Gaji
     * 
     * Order:
     * 1. Roles & Permissions
     * 2. Users
     * 3. System Settings
     * 4. Jabatan Jenis Karyawan
     * 5. Karyawan
     * 6. Pengaturan Gaji
     * 7. Pengaturan Gaji Status Pegawai
     * 8. NKI
     * 9. Absensi
     * 10. Kasbon
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting COMPLETE database seeding...');
        $this->command->newLine();
        
        // 1. Roles & Permissions
        $this->command->info('🔐 Step 1: Roles & Permissions');
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AddMissingPermissionsSeeder::class);
        $this->command->newLine();
        
        // 2. Users
        $this->command->info('👥 Step 2: Users');
        $this->call(UserSeeder::class);
        $this->command->newLine();
        
        // 3. System Settings
        $this->command->info('⚙️  Step 3: System Settings');
        $this->call(SystemSettingSeeder::class);
        $this->command->newLine();
        
        // 4. Jabatan Jenis Karyawan
        $this->command->info('🔗 Step 4: Jabatan Jenis Karyawan');
        $this->call(JabatanJenisKaryawanSeeder::class);
        $this->command->newLine();
        
        // 5. Karyawan
        $this->command->info('👔 Step 5: Karyawan (35 employees)');
        $this->call(KaryawanSeeder::class);
        $this->command->newLine();
        
        // 6. Pengaturan Gaji
        $this->command->info('💰 Step 6: Pengaturan Gaji');
        $this->call(PengaturanGajiSeeder::class);
        $this->command->newLine();
        
        // 7. Pengaturan Gaji Status Pegawai
        $this->command->info('💼 Step 7: Pengaturan Gaji Status Pegawai');
        $this->call(PengaturanGajiStatusPegawaiSeeder::class);
        $this->command->newLine();
        
        // 8. NKI
        $this->command->info('📊 Step 8: NKI');
        $this->call(NKISeeder::class);
        $this->command->newLine();
        
        // 9. Absensi
        $this->command->info('📅 Step 9: Absensi');
        $this->call(AbsensiSeeder::class);
        $this->command->newLine();
        
        // 10. Kasbon
        $this->command->info('💵 Step 10: Kasbon');
        $this->call(KasbonSeeder::class);
        $this->command->newLine();
        
        $this->command->info('✅ Complete seeding finished!');
        $this->command->info('📝 Acuan Gaji, Hitung Gaji, Slip Gaji will be generated via UI');
    }
}
