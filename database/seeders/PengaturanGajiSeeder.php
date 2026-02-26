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
            // Teknisi - Various positions and locations
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Installer',
                'lokasi_kerja' => 'West Java',
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
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Leader',
                'lokasi_kerja' => 'Central Java',
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
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 6500000,
                'tunjangan_operasional' => 1100000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 270000,
                'bpjs_ketenagakerjaan' => 160000,
                'bpjs_kecelakaan_kerja' => 110000,
                'bpjs_total' => 540000,
                'gaji_nett' => 7550000,
                'total_gaji' => 8090000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'West Java',
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
                'jabatan' => 'Senior Leader',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 8500000,
                'tunjangan_operasional' => 1500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 320000,
                'bpjs_ketenagakerjaan' => 200000,
                'bpjs_kecelakaan_kerja' => 140000,
                'bpjs_total' => 660000,
                'gaji_nett' => 9900000,
                'total_gaji' => 10560000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 10000000,
                'tunjangan_operasional' => 2000000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 380000,
                'bpjs_ketenagakerjaan' => 240000,
                'bpjs_kecelakaan_kerja' => 160000,
                'bpjs_total' => 780000,
                'gaji_nett' => 11900000,
                'total_gaji' => 12680000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Project Manager',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 12000000,
                'tunjangan_operasional' => 2500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 450000,
                'bpjs_ketenagakerjaan' => 280000,
                'bpjs_kecelakaan_kerja' => 180000,
                'bpjs_total' => 910000,
                'gaji_nett' => 14400000,
                'total_gaji' => 15310000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 7000000,
                'tunjangan_operasional' => 1300000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 290000,
                'bpjs_ketenagakerjaan' => 170000,
                'bpjs_kecelakaan_kerja' => 115000,
                'bpjs_total' => 575000,
                'gaji_nett' => 8250000,
                'total_gaji' => 8825000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 9000000,
                'tunjangan_operasional' => 1800000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 350000,
                'bpjs_ketenagakerjaan' => 220000,
                'bpjs_kecelakaan_kerja' => 150000,
                'bpjs_total' => 720000,
                'gaji_nett' => 10700000,
                'total_gaji' => 11420000,
            ],
            
            // Borongan - Various positions (no BPJS)
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Junior Installer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 4500000,
                'tunjangan_operasional' => 700000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 5200000,
                'total_gaji' => 5200000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Junior Leader',
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
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 5500000,
                'tunjangan_operasional' => 900000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 6400000,
                'total_gaji' => 6400000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 6000000,
                'tunjangan_operasional' => 1000000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 7000000,
                'total_gaji' => 7000000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Senior Leader',
                'lokasi_kerja' => 'Central Java',
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
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 5500000,
                'tunjangan_operasional' => 900000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 6400000,
                'total_gaji' => 6400000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 7000000,
                'tunjangan_operasional' => 1200000,
                'potongan_koperasi' => 0,
                'bpjs_kesehatan' => 0,
                'bpjs_ketenagakerjaan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_total' => 0,
                'gaji_nett' => 8200000,
                'total_gaji' => 8200000,
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
