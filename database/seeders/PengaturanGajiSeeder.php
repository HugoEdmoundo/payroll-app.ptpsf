<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;

class PengaturanGajiSeeder extends Seeder
{
    /**
     * Seed pengaturan gaji yang sinkron dengan database schema
     */
    public function run(): void
    {
        $this->command->info('Seeding Pengaturan Gaji...');
        
        // Kolom yang ada di database:
        // - gaji_pokok
        // - tunjangan_operasional
        // - potongan_koperasi
        // - gaji_nett
        // - bpjs_kesehatan
        // - bpjs_ketenagakerjaan
        // - bpjs_kecelakaan_kerja
        // - bpjs_total
        // - total_gaji
        
        $pengaturanGaji = [
            // Organik
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 15000000,
                'tunjangan_operasional' => 3000000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 500000,
                'bpjs_ketenagakerjaan' => 300000,
                'bpjs_kecelakaan_kerja' => 200000,
                'bpjs_total' => 1000000, // sum of all BPJS
                'gaji_nett' => 17900000, // gaji_pokok + tunjangan_operasional - potongan_koperasi
                'total_gaji' => 18900000, // gaji_nett + bpjs_total
            ],
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Finance',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 8000000,
                'tunjangan_operasional' => 1500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 300000,
                'bpjs_ketenagakerjaan' => 200000,
                'bpjs_kecelakaan_kerja' => 150000,
                'bpjs_total' => 650000,
                'gaji_nett' => 9400000,
                'total_gaji' => 10050000,
            ],
            
            // Konsultan
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Senior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 12000000,
                'tunjangan_operasional' => 2500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 400000,
                'bpjs_ketenagakerjaan' => 250000,
                'bpjs_kecelakaan_kerja' => 180000,
                'bpjs_total' => 830000,
                'gaji_nett' => 14400000,
                'total_gaji' => 15230000,
            ],
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Project Manager',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 14000000,
                'tunjangan_operasional' => 2800000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 450000,
                'bpjs_ketenagakerjaan' => 280000,
                'bpjs_kecelakaan_kerja' => 200000,
                'bpjs_total' => 930000,
                'gaji_nett' => 16700000,
                'total_gaji' => 17630000,
            ],
            
            // Teknisi
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 6000000,
                'tunjangan_operasional' => 1000000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 250000,
                'bpjs_ketenagakerjaan' => 150000,
                'bpjs_kecelakaan_kerja' => 100000,
                'bpjs_total' => 500000,
                'gaji_nett' => 6950000,
                'total_gaji' => 7450000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'Bali',
                'gaji_pokok' => 7500000,
                'tunjangan_operasional' => 1200000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 280000,
                'bpjs_ketenagakerjaan' => 180000,
                'bpjs_kecelakaan_kerja' => 120000,
                'bpjs_total' => 580000,
                'gaji_nett' => 8650000,
                'total_gaji' => 9230000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Installer',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 5500000,
                'tunjangan_operasional' => 900000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 220000,
                'bpjs_ketenagakerjaan' => 140000,
                'bpjs_kecelakaan_kerja' => 90000,
                'bpjs_total' => 450000,
                'gaji_nett' => 6350000,
                'total_gaji' => 6800000,
            ],
            
            // Borongan (no BPJS)
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 5000000,
                'tunjangan_operasional' => 800000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 5800000,
                'total_gaji' => 5800000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 6500000,
                'tunjangan_operasional' => 1100000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 7600000,
                'total_gaji' => 7600000,
            ],
        ];

        foreach ($pengaturanGaji as $data) {
            PengaturanGaji::updateOrCreate(
                [
                    'jenis_karyawan' => $data['jenis_karyawan'],
                    'jabatan' => $data['jabatan'],
                    'lokasi_kerja' => $data['lokasi_kerja'],
                ],
                $data
            );
        }

        $this->command->info('Pengaturan Gaji seeded successfully! Processed ' . count($pengaturanGaji) . ' configurations.');
    }
}
