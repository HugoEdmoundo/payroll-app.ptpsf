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
            // Konsultan - Jakarta
            ['nama' => 'Ahmad Fauzi', 'jenis' => 'Konsultan', 'jabatan' => 'Senior Consultant', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Siti Nurhaliza', 'jenis' => 'Konsultan', 'jabatan' => 'Consultant', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Budi Santoso', 'jenis' => 'Konsultan', 'jabatan' => 'Junior Consultant', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Dewi Lestari', 'jenis' => 'Konsultan', 'jabatan' => 'Senior Consultant', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Eko Prasetyo', 'jenis' => 'Konsultan', 'jabatan' => 'Consultant', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            
            // Organik - Jakarta
            ['nama' => 'Fitri Handayani', 'jenis' => 'Organik', 'jabatan' => 'Manager', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Gunawan Wijaya', 'jenis' => 'Organik', 'jabatan' => 'Supervisor', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Hendra Kusuma', 'jenis' => 'Organik', 'jabatan' => 'Staff', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Indah Permata', 'jenis' => 'Organik', 'jabatan' => 'Staff', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Joko Widodo', 'jenis' => 'Organik', 'jabatan' => 'Coordinator', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            
            // Teknisi - Jakarta
            ['nama' => 'Kurniawan Adi', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Technician', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Linda Sari', 'jenis' => 'Teknisi', 'jabatan' => 'Technician', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Muhammad Rizki', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Technician', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Nur Azizah', 'jenis' => 'Teknisi', 'jabatan' => 'Technician', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            ['nama' => 'Omar Bakri', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Technician', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
            
            // Borongan - Jakarta
            ['nama' => 'Putra Mahendra', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Jakarta', 'status' => 'Harian'],
            ['nama' => 'Qori Amalia', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Jakarta', 'status' => 'Harian'],
            ['nama' => 'Rudi Hartono', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Jakarta', 'status' => 'Harian'],
            ['nama' => 'Sari Wulandari', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Jakarta', 'status' => 'Harian'],
            ['nama' => 'Tono Sugiarto', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Jakarta', 'status' => 'Harian'],
            
            // Konsultan - Bandung
            ['nama' => 'Umar Faruq', 'jenis' => 'Konsultan', 'jabatan' => 'Senior Consultant', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Vina Melinda', 'jenis' => 'Konsultan', 'jabatan' => 'Consultant', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Wawan Setiawan', 'jenis' => 'Konsultan', 'jabatan' => 'Junior Consultant', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            
            // Organik - Bandung
            ['nama' => 'Xaverius Budi', 'jenis' => 'Organik', 'jabatan' => 'Manager', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Yanti Kusuma', 'jenis' => 'Organik', 'jabatan' => 'Supervisor', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Zaki Rahman', 'jenis' => 'Organik', 'jabatan' => 'Staff', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            
            // Teknisi - Bandung
            ['nama' => 'Agus Salim', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Technician', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Bella Safira', 'jenis' => 'Teknisi', 'jabatan' => 'Technician', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            ['nama' => 'Candra Wijaya', 'jenis' => 'Teknisi', 'jabatan' => 'Junior Technician', 'lokasi' => 'Bandung', 'status' => 'Kontrak'],
            
            // Borongan - Bandung
            ['nama' => 'Dani Pratama', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Bandung', 'status' => 'Harian'],
            ['nama' => 'Erna Susanti', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Bandung', 'status' => 'Harian'],
            
            // Konsultan - Surabaya
            ['nama' => 'Fajar Nugroho', 'jenis' => 'Konsultan', 'jabatan' => 'Senior Consultant', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            ['nama' => 'Gita Savitri', 'jenis' => 'Konsultan', 'jabatan' => 'Consultant', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            
            // Organik - Surabaya
            ['nama' => 'Hadi Purnomo', 'jenis' => 'Organik', 'jabatan' => 'Manager', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            ['nama' => 'Ika Rahmawati', 'jenis' => 'Organik', 'jabatan' => 'Supervisor', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            
            // Teknisi - Surabaya
            ['nama' => 'Jaka Sembung', 'jenis' => 'Teknisi', 'jabatan' => 'Senior Technician', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            ['nama' => 'Kartika Sari', 'jenis' => 'Teknisi', 'jabatan' => 'Technician', 'lokasi' => 'Surabaya', 'status' => 'Kontrak'],
            
            // Borongan - Surabaya
            ['nama' => 'Lukman Hakim', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Surabaya', 'status' => 'Harian'],
            ['nama' => 'Maya Anggraini', 'jenis' => 'Borongan', 'jabatan' => 'Contract Worker', 'lokasi' => 'Surabaya', 'status' => 'Harian'],
            
            // Additional employees
            ['nama' => 'Nanda Pratiwi', 'jenis' => 'Organik', 'jabatan' => 'Staff', 'lokasi' => 'Jakarta', 'status' => 'Kontrak'],
        ];

        foreach ($karyawanData as $index => $data) {
            Karyawan::create([
                'nama_karyawan' => $data['nama'],
                'email' => strtolower(str_replace(' ', '.', $data['nama'])) . '@ptpsf.com',
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
            ]);
        }
    }
}
