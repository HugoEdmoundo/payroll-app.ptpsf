<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGajiStatusPegawai;
use App\Models\SystemSetting;

class PengaturanGajiStatusPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Get all lokasi kerja options
        $lokasiKerjaOptions = SystemSetting::where('group', 'lokasi_kerja')->pluck('value')->toArray();
        
        // Status Pegawai configurations (only Harian and OJT)
        // NO JABATAN - applies to all positions
        $statusPegawaiConfigs = [
            'Harian' => [
                'gaji_pokok' => 90000, // 90rb per hari
                'keterangan' => '14 hari pertama sejak join date',
            ],
            'OJT' => [
                'gaji_pokok' => 3100000, // 3.1 juta per bulan
                'keterangan' => '3 bulan setelah fase harian',
            ],
        ];
        
        // Create configurations for each combination (status_pegawai + lokasi_kerja only)
        foreach ($statusPegawaiConfigs as $status => $config) {
            foreach ($lokasiKerjaOptions as $lokasi) {
                PengaturanGajiStatusPegawai::updateOrCreate(
                    [
                        'status_pegawai' => $status,
                        'lokasi_kerja' => $lokasi,
                    ],
                    [
                        'gaji_pokok' => $config['gaji_pokok'],
                        'keterangan' => $config['keterangan'],
                    ]
                );
            }
        }
        
        $this->command->info('Pengaturan Gaji Status Pegawai seeded successfully!');
    }
}
