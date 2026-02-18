<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterWilayah;
use App\Models\MasterStatusPegawai;
use App\Models\KomponenGaji;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Master Wilayah
        $wilayah = [
            ['kode' => 'CJ', 'nama' => 'Central Java', 'keterangan' => 'Jawa Tengah', 'is_active' => true],
            ['kode' => 'EJ', 'nama' => 'East Java', 'keterangan' => 'Jawa Timur', 'is_active' => true],
            ['kode' => 'WJ', 'nama' => 'West Java', 'keterangan' => 'Jawa Barat', 'is_active' => true],
        ];

        foreach ($wilayah as $w) {
            MasterWilayah::create($w);
        }

        // Master Status Pegawai
        $statusPegawai = [
            ['nama' => 'Harian', 'durasi_hari' => 14, 'keterangan' => '14 hari pertama sejak join', 'gunakan_nki' => false, 'is_active' => true, 'order' => 1],
            ['nama' => 'OJT', 'durasi_hari' => 90, 'keterangan' => '3 bulan setelah fase harian', 'gunakan_nki' => false, 'is_active' => true, 'order' => 2],
            ['nama' => 'Kontrak', 'durasi_hari' => null, 'keterangan' => 'Setelah OJT selesai', 'gunakan_nki' => true, 'is_active' => true, 'order' => 3],
        ];

        foreach ($statusPegawai as $sp) {
            MasterStatusPegawai::create($sp);
        }

        // Komponen Gaji - Pendapatan
        $komponenPendapatan = [
            ['nama' => 'Gaji Pokok', 'kode' => 'gaji_pokok', 'tipe' => 'pendapatan', 'kategori' => 'gaji', 'deskripsi' => 'Gaji pokok karyawan', 'is_system' => true, 'is_active' => true, 'order' => 1],
            ['nama' => 'Tunjangan Prestasi', 'kode' => 'tunjangan_prestasi', 'tipe' => 'pendapatan', 'kategori' => 'tunjangan', 'deskripsi' => 'Tunjangan berdasarkan NKI', 'is_system' => true, 'is_active' => true, 'order' => 2],
            ['nama' => 'Tunjangan Konjungtur', 'kode' => 'tunjangan_konjungtur', 'tipe' => 'pendapatan', 'kategori' => 'tunjangan', 'deskripsi' => 'Tunjangan konjungtur', 'is_system' => true, 'is_active' => true, 'order' => 3],
            ['nama' => 'Benefit Ibadah', 'kode' => 'benefit_ibadah', 'tipe' => 'pendapatan', 'kategori' => 'benefit', 'deskripsi' => 'Benefit ibadah', 'is_system' => true, 'is_active' => true, 'order' => 4],
            ['nama' => 'Benefit Komunikasi', 'kode' => 'benefit_komunikasi', 'tipe' => 'pendapatan', 'kategori' => 'benefit', 'deskripsi' => 'Benefit komunikasi', 'is_system' => true, 'is_active' => true, 'order' => 5],
            ['nama' => 'Benefit Operasional', 'kode' => 'benefit_operasional', 'tipe' => 'pendapatan', 'kategori' => 'benefit', 'deskripsi' => 'Benefit operasional', 'is_system' => true, 'is_active' => true, 'order' => 6],
            ['nama' => 'BPJS Kesehatan', 'kode' => 'bpjs_kesehatan', 'tipe' => 'pendapatan', 'kategori' => 'bpjs', 'deskripsi' => 'BPJS Kesehatan', 'is_system' => true, 'is_active' => true, 'order' => 7],
            ['nama' => 'BPJS Kecelakaan Kerja', 'kode' => 'bpjs_kecelakaan_kerja', 'tipe' => 'pendapatan', 'kategori' => 'bpjs', 'deskripsi' => 'BPJS Kecelakaan Kerja', 'is_system' => true, 'is_active' => true, 'order' => 8],
            ['nama' => 'BPJS Kematian', 'kode' => 'bpjs_kematian', 'tipe' => 'pendapatan', 'kategori' => 'bpjs', 'deskripsi' => 'BPJS Kematian', 'is_system' => true, 'is_active' => true, 'order' => 9],
            ['nama' => 'BPJS JHT', 'kode' => 'bpjs_jht', 'tipe' => 'pendapatan', 'kategori' => 'bpjs', 'deskripsi' => 'BPJS Jaminan Hari Tua', 'is_system' => true, 'is_active' => true, 'order' => 10],
            ['nama' => 'BPJS JP', 'kode' => 'bpjs_jp', 'tipe' => 'pendapatan', 'kategori' => 'bpjs', 'deskripsi' => 'BPJS Jaminan Pensiun', 'is_system' => true, 'is_active' => true, 'order' => 11],
            ['nama' => 'Reward', 'kode' => 'reward', 'tipe' => 'pendapatan', 'kategori' => 'benefit', 'deskripsi' => 'Reward tambahan', 'is_system' => false, 'is_active' => true, 'order' => 12],
        ];

        foreach ($komponenPendapatan as $kp) {
            KomponenGaji::create($kp);
        }

        // Komponen Gaji - Pengeluaran
        $komponenPengeluaran = [
            ['nama' => 'Potongan BPJS Kesehatan', 'kode' => 'potongan_bpjs_kesehatan', 'tipe' => 'pengeluaran', 'kategori' => 'bpjs', 'deskripsi' => 'Potongan BPJS Kesehatan', 'is_system' => true, 'is_active' => true, 'order' => 1],
            ['nama' => 'Potongan BPJS Kecelakaan', 'kode' => 'potongan_bpjs_kecelakaan', 'tipe' => 'pengeluaran', 'kategori' => 'bpjs', 'deskripsi' => 'Potongan BPJS Kecelakaan Kerja', 'is_system' => true, 'is_active' => true, 'order' => 2],
            ['nama' => 'Potongan BPJS Kematian', 'kode' => 'potongan_bpjs_kematian', 'tipe' => 'pengeluaran', 'kategori' => 'bpjs', 'deskripsi' => 'Potongan BPJS Kematian', 'is_system' => true, 'is_active' => true, 'order' => 3],
            ['nama' => 'Potongan BPJS JHT', 'kode' => 'potongan_bpjs_jht', 'tipe' => 'pengeluaran', 'kategori' => 'bpjs', 'deskripsi' => 'Potongan BPJS JHT', 'is_system' => true, 'is_active' => true, 'order' => 4],
            ['nama' => 'Potongan BPJS JP', 'kode' => 'potongan_bpjs_jp', 'tipe' => 'pengeluaran', 'kategori' => 'bpjs', 'deskripsi' => 'Potongan BPJS JP', 'is_system' => true, 'is_active' => true, 'order' => 5],
            ['nama' => 'Potongan Koperasi', 'kode' => 'potongan_koperasi', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan koperasi', 'is_system' => true, 'is_active' => true, 'order' => 6],
            ['nama' => 'Tabungan Koperasi', 'kode' => 'tabungan_koperasi', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Tabungan koperasi', 'is_system' => false, 'is_active' => true, 'order' => 7],
            ['nama' => 'Kasbon', 'kode' => 'kasbon', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan kasbon', 'is_system' => true, 'is_active' => true, 'order' => 8],
            ['nama' => 'Umroh', 'kode' => 'umroh', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan umroh', 'is_system' => false, 'is_active' => true, 'order' => 9],
            ['nama' => 'Kurban', 'kode' => 'kurban', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan kurban', 'is_system' => false, 'is_active' => true, 'order' => 10],
            ['nama' => 'Mutabaah', 'kode' => 'mutabaah', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan mutabaah', 'is_system' => false, 'is_active' => true, 'order' => 11],
            ['nama' => 'Potongan Absensi', 'kode' => 'potongan_absensi', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan absensi', 'is_system' => true, 'is_active' => true, 'order' => 12],
            ['nama' => 'Potongan Kehadiran', 'kode' => 'potongan_kehadiran', 'tipe' => 'pengeluaran', 'kategori' => 'potongan', 'deskripsi' => 'Potongan kehadiran', 'is_system' => false, 'is_active' => true, 'order' => 13],
        ];

        foreach ($komponenPengeluaran as $kp) {
            KomponenGaji::create($kp);
        }
    }
}
