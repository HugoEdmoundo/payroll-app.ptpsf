<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\PengaturanGaji;
use App\Models\Kasbon;
use Carbon\Carbon;

class AcuanGajiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Generating Acuan Gaji...');
        
        $periode = Carbon::now()->format('Y-m');
        
        // Get active employees
        $karyawanList = Karyawan::where('status_karyawan', 'Active')->get();
        
        $generated = 0;
        
        foreach ($karyawanList as $karyawan) {
            // Check if already exists
            $exists = AcuanGaji::where('id_karyawan', $karyawan->id_karyawan)
                              ->where('periode', $periode)
                              ->exists();
            
            if ($exists) {
                continue;
            }

            // Get Pengaturan Gaji
            $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                       ->where('jabatan', $karyawan->jabatan)
                                       ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                       ->first();
            
            if (!$pengaturan) {
                continue;
            }

            // Get Kasbon - handle both Langsung and Cicilan
            $kasbonTotal = 0;
            $kasbonList = Kasbon::where('id_karyawan', $karyawan->id_karyawan)
                               ->where('status_pembayaran', '!=', 'Lunas')
                               ->get();
            
            foreach ($kasbonList as $kasbon) {
                $kasbonTotal += $kasbon->getPotonganForPeriode($periode);
            }

            // Create Acuan Gaji - ONLY Kasbon from Komponen
            AcuanGaji::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                // Pendapatan (from Pengaturan Gaji)
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'bpjs_kesehatan_pendapatan' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pendapatan' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pendapatan' => $pengaturan->bpjs_ketenagakerjaan,
                'benefit_operasional' => $pengaturan->tunjangan_operasional,
                // Pengeluaran (ONLY Kasbon)
                'bpjs_kesehatan_pengeluaran' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pengeluaran' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pengeluaran' => $pengaturan->bpjs_ketenagakerjaan,
                'koperasi' => $pengaturan->potongan_koperasi,
                'kasbon' => $kasbonTotal,
                // Empty fields
                'tunjangan_prestasi' => 0,
                'potongan_absensi' => 0,
                'bpjs_jht_pendapatan' => 0,
                'bpjs_jp_pendapatan' => 0,
                'tunjangan_konjungtur' => 0,
                'benefit_ibadah' => 0,
                'benefit_komunikasi' => 0,
                'reward' => 0,
                'bpjs_jht_pengeluaran' => 0,
                'bpjs_jp_pengeluaran' => 0,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_kehadiran' => 0,
            ]);

            $generated++;
        }

        $this->command->info("✓ Generated {$generated} acuan gaji for periode {$periode}");
    }
}
