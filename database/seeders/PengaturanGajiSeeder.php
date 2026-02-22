<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;

class PengaturanGajiSeeder extends Seeder
{
    public function run(): void
    {
        $pengaturanGaji = [
            // Konsultan
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 15000000,
                'tunjangan_operasional' => 3000000,
                'potongan_koperasi' => 500000,
                'bpjs_kesehatan' => 150000,
                'bpjs_ketenagakerjaan' => 200000,
                'bpjs_kecelakaan_kerja' => 50000,
                'keterangan' => 'Pengaturan gaji untuk Manager Konsultan di Central Java',
            ],
            [
                'jenis_karyawan' => 'Konsultan',
                'jabatan' => 'Finance',
                'lokasi_kerja' => 'Central Java',
                'gaji_pokok' => 8000000,
                'tunjangan_operasional' => 1500000,
                'potongan_koperasi' => 300000,
                'bpjs_kesehatan' => 100000,
                'bpjs_ketenagakerjaan' => 150000,
                'bpjs_kecelakaan_kerja' => 30000,
                'keterangan' => 'Pengaturan gaji untuk Finance Konsultan di Central Java',
            ],
            
            // Organik
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 12000000,
                'tunjangan_operasional' => 2500000,
                'potongan_koperasi' => 400000,
                'bpjs_kesehatan' => 120000,
                'bpjs_ketenagakerjaan' => 180000,
                'bpjs_kecelakaan_kerja' => 40000,
                'keterangan' => 'Pengaturan gaji untuk Manager Organik di East Java',
            ],
            [
                'jenis_karyawan' => 'Organik',
                'jabatan' => 'Finance',
                'lokasi_kerja' => 'East Java',
                'gaji_pokok' => 6000000,
                'tunjangan_operasional' => 1000000,
                'potongan_koperasi' => 200000,
                'bpjs_kesehatan' => 80000,
                'bpjs_ketenagakerjaan' => 120000,
                'bpjs_kecelakaan_kerja' => 25000,
                'keterangan' => 'Pengaturan gaji untuk Finance Organik di East Java',
            ],
            
            // Teknisi
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 7000000,
                'tunjangan_operasional' => 1200000,
                'potongan_koperasi' => 250000,
                'bpjs_kesehatan' => 90000,
                'bpjs_ketenagakerjaan' => 140000,
                'bpjs_kecelakaan_kerja' => 30000,
                'keterangan' => 'Pengaturan gaji untuk Manager Teknisi di West Java',
            ],
            [
                'jenis_karyawan' => 'Teknisi',
                'jabatan' => 'Finance',
                'lokasi_kerja' => 'West Java',
                'gaji_pokok' => 4500000,
                'tunjangan_operasional' => 800000,
                'potongan_koperasi' => 150000,
                'bpjs_kesehatan' => 60000,
                'bpjs_ketenagakerjaan' => 100000,
                'bpjs_kecelakaan_kerja' => 20000,
                'keterangan' => 'Pengaturan gaji untuk Finance Teknisi di West Java',
            ],
            
            // Borongan
            [
                'jenis_karyawan' => 'Borongan',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Bali',
                'gaji_pokok' => 3500000,
                'tunjangan_operasional' => 500000,
                'potongan_koperasi' => 100000,
                'bpjs_kesehatan' => 50000,
                'bpjs_ketenagakerjaan' => 80000,
                'bpjs_kecelakaan_kerja' => 15000,
                'keterangan' => 'Pengaturan gaji untuk Manager Borongan di Bali',
            ],
        ];

        foreach ($pengaturanGaji as $data) {
            PengaturanGaji::create($data);
        }
    }
}
