<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        SystemSetting::truncate();
        Schema::enableForeignKeyConstraints();
        
        $settings = [
            // Jenis Karyawan (2 types only)
            ['group' => 'jenis_karyawan', 'key' => 'teknisi', 'value' => 'Teknisi', 'order' => 1],
            ['group' => 'jenis_karyawan', 'key' => 'borongan', 'value' => 'Borongan', 'order' => 2],
            ['group' => 'jenis_karyawan', 'key' => 'konsultan', 'value' => 'Konsultan', 'order' => 3],
            ['group' => 'jenis_karyawan', 'key' => 'organik', 'value' => 'Organik', 'order' => 4],
            
            // Status Pegawai
            ['group' => 'status_pegawai', 'key' => 'harian', 'value' => 'Harian', 'order' => 1],
            ['group' => 'status_pegawai', 'key' => 'ojt', 'value' => 'OJT', 'order' => 2],
            ['group' => 'status_pegawai', 'key' => 'kontrak', 'value' => 'Kontrak', 'order' => 3],
            
            // Status Perkawinan
            ['group' => 'status_perkawinan', 'key' => 'belum_kawin', 'value' => 'Belum Kawin', 'order' => 1],
            ['group' => 'status_perkawinan', 'key' => 'kawin', 'value' => 'Kawin', 'order' => 2],
            ['group' => 'status_perkawinan', 'key' => 'cerai_hidup', 'value' => 'Cerai Hidup', 'order' => 3],
            ['group' => 'status_perkawinan', 'key' => 'cerai_mati', 'value' => 'Cerai Mati', 'order' => 4],
            
            // Status Karyawan
            ['group' => 'status_karyawan', 'key' => 'active', 'value' => 'Active', 'order' => 1],
            ['group' => 'status_karyawan', 'key' => 'non_active', 'value' => 'Non-Active', 'order' => 2],
            ['group' => 'status_karyawan', 'key' => 'resign', 'value' => 'Resign', 'order' => 3],
            
            // Lokasi Kerja (5 locations)
            ['group' => 'lokasi_kerja', 'key' => 'central_java', 'value' => 'Central Java', 'order' => 1],
            ['group' => 'lokasi_kerja', 'key' => 'east_java', 'value' => 'East Java', 'order' => 2],
            ['group' => 'lokasi_kerja', 'key' => 'west_java', 'value' => 'West Java', 'order' => 3],
            ['group' => 'lokasi_kerja', 'key' => 'bali', 'value' => 'Bali', 'order' => 4],
            ['group' => 'lokasi_kerja', 'key' => 'kantor_pusat', 'value' => 'Kantor Pusat', 'order' => 5],
            
            // Bank Options
            ['group' => 'bank_options', 'key' => 'bca', 'value' => 'BCA', 'order' => 1],
            ['group' => 'bank_options', 'key' => 'mandiri', 'value' => 'Mandiri', 'order' => 2],
            ['group' => 'bank_options', 'key' => 'bni', 'value' => 'BNI', 'order' => 3],
            ['group' => 'bank_options', 'key' => 'bri', 'value' => 'BRI', 'order' => 4],
            ['group' => 'bank_options', 'key' => 'cimb', 'value' => 'CIMB Niaga', 'order' => 5],
            ['group' => 'bank_options', 'key' => 'bsi', 'value' => 'BSI', 'order' => 6],
            
            // Jabatan for Teknisi & Borongan only
            ['group' => 'jabatan_options', 'key' => 'junior_installer', 'value' => 'Junior Installer', 'order' => 1],
            ['group' => 'jabatan_options', 'key' => 'junior_leader', 'value' => 'Junior Leader', 'order' => 2],
            ['group' => 'jabatan_options', 'key' => 'junior_engineer', 'value' => 'Junior Engineer', 'order' => 3],
            ['group' => 'jabatan_options', 'key' => 'senior_installer', 'value' => 'Senior Installer', 'order' => 4],
            ['group' => 'jabatan_options', 'key' => 'senior_leader', 'value' => 'Senior Leader', 'order' => 5],
            ['group' => 'jabatan_options', 'key' => 'senior_engineer', 'value' => 'Senior Engineer', 'order' => 6],
            ['group' => 'jabatan_options', 'key' => 'project_manager', 'value' => 'Project Manager', 'order' => 7],
            ['group' => 'jabatan_options', 'key' => 'team_leader_senior', 'value' => 'Team Leader (senior)', 'order' => 8],
            ['group' => 'jabatan_options', 'key' => 'team_leader_junior', 'value' => 'Team Leader (junior)', 'order' => 9],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
        
        $this->command->info('System settings seeded successfully!');
    }
}
