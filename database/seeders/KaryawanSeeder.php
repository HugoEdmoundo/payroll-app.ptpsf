<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use Carbon\Carbon;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanData = [
            // Teknisi - West Java (8 orang)
            ['nama' => 'Indah Permata', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Engineer', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Joko Widodo', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Engineer', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Kurniawan Adi', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Leader', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Linda Sari', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Leader', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Muhammad Rizki', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Installer', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Nur Azizah', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Installer', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Rina Wati', 'jenis' => 'Teknisi', 'jabatan' => 'Project Manager', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            ['nama' => 'Slamet Riyadi', 'jenis' => 'Teknisi', 'jabatan' => 'Team Leader (junior)', 'lokasi' => 'West Java', 'status' => 'Kontrak'],
            
            // Borongan - West Java (6 orang)
            ['nama' => 'Omar Bakri', 'jenis' => 'Borongan', 'jabatan' => 'Junior Leader', 'lokasi' => 'West Java', 'status' => 'Harian'],
            ['nama' => 'Putra Mahendra', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'West Java', 'status' => 'Harian'],
            ['nama' => 'Qori Amalia', 'jenis' => 'Borongan', 'jabatan' => 'Junior Engineer', 'lokasi' => 'West Java', 'status' => 'Harian'],
            ['nama' => 'Taufik Hidayat', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'West Java', 'status' => 'Harian'],
            ['nama' => 'Udin Sedunia', 'jenis' => 'Borongan', 'jabatan' => 'Senior Installer', 'lokasi' => 'West Java', 'status' => 'Harian'],
            ['nama' => 'Vivi Lestari', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'West Java', 'status' => 'Harian'],
            
            // Teknisi - Central Java (7 orang)
            ['nama' => 'Xaverius Budi', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Engineer', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Yanti Kusuma', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Leader', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Zaki Rahman', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Leader', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Andi Wijaya', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Engineer', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Bambang Sutrisno', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Installer', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Citra Dewi', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Installer', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            ['nama' => 'Dedi Supardi', 'jenis' => 'Teknisi', 'jabatan' => 'Team Leader (senior)', 'lokasi' => 'Central Java', 'status' => 'Kontrak'],
            
            // Borongan - Central Java (5 orang)
            ['nama' => 'Agus Salim', 'jenis' => 'Borongan', 'jabatan' => 'Senior Installer', 'lokasi' => 'Central Java', 'status' => 'Harian'],
            ['nama' => 'Bella Safira', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'Central Java', 'status' => 'Harian'],
            ['nama' => 'Cahyo Nugroho', 'jenis' => 'Borongan', 'jabatan' => 'Junior Engineer', 'lokasi' => 'Central Java', 'status' => 'Harian'],
            ['nama' => 'Dian Purnama', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'Central Java', 'status' => 'Harian'],
            ['nama' => 'Eko Saputra', 'jenis' => 'Borongan', 'jabatan' => 'Senior Leader', 'lokasi' => 'Central Java', 'status' => 'Harian'],
            
            // Teknisi - East Java (5 orang)
            ['nama' => 'Gita Savitri', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Engineer', 'lokasi' => 'East Java', 'status' => 'Kontrak'],
            ['nama' => 'Hadi Purnomo', 'jenis' => 'Teknisi', 'jabatan' => 'Team Leader (senior)', 'lokasi' => 'East Java', 'status' => 'Kontrak'],
            ['nama' => 'Ika Rahmawati', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Leader', 'lokasi' => 'East Java', 'status' => 'Kontrak'],
            ['nama' => 'Jaya Kusuma', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Engineer', 'lokasi' => 'East Java', 'status' => 'Kontrak'],
            ['nama' => 'Kartini Sari', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Installer', 'lokasi' => 'East Java', 'status' => 'Kontrak'],
            
            // Borongan - East Java (4 orang)
            ['nama' => 'Jaka Sembung', 'jenis' => 'Borongan', 'jabatan' => 'Senior Installer', 'lokasi' => 'East Java', 'status' => 'Harian'],
            ['nama' => 'Kartika Sari', 'jenis' => 'Borongan', 'jabatan' => 'Junior Engineer', 'lokasi' => 'East Java', 'status' => 'Harian'],
            ['nama' => 'Lukman Hakim', 'jenis' => 'Borongan', 'jabatan' => 'Junior Installer', 'lokasi' => 'East Java', 'status' => 'Harian'],
            ['nama' => 'Maya Anggraini', 'jenis' => 'Borongan', 'jabatan' => 'Team Leader (junior)', 'lokasi' => 'East Java', 'status' => 'Harian'],
        ];

        foreach ($karyawanData as $index => $data) {
            $email = strtolower(str_replace(' ', '.', $data['nama'])) . '@ptpsf.com';
            
            Karyawan::updateOrCreate(
                ['email' => $email],
                [
                    'nama_karyawan' => $data['nama'],
                    'no_telp' => '08' . rand(1000000000, 9999999999),
                    'tanggal_bergabung' => Carbon::now()->subMonths(rand(1, 36)),
                    'jenis_karyawan' => $data['jenis'],
                    'jabatan' => $data['jabatan'],
                    'lokasi_kerja' => $data['lokasi'],
                    'status_pegawai' => $data['status'],
                    'status_karyawan' => 'Active',
                    'bank' => ['BCA', 'Mandiri', 'BNI', 'BRI'][rand(0, 3)],
                    'no_rekening' => rand(1000000000, 9999999999),
                    'npwp' => rand(10, 99) . '.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-' . rand(100, 999) . '.000',
                    'no_bpjs_kesehatan' => '0001' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'no_bpjs_ketenagakerjaan' => '1001' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'jumlah_tanggungan_keluarga' => rand(0, 3),
                ]
            );
        }
    }
}
