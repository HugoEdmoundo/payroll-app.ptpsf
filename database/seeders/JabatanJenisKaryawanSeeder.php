<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JabatanJenisKaryawan;

class JabatanJenisKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            // Teknisi
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Teknisi Senior'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Teknisi Junior'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Teknisi Lapangan'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Teknisi Maintenance'],
            
            // Borongan
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Pekerja Borongan'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Mandor'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Helper'],
            
            // Konsultan
            ['jenis_karyawan' => 'Konsultan', 'jabatan' => 'Konsultan Senior'],
            ['jenis_karyawan' => 'Konsultan', 'jabatan' => 'Konsultan'],
            ['jenis_karyawan' => 'Konsultan', 'jabatan' => 'Konsultan IT'],
            ['jenis_karyawan' => 'Konsultan', 'jabatan' => 'Konsultan Keuangan'],
            
            // Organik
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Manager'],
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Supervisor'],
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Staff Admin'],
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Staff Keuangan'],
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Staff HRD'],
            ['jenis_karyawan' => 'Organik', 'jabatan' => 'Staff IT'],
        ];
        
        foreach ($mappings as $mapping) {
            JabatanJenisKaryawan::updateOrCreate(
                [
                    'jenis_karyawan' => $mapping['jenis_karyawan'],
                    'jabatan' => $mapping['jabatan'],
                ],
                $mapping
            );
        }
    }
}
