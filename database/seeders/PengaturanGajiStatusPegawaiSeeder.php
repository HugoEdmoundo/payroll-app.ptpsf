<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGajiStatusPegawai;
use App\Models\SystemSetting;

class PengaturanGajiStatusPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Get all jabatan and lokasi kerja options
        $jabatanOptions = SystemSetting::where('group', 'jabatan_options')->pluck('value')->toArray();
        $lokasiKerjaOptions = SystemSetting::where('group', 'lokasi_kerja')->pluck('value')->toArray();
        
        // Status Pegawai configurations (only Harian and OJT)
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
        
        // Create configurations for each combination
        foreach ($statusPegawaiConfigs as $status => $config) {
            foreach ($jabatanOptions as $jabatan) {
                foreach ($lokasiKerjaOptions as $lokasi) {
                    PengaturanGajiStatusPegawai::updateOrCreate(
                        [
                            'status_pegawai' => $status,
                            'jabatan' => $jabatan,
                            'lokasi_kerja' => $lokasi,
                        ],
                        [
                            'gaji_pokok' => $config['gaji_pokok'],
                            'keterangan' => $config['keterangan'],
                        ]
                    );
                }
            }
        }
        
        $this->command->info('Pengaturan Gaji Status Pegawai seeded successfully!');
    }
}
