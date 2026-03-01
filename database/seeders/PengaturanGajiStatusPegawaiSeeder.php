<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGajiStatusPegawai;
use App\Models\SystemSetting;

class PengaturanGajiStatusPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all lokasi kerja
        $lokasiKerjaList = SystemSetting::getOptions('lokasi_kerja');
        
        if (empty($lokasiKerjaList)) {
            $this->command->warn('⚠️  No lokasi_kerja found in system settings. Using defaults.');
            $lokasiKerjaList = ['Jakarta', 'Bandung', 'Surabaya'];
        }
        
        // Create Harian configuration for each lokasi
        foreach ($lokasiKerjaList as $lokasi) {
            PengaturanGajiStatusPegawai::updateOrCreate(
                [
                    'status_pegawai' => 'Harian',
                    'lokasi_kerja' => $lokasi,
                ],
                [
                    'gaji_pokok' => 90000.00, // 90k per day
                ]
            );
        }
        
        // Create OJT configuration for each lokasi
        foreach ($lokasiKerjaList as $lokasi) {
            PengaturanGajiStatusPegawai::updateOrCreate(
                [
                    'status_pegawai' => 'OJT',
                    'lokasi_kerja' => $lokasi,
                ],
                [
                    'gaji_pokok' => 3100000.00, // 3.1M per month
                ]
            );
        }
        
        $this->command->info('✅ Pengaturan Gaji Status Pegawai (Harian & OJT) created successfully!');
        $this->command->info('   - Harian: 90,000 per day');
        $this->command->info('   - OJT: 3,100,000 per month');
        $this->command->info('   - Locations: ' . implode(', ', $lokasiKerjaList));
    }
}
