<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanGaji;
use App\Models\Karyawan;
use App\Models\SystemSetting;

class PengaturanGajiCompleteSeeder extends Seeder
{
    /**
     * Seed pengaturan gaji for ALL active employees
     */
    public function run(): void
    {
        echo "=== CREATING PENGATURAN GAJI FOR ALL EMPLOYEES ===\n\n";
        
        // Get all active Kontrak employees
        $karyawanList = Karyawan::where('status_karyawan', 'Active')->get();
        
        $created = 0;
        $skipped = 0;
        
        foreach ($karyawanList as $karyawan) {
            // Calculate status_pegawai
            $statusPegawai = $karyawan->status_pegawai;
            
            // Skip Harian and OJT (they use PengaturanGajiStatusPegawai)
            if (in_array($statusPegawai, ['Harian', 'OJT'])) {
                echo "  SKIP: {$karyawan->nama_karyawan} ({$statusPegawai}) - uses PengaturanGajiStatusPegawai\n";
                $skipped++;
                continue;
            }
            
            // Check if already exists
            $exists = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                ->where('jabatan', $karyawan->jabatan)
                ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                ->exists();
            
            if ($exists) {
                echo "  EXISTS: {$karyawan->jenis_karyawan} | {$karyawan->jabatan} | {$karyawan->lokasi_kerja}\n";
                $skipped++;
                continue;
            }
            
            // Create pengaturan gaji based on jabatan level
            $gajiPokok = $this->getGajiPokokByJabatan($karyawan->jabatan);
            $tunjanganOperasional = $gajiPokok * 0.20; // 20% of gaji pokok
            $tunjanganPrestasi = $gajiPokok * 0.15; // 15% of gaji pokok
            
            PengaturanGaji::create([
                'jenis_karyawan' => $karyawan->jenis_karyawan,
                'jabatan' => $karyawan->jabatan,
                'lokasi_kerja' => $karyawan->lokasi_kerja,
                'gaji_pokok' => $gajiPokok,
                'tunjangan_operasional' => $tunjanganOperasional,
                'tunjangan_prestasi' => $tunjanganPrestasi,
            ]);
            
            echo "  ✅ CREATED: {$karyawan->jenis_karyawan} | {$karyawan->jabatan} | {$karyawan->lokasi_kerja}\n";
            echo "     Gaji Pokok: " . number_format($gajiPokok, 0, ',', '.') . "\n";
            echo "     Tunjangan Operasional: " . number_format($tunjanganOperasional, 0, ',', '.') . "\n";
            echo "     Tunjangan Prestasi: " . number_format($tunjanganPrestasi, 0, ',', '.') . "\n\n";
            
            $created++;
        }
        
        echo "\n=== SUMMARY ===\n";
        echo "Created: {$created}\n";
        echo "Skipped: {$skipped}\n";
        
        $this->command->info('✅ Pengaturan Gaji seeded for all employees!');
    }
    
    /**
     * Get gaji pokok based on jabatan level
     */
    private function getGajiPokokByJabatan($jabatan)
    {
        $jabatan = strtolower($jabatan);
        
        // Manager level
        if (str_contains($jabatan, 'manager') || str_contains($jabatan, 'director')) {
            return 15000000;
        }
        
        // Senior level
        if (str_contains($jabatan, 'senior') || str_contains($jabatan, 'lead')) {
            return 10000000;
        }
        
        // Mid level
        if (str_contains($jabatan, 'engineer') || str_contains($jabatan, 'specialist')) {
            return 7500000;
        }
        
        // Junior level
        if (str_contains($jabatan, 'junior') || str_contains($jabatan, 'staff')) {
            return 5000000;
        }
        
        // Default
        return 6000000;
    }
}
