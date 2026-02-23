<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;

class PengaturanGajiSeeder extends Seeder
{
    public function run(): void
    {
        $pengaturan = [
            // Manager - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Jakarta',
                'gaji_pokok' => 15000000,
                'tunjangan_operasional' => 2000000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 750000,
                'bpjs_ketenagakerjaan' => 500000,
                'bpjs_kecelakaan_kerja' => 150000,
                'bpjs_total' => 1400000,
                'gaji_nett' => 16900000,
                'total_gaji' => 18300000,
                'keterangan' => 'Pengaturan gaji Manager Jakarta',
            ],
            // Supervisor - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Supervisor',
                'lokasi_kerja' => 'Jakarta',
                'gaji_pokok' => 10000000,
                'tunjangan_operasional' => 1500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 500000,
                'bpjs_ketenagakerjaan' => 350000,
                'bpjs_kecelakaan_kerja' => 100000,
                'bpjs_total' => 950000,
                'gaji_nett' => 11400000,
                'total_gaji' => 12350000,
                'keterangan' => 'Pengaturan gaji Supervisor Jakarta',
            ],
            // Staff - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Jakarta',
                'gaji_pokok' => 7000000,
                'tunjangan_operasional' => 1000000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 350000,
                'bpjs_ketenagakerjaan' => 250000,
                'bpjs_kecelakaan_kerja' => 70000,
                'bpjs_total' => 670000,
                'gaji_nett' => 7900000,
                'total_gaji' => 8570000,
                'keterangan' => 'Pengaturan gaji Staff Tetap Jakarta',
            ],
            // Staff Kontrak - Jakarta
            [
                'jenis_karyawan' => 'Kontrak',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Jakarta',
                'gaji_pokok' => 6000000,
                'tunjangan_operasional' => 800000,
                'potongan_koperasi' => 50000,
                'bpjs_kesehatan' => 300000,
                'bpjs_ketenagakerjaan' => 200000,
                'bpjs_kecelakaan_kerja' => 60000,
                'bpjs_total' => 560000,
                'gaji_nett' => 6750000,
                'total_gaji' => 7310000,
                'keterangan' => 'Pengaturan gaji Staff Kontrak Jakarta',
            ],
            // Supervisor - Bandung
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Supervisor',
                'lokasi_kerja' => 'Bandung',
                'gaji_pokok' => 9000000,
                'tunjangan_operasional' => 1300000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 450000,
                'bpjs_ketenagakerjaan' => 300000,
                'bpjs_kecelakaan_kerja' => 90000,
                'bpjs_total' => 840000,
                'gaji_nett' => 10200000,
                'total_gaji' => 11040000,
                'keterangan' => 'Pengaturan gaji Supervisor Bandung',
            ],
            // Staff - Surabaya
            [
                'jenis_karyawan' => 'Kontrak',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Surabaya',
                'gaji_pokok' => 6500000,
                'tunjangan_operasional' => 900000,
                'potongan_koperasi' => 75000,
                'bpjs_kesehatan' => 325000,
                'bpjs_ketenagakerjaan' => 220000,
                'bpjs_kecelakaan_kerja' => 65000,
                'bpjs_total' => 610000,
                'gaji_nett' => 7325000,
                'total_gaji' => 7935000,
                'keterangan' => 'Pengaturan gaji Staff Kontrak Surabaya',
            ],
        ];

        foreach ($pengaturan as $data) {
            PengaturanGaji::firstOrCreate(
                [
                    'jenis_karyawan' => $data['jenis_karyawan'],
                    'jabatan' => $data['jabatan'],
                    'lokasi_kerja' => $data['lokasi_kerja'],
                ],
                $data
            );
        }
    }
}
