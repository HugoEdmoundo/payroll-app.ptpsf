<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;
use App\Models\MasterWilayah;

class PengaturanGajiSeeder extends Seeder
{
    public function run(): void
    {
        $jakarta = MasterWilayah::where('kode', 'JKT')->first();
        $bandung = MasterWilayah::where('kode', 'BDG')->first();
        $surabaya = MasterWilayah::where('kode', 'SBY')->first();

        $pengaturan = [
            // Manager - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Manager',
                'wilayah_id' => $jakarta?->id,
                'gaji_pokok' => 15000000,
                'tunjangan_operasional' => 2000000,
                'tunjangan_prestasi' => 3000000,
                'tunjangan_konjungtur' => 1000000,
                'benefit_ibadah' => 500000,
                'benefit_komunikasi' => 300000,
                'benefit_operasional' => 500000,
                'bpjs_kesehatan' => 750000,
                'bpjs_kecelakaan_kerja' => 150000,
                'bpjs_kematian' => 75000,
                'bpjs_jht' => 500000,
                'bpjs_jp' => 250000,
                'potongan_koperasi' => 100000,
                'is_active' => true,
            ],
            // Supervisor - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Supervisor',
                'wilayah_id' => $jakarta?->id,
                'gaji_pokok' => 10000000,
                'tunjangan_operasional' => 1500000,
                'tunjangan_prestasi' => 2000000,
                'tunjangan_konjungtur' => 750000,
                'benefit_ibadah' => 400000,
                'benefit_komunikasi' => 250000,
                'benefit_operasional' => 400000,
                'bpjs_kesehatan' => 500000,
                'bpjs_kecelakaan_kerja' => 100000,
                'bpjs_kematian' => 50000,
                'bpjs_jht' => 350000,
                'bpjs_jp' => 175000,
                'potongan_koperasi' => 100000,
                'is_active' => true,
            ],
            // Staff - Jakarta
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Staff',
                'wilayah_id' => $jakarta?->id,
                'gaji_pokok' => 7000000,
                'tunjangan_operasional' => 1000000,
                'tunjangan_prestasi' => 1500000,
                'tunjangan_konjungtur' => 500000,
                'benefit_ibadah' => 300000,
                'benefit_komunikasi' => 200000,
                'benefit_operasional' => 300000,
                'bpjs_kesehatan' => 350000,
                'bpjs_kecelakaan_kerja' => 70000,
                'bpjs_kematian' => 35000,
                'bpjs_jht' => 250000,
                'bpjs_jp' => 125000,
                'potongan_koperasi' => 100000,
                'is_active' => true,
            ],
            // Staff Kontrak - Jakarta
            [
                'jenis_karyawan' => 'Kontrak',
                'jabatan' => 'Staff',
                'wilayah_id' => $jakarta?->id,
                'gaji_pokok' => 6000000,
                'tunjangan_operasional' => 800000,
                'tunjangan_prestasi' => 1000000,
                'tunjangan_konjungtur' => 400000,
                'benefit_ibadah' => 250000,
                'benefit_komunikasi' => 150000,
                'benefit_operasional' => 250000,
                'bpjs_kesehatan' => 300000,
                'bpjs_kecelakaan_kerja' => 60000,
                'bpjs_kematian' => 30000,
                'bpjs_jht' => 200000,
                'bpjs_jp' => 100000,
                'potongan_koperasi' => 50000,
                'is_active' => true,
            ],
            // Supervisor - Bandung
            [
                'jenis_karyawan' => 'Tetap',
                'jabatan' => 'Supervisor',
                'wilayah_id' => $bandung?->id,
                'gaji_pokok' => 9000000,
                'tunjangan_operasional' => 1300000,
                'tunjangan_prestasi' => 1800000,
                'tunjangan_konjungtur' => 650000,
                'benefit_ibadah' => 350000,
                'benefit_komunikasi' => 200000,
                'benefit_operasional' => 350000,
                'bpjs_kesehatan' => 450000,
                'bpjs_kecelakaan_kerja' => 90000,
                'bpjs_kematian' => 45000,
                'bpjs_jht' => 300000,
                'bpjs_jp' => 150000,
                'potongan_koperasi' => 100000,
                'is_active' => true,
            ],
            // Staff - Surabaya
            [
                'jenis_karyawan' => 'Kontrak',
                'jabatan' => 'Staff',
                'wilayah_id' => $surabaya?->id,
                'gaji_pokok' => 6500000,
                'tunjangan_operasional' => 900000,
                'tunjangan_prestasi' => 1200000,
                'tunjangan_konjungtur' => 450000,
                'benefit_ibadah' => 280000,
                'benefit_komunikasi' => 180000,
                'benefit_operasional' => 280000,
                'bpjs_kesehatan' => 325000,
                'bpjs_kecelakaan_kerja' => 65000,
                'bpjs_kematian' => 32500,
                'bpjs_jht' => 220000,
                'bpjs_jp' => 110000,
                'potongan_koperasi' => 75000,
                'is_active' => true,
            ],
        ];

        foreach ($pengaturan as $data) {
            PengaturanGaji::firstOrCreate(
                [
                    'jenis_karyawan' => $data['jenis_karyawan'],
                    'jabatan' => $data['jabatan'],
                    'wilayah_id' => $data['wilayah_id'],
                ],
                $data
            );
        }
    }
}
