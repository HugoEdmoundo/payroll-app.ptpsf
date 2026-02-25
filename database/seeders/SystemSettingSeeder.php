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
            ['group' => 'status_karyawan', 'key' => 'non_active', 'value' => 'Non-Active', 'order' => 2],
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
            ['group' => 'jabatan_options', 'key' => 'junior installer', 'value' => 'Junior Installer', 'order' => 3],
            ['group' => 'jabatan_options', 'key' => 'junior leader', 'value' => 'Junior Leader', 'order' => 4],
            ['group' => 'jabatan_options', 'key' => 'junior engineer', 'value' => 'Junior Engineer', 'order' => 5],
            ['group' => 'jabatan_options', 'key' => 'senior installer', 'value' => 'Senior Installer', 'order' => 6],
            ['group' => 'jabatan_options', 'key' => 'senior leader', 'value' => 'Senior Leader', 'order' => 7],
            ['group' => 'jabatan_options', 'key' => 'senior engineer', 'value' => 'Senior Engineer', 'order' => 8],
            ['group' => 'jabatan_options', 'key' => 'project manager', 'value' => 'Project Manager', 'order' => 9],
            ['group' => 'jabatan_options', 'key' => 'team leader (senior)', 'value' => 'Team Leader (senior)', 'order' => 10],
            ['group' => 'jabatan_options', 'key' => 'team leader (junior)', 'value' => 'Team Leader (junior)', 'order' => 11],
            
            // Komponen Gaji Labels (Editable Field Names)
            ['group' => 'komponen_gaji_labels', 'key' => 'gaji_pokok', 'value' => 'Gaji Pokok', 'order' => 1],
            ['group' => 'komponen_gaji_labels', 'key' => 'tunjangan_prestasi', 'value' => 'Tunjangan Prestasi', 'order' => 2],
            ['group' => 'komponen_gaji_labels', 'key' => 'tunjangan_konjungtur', 'value' => 'Tunjangan Konjungtur', 'order' => 3],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_ibadah', 'value' => 'Benefit Ibadah', 'order' => 4],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_komunikasi', 'value' => 'Benefit Komunikasi', 'order' => 5],
            ['group' => 'komponen_gaji_labels', 'key' => 'benefit_operasional', 'value' => 'Benefit Operasional', 'order' => 6],
            ['group' => 'komponen_gaji_labels', 'key' => 'reward', 'value' => 'Reward', 'order' => 7],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kesehatan', 'value' => 'BPJS Kesehatan', 'order' => 8],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kecelakaan_kerja', 'value' => 'BPJS Kecelakaan Kerja', 'order' => 9],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_kematian', 'value' => 'BPJS Kematian', 'order' => 10],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_jht', 'value' => 'BPJS JHT', 'order' => 11],
            ['group' => 'komponen_gaji_labels', 'key' => 'bpjs_jp', 'value' => 'BPJS JP', 'order' => 12],
            ['group' => 'komponen_gaji_labels', 'key' => 'tabungan_koperasi', 'value' => 'Tabungan Koperasi', 'order' => 13],
            ['group' => 'komponen_gaji_labels', 'key' => 'koperasi', 'value' => 'Koperasi', 'order' => 14],
            ['group' => 'komponen_gaji_labels', 'key' => 'kasbon', 'value' => 'Kasbon', 'order' => 15],
            ['group' => 'komponen_gaji_labels', 'key' => 'umroh', 'value' => 'Umroh', 'order' => 16],
            ['group' => 'komponen_gaji_labels', 'key' => 'kurban', 'value' => 'Kurban', 'order' => 17],
            ['group' => 'komponen_gaji_labels', 'key' => 'mutabaah', 'value' => 'Mutabaah', 'order' => 18],
            ['group' => 'komponen_gaji_labels', 'key' => 'potongan_absensi', 'value' => 'Potongan Absensi', 'order' => 19],
            ['group' => 'komponen_gaji_labels', 'key' => 'potongan_kehadiran', 'value' => 'Potongan Kehadiran', 'order' => 20],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
        
        $this->command->info('System settings seeded successfully!');
    }
}