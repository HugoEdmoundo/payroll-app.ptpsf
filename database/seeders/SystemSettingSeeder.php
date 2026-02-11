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
            ['group' => 'jenis_karyawan', 'key' => 'tetap', 'value' => 'Tetap', 'order' => 1],
            ['group' => 'jenis_karyawan', 'key' => 'kontrak', 'value' => 'Kontrak', 'order' => 2],
            ['group' => 'jenis_karyawan', 'key' => 'magang', 'value' => 'Magang', 'order' => 3],
            ['group' => 'jenis_karyawan', 'key' => 'harian', 'value' => 'Harian', 'order' => 4],
            
            // Status Pegawai
            ['group' => 'status_pegawai', 'key' => 'aktif', 'value' => 'Aktif', 'order' => 1],
            ['group' => 'status_pegawai', 'key' => 'nonaktif', 'value' => 'Non-Aktif', 'order' => 2],
            ['group' => 'status_pegawai', 'key' => 'cuti', 'value' => 'Cuti', 'order' => 3],
            ['group' => 'status_pegawai', 'key' => 'resign', 'value' => 'Resign', 'order' => 4],
            
            // Status Perkawinan
            ['group' => 'status_perkawinan', 'key' => 'belum_kawin', 'value' => 'Belum Kawin', 'order' => 1],
            ['group' => 'status_perkawinan', 'key' => 'kawin', 'value' => 'Kawin', 'order' => 2],
            ['group' => 'status_perkawinan', 'key' => 'cerai_hidup', 'value' => 'Cerai Hidup', 'order' => 3],
            ['group' => 'status_perkawinan', 'key' => 'cerai_mati', 'value' => 'Cerai Mati', 'order' => 4],
            
            // Status Karyawan
            ['group' => 'status_karyawan', 'key' => 'probation', 'value' => 'Probation', 'order' => 1],
            ['group' => 'status_karyawan', 'key' => 'permanent', 'value' => 'Permanent', 'order' => 2],
            ['group' => 'status_karyawan', 'key' => 'kontrak', 'value' => 'Kontrak', 'order' => 3],
            
            // Lokasi Kerja
            ['group' => 'lokasi_kerja', 'key' => 'jakarta', 'value' => 'Jakarta', 'order' => 1],
            ['group' => 'lokasi_kerja', 'key' => 'bandung', 'value' => 'Bandung', 'order' => 2],
            ['group' => 'lokasi_kerja', 'key' => 'surabaya', 'value' => 'Surabaya', 'order' => 3],
            ['group' => 'lokasi_kerja', 'key' => 'bali', 'value' => 'Bali', 'order' => 4],
            ['group' => 'lokasi_kerja', 'key' => 'medan', 'value' => 'Medan', 'order' => 5],
            
            // Bank Options
            ['group' => 'bank_options', 'key' => 'bca', 'value' => 'BCA', 'order' => 1],
            ['group' => 'bank_options', 'key' => 'mandiri', 'value' => 'Mandiri', 'order' => 2],
            ['group' => 'bank_options', 'key' => 'bni', 'value' => 'BNI', 'order' => 3],
            ['group' => 'bank_options', 'key' => 'bri', 'value' => 'BRI', 'order' => 4],
            ['group' => 'bank_options', 'key' => 'cimb', 'value' => 'CIMB Niaga', 'order' => 5],
            ['group' => 'bank_options', 'key' => 'danamon', 'value' => 'Danamon', 'order' => 6],
            ['group' => 'bank_options', 'key' => 'permata', 'value' => 'Permata', 'order' => 7],
            
            // Jabatan Options
            ['group' => 'jabatan_options', 'key' => 'direktur', 'value' => 'Direktur', 'order' => 1],
            ['group' => 'jabatan_options', 'key' => 'manager', 'value' => 'Manager', 'order' => 2],
            ['group' => 'jabatan_options', 'key' => 'supervisor', 'value' => 'Supervisor', 'order' => 3],
            ['group' => 'jabatan_options', 'key' => 'staff', 'value' => 'Staff', 'order' => 4],
            ['group' => 'jabatan_options', 'key' => 'admin', 'value' => 'Admin', 'order' => 5],
            ['group' => 'jabatan_options', 'key' => 'operator', 'value' => 'Operator', 'order' => 6],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
        
        $this->command->info('System settings seeded successfully!');
    }
}