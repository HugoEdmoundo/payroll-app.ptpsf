<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Schema;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Schema::disableForeignKeyConstraints();
        SystemSetting::truncate();
        Schema::enableForeignKeyConstraints();
        
        $settings = [
            // Jenis Karyawan
            ['group' => 'jenis_karyawan', 'key' => 'konsultan', 'value' => 'Konsultan', 'order' => 1],
            ['group' => 'jenis_karyawan', 'key' => 'organik', 'value' => 'Organik', 'order' => 2],
            ['group' => 'jenis_karyawan', 'key' => 'teknisi', 'value' => 'Teknisi', 'order' => 3],
            ['group' => 'jenis_karyawan', 'key' => 'borongan', 'value' => 'Borongan', 'order' => 4],
            
            // Status Pegawai
            ['group' => 'status_pegawai', 'key' => 'harian', 'value' => 'Harian', 'order' => 1],
            ['group' => 'status_pegawai', 'key' => 'ojt', 'value' => 'OJT', 'order' => 2],
            ['group' => 'status_pegawai', 'key' => 'kontrak', 'value' => 'Kontrak', 'order' => 3],
            ['group' => 'status_pegawai', 'key' => 'magang', 'value' => 'Magang', 'order' => 4],
            
            // Status Perkawinan
            ['group' => 'status_perkawinan', 'key' => 'belum_kawin', 'value' => 'Belum Kawin', 'order' => 1],
            ['group' => 'status_perkawinan', 'key' => 'kawin', 'value' => 'Kawin', 'order' => 2],
            ['group' => 'status_perkawinan', 'key' => 'cerai_hidup', 'value' => 'Cerai Hidup', 'order' => 3],
            ['group' => 'status_perkawinan', 'key' => 'cerai_mati', 'value' => 'Cerai Mati', 'order' => 4],
            
            // Status Karyawan
            ['group' => 'status_karyawan', 'key' => 'active', 'value' => 'Active', 'order' => 1],
            ['group' => 'status_karyawan', 'key' => 'nonactive', 'value' => 'Non-Active', 'order' => 2],
            ['group' => 'status_karyawan', 'key' => 'resign', 'value' => 'Resign', 'order' => 3],
            
            // Lokasi Kerja
            ['group' => 'lokasi_kerja', 'key' => 'cj', 'value' => 'Central Java', 'order' => 1],
            ['group' => 'lokasi_kerja', 'key' => 'ej', 'value' => 'East Java', 'order' => 2],
            ['group' => 'lokasi_kerja', 'key' => 'wj', 'value' => 'West Java', 'order' => 3],
            ['group' => 'lokasi_kerja', 'key' => 'bali', 'value' => 'Bali', 'order' => 4],
            
            // Bank Options
            ['group' => 'bank_options', 'key' => 'bca', 'value' => 'BCA', 'order' => 1],
            ['group' => 'bank_options', 'key' => 'mandiri', 'value' => 'Mandiri', 'order' => 2],
            ['group' => 'bank_options', 'key' => 'bni', 'value' => 'BNI', 'order' => 3],
            ['group' => 'bank_options', 'key' => 'bri', 'value' => 'BRI', 'order' => 4],
            ['group' => 'bank_options', 'key' => 'cimb', 'value' => 'CIMB Niaga', 'order' => 5],
            ['group' => 'bank_options', 'key' => 'bsi', 'value' => 'BSI', 'order' => 6],
            
            // Jabatan Options
            ['group' => 'jabatan_options', 'key' => 'finance', 'value' => 'Finance', 'order' => 1],
            ['group' => 'jabatan_options', 'key' => 'manager', 'value' => 'Manager', 'order' => 2],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
        
        $this->command->info('System settings seeded successfully!');
    }
}