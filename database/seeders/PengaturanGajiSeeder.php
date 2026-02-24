<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;

class PengaturanGajiSeeder extends Seeder
{
    /**
     * Seed pengaturan gaji yang sinkron dengan SystemSettingSeeder
     */
    public function run(): void
    {
        $this->command->info('Seeding Pengaturan Gaji...');
        
        // Data dari SystemSettingSeeder
        $pengaturanGaji = [
            // Organik
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 15000000,
                'tunjangan_jabatan' => 3000000,
                'tunjangan_prestasi' => 2000000,
                'tunjangan_operasional' => 1500000,
                'bpjs_kesehatan' => 500000,
                'bpjs_ketenagakerjaan' => 300000,
            ],
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Finance',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 8000000,
                'tunjangan_jabatan' => 1500000,
                'tunjangan_prestasi' => 1000000,
                'tunjangan_operasional' => 800000,
                'bpjs_kesehatan' => 300000,
                'bpjs_ketenagakerjaan' => 200000,
            ],
            
            // Konsultan
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Senior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 12000000,
                'tunjangan_jabatan' => 2500000,
                'tunjangan_prestasi' => 1800000,
                'tunjangan_operasional' => 1200000,
                'bpjs_kesehatan' => 400000,
                'bpjs_ketenagakerjaan' => 250000,
            ],
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Project Manager',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 14000000,
                'tunjangan_jabatan' => 2800000,
                'tunjangan_prestasi' => 2000000,
                'tunjangan_operasional' => 1400000,
                'bpjs_kesehatan' => 450000,
                'bpjs_ketenagakerjaan' => 280000,
            ],
            
            // Teknisi
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 6000000,
                'tunjangan_jabatan' => 1000000,
                'tunjangan_prestasi' => 800000,
                'tunjangan_operasional' => 600000,
                'bpjs_kesehatan' => 250000,
                'bpjs_ketenagakerjaan' => 150000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'Bali',
                'gaji_pokok' => 7500000,
                'tunjangan_jabatan' => 1200000,
                'tunjangan_prestasi' => 1000000,
                'tunjangan_operasional' => 700000,
                'bpjs_kesehatan' => 280000,
                'bpjs_ketenagakerjaan' => 180000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Installer',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 5500000,
                'tunjangan_jabatan' => 900000,
                'tunjangan_prestasi' => 700000,
                'tunjangan_operasional' => 500000,
                'bpjs_kesehatan' => 220000,
                'bpjs_ketenagakerjaan' => 140000,
            ],
            
            // Borongan
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 5000000,
                'tunjangan_jabatan' => 800000,
                'tunjangan_prestasi' => 600000,
                'tunjangan_operasional' => 400000,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 6500000,
                'tunjangan_jabatan' => 1100000,
                'tunjangan_prestasi' => 900000,
                'tunjangan_operasional' => 550000,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
            ],
        ];

        foreach ($pengaturanGaji as $data) {
            PengaturanGaji::create($data);
        }

        $this->command->info('Pengaturan Gaji seeded successfully! Created ' . count($pengaturanGaji) . ' configurations.');
    }
}
