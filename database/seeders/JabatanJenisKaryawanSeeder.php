<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JabatanJenisKaryawan;

class JabatanJenisKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        // Check if table exists first
        if (!\Schema::hasTable('jabatan_jenis_karyawan')) {
            $this->command->warn('Table jabatan_jenis_karyawan does not exist. Skipping...');
            $this->command->info('Please run migrations first: php artisan migrate');
            return;
        }
        
        $mappings = [
            // Teknisi - All 9 positions available
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Junior Installer'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Junior Leader'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Junior Engineer'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Senior Installer'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Senior Leader'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Senior Engineer'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Project Manager'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Team Leader (senior)'],
            ['jenis_karyawan' => 'Teknisi', 'jabatan' => 'Team Leader (junior)'],
            
            // Borongan - All 9 positions available
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Junior Installer'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Junior Leader'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Junior Engineer'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Senior Installer'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Senior Leader'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Senior Engineer'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Project Manager'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Team Leader (senior)'],
            ['jenis_karyawan' => 'Borongan', 'jabatan' => 'Team Leader (junior)'],
        ];
        
        foreach ($mappings as $mapping) {
            \DB::table('jabatan_jenis_karyawan')->updateOrInsert(
                [
                    'jenis_karyawan' => $mapping['jenis_karyawan'],
                    'jabatan' => $mapping['jabatan'],
                ],
                array_merge($mapping, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
        
        $this->command->info('Jabatan Jenis Karyawan seeded successfully!');
    }
}
