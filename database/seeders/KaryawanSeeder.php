<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\MasterStatusPegawai;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        // Create master status pegawai first
        $statusPegawai = [
            ['nama' => 'Tetap', 'keterangan' => 'Karyawan Tetap', 'gunakan_nki' => true, 'durasi_hari' => null],
            ['nama' => 'Kontrak', 'keterangan' => 'Karyawan Kontrak', 'gunakan_nki' => true, 'durasi_hari' => null],
            ['nama' => 'Magang', 'keterangan' => 'Karyawan Magang', 'gunakan_nki' => false, 'durasi_hari' => 90],
            ['nama' => 'Harian', 'keterangan' => 'Karyawan Harian', 'gunakan_nki' => false, 'durasi_hari' => 14],
            ['nama' => 'OJT', 'keterangan' => 'On Job Training', 'gunakan_nki' => false, 'durasi_hari' => 90],
        ];

        foreach ($statusPegawai as $status) {
            MasterStatusPegawai::firstOrCreate(
                ['nama' => $status['nama']],
                $status
            );
        }

        $karyawan = [
            [
                'nama_karyawan' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@psf.com',
                'no_telp' => '081234567890',
                'join_date' => '2023-01-15',
                'jabatan' => 'Manager',
                'lokasi_kerja' => 'Jakarta',
                'jenis_karyawan' => 'Tetap',
                'status_pegawai' => 'Tetap',
                'no_rekening' => '1234567890',
                'bank' => 'BCA',
                'status_karyawan' => 'Aktif',
            ],
            [
                'nama_karyawan' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@psf.com',
                'no_telp' => '081234567891',
                'join_date' => '2023-02-20',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Jakarta',
                'jenis_karyawan' => 'Tetap',
                'status_pegawai' => 'Tetap',
                'no_rekening' => '1234567891',
                'bank' => 'Mandiri',
                'status_karyawan' => 'Aktif',
            ],
            [
                'nama_karyawan' => 'Budi Santoso',
                'email' => 'budi.santoso@psf.com',
                'no_telp' => '081234567892',
                'join_date' => '2023-03-10',
                'jabatan' => 'Supervisor',
                'lokasi_kerja' => 'Bandung',
                'jenis_karyawan' => 'Tetap',
                'status_pegawai' => 'Tetap',
                'no_rekening' => '1234567892',
                'bank' => 'BRI',
                'status_karyawan' => 'Aktif',
            ],
            [
                'nama_karyawan' => 'Dewi Lestari',
                'email' => 'dewi.lestari@psf.com',
                'no_telp' => '081234567893',
                'join_date' => '2023-04-05',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Surabaya',
                'jenis_karyawan' => 'Kontrak',
                'status_pegawai' => 'Kontrak',
                'no_rekening' => '1234567893',
                'bank' => 'BNI',
                'status_karyawan' => 'Aktif',
            ],
            [
                'nama_karyawan' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@psf.com',
                'no_telp' => '081234567894',
                'join_date' => '2023-05-12',
                'jabatan' => 'Staff',
                'lokasi_kerja' => 'Jakarta',
                'jenis_karyawan' => 'Tetap',
                'status_pegawai' => 'Tetap',
                'no_rekening' => '1234567894',
                'bank' => 'BCA',
                'status_karyawan' => 'Aktif',
            ],
        ];

        foreach ($karyawan as $data) {
            Karyawan::firstOrCreate(
                ['nama_karyawan' => $data['nama_karyawan'], 'email' => $data['email']],
                $data
            );
        }
    }
}
