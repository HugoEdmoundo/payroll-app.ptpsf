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
                'tunjangan_prestasi' => 900000,
                'gaji_nett' => 6400000,
                'total_gaji' => 6400000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Leader',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 6000000,
                'tunjangan_prestasi' => 1000000,
                'gaji_nett' => 7000000,
                'total_gaji' => 7000000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 6500000,
                'tunjangan_prestasi' => 1100000,
                'gaji_nett' => 7600000,
                'total_gaji' => 7600000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 7500000,
                'tunjangan_prestasi' => 1200000,
                'gaji_nett' => 8700000,
                'total_gaji' => 8700000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Leader',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 8500000,
                'tunjangan_prestasi' => 1500000,
                'gaji_nett' => 10000000,
                'total_gaji' => 10000000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Senior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 10000000,
                'tunjangan_prestasi' => 2000000,
                'gaji_nett' => 12000000,
                'total_gaji' => 12000000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Project Manager',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 12000000,
                'tunjangan_prestasi' => 2500000,
                'gaji_nett' => 14500000,
                'total_gaji' => 14500000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 7000000,
                'tunjangan_prestasi' => 1300000,
                'gaji_nett' => 8300000,
                'total_gaji' => 8300000,
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 9000000,
                'tunjangan_prestasi' => 1800000,
                'gaji_nett' => 10800000,
                'total_gaji' => 10800000,
            ],
            
            // Borongan - Various positions (no BPJS, handled separately)
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Junior Installer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 4500000,
                'tunjangan_prestasi' => 700000,
                'gaji_nett' => 5200000,
                'total_gaji' => 5200000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Junior Leader',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 5000000,
                'tunjangan_prestasi' => 800000,
                'gaji_nett' => 5800000,
                'total_gaji' => 5800000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Junior Engineer',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 5500000,
                'tunjangan_prestasi' => 900000,
                'gaji_nett' => 6400000,
                'total_gaji' => 6400000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Senior Installer',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 6000000,
                'tunjangan_prestasi' => 1000000,
                'gaji_nett' => 7000000,
                'total_gaji' => 7000000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Senior Leader',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 6500000,
                'tunjangan_prestasi' => 1100000,
                'gaji_nett' => 7600000,
                'total_gaji' => 7600000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (junior)',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 5500000,
                'tunjangan_prestasi' => 900000,
                'gaji_nett' => 6400000,
                'total_gaji' => 6400000,
            ],
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Team Leader (senior)',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 7000000,
                'tunjangan_prestasi' => 1200000,
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
