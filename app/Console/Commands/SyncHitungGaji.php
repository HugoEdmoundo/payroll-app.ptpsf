<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HitungGaji;
use App\Models\AcuanGaji;
use App\Models\NKI;
use App\Models\Absensi;

class SyncHitungGaji extends Command
{
    protected $signature = 'hitung-gaji:sync';
    protected $description = 'Sync Hitung Gaji data with Acuan Gaji and recalculate NKI & Absensi';

    public function handle()
    {
        $this->info('Starting Hitung Gaji synchronization...');
        
        $hitungGajiList = HitungGaji::with(['acuanGaji', 'karyawan'])->get();
        $updated = 0;
        
        foreach ($hitungGajiList as $hitungGaji) {
            $acuanGaji = $hitungGaji->acuanGaji;
            
            if (!$acuanGaji) {
                $this->warn("Acuan Gaji not found for Hitung Gaji ID: {$hitungGaji->id}");
                continue;
            }
            
            $karyawan = $hitungGaji->karyawan;
            if (!$karyawan) {
                $this->warn("Karyawan not found for Hitung Gaji ID: {$hitungGaji->id}");
                continue;
            }
            
            // Check if this is status pegawai (Harian/OJT only)
            $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);
            
            // Calculate NKI (Tunjangan Prestasi)
            $nki = NKI::where('id_karyawan', $karyawan->id_karyawan)
                     ->where('periode', $hitungGaji->periode)
                     ->first();
            
            $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi;
            if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0) {
                $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
            }
            
            // Calculate Absensi (Potongan Absensi)
            $absensi = Absensi::where('id_karyawan', $karyawan->id_karyawan)
                             ->where('periode', $hitungGaji->periode)
                             ->first();
            
            $potonganAbsensi = $acuanGaji->potongan_absensi;
            if ($absensi && $absensi->jumlah_hari_bulan > 0) {
                $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
                $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
                $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
            }
            
            // Update Hitung Gaji
            $hitungGaji->update([
                'gaji_pokok' => $acuanGaji->gaji_pokok,
                'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
                'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
                'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
                'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
                'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
                'tunjangan_prestasi' => $tunjanganPrestasi,
                'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
                'benefit_ibadah' => $acuanGaji->benefit_ibadah,
                'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
                'benefit_operasional' => $acuanGaji->benefit_operasional,
                'reward' => $acuanGaji->reward,
                'bpjs_kesehatan_pengeluaran' => $acuanGaji->bpjs_kesehatan_pengeluaran,
                'bpjs_kecelakaan_kerja_pengeluaran' => $acuanGaji->bpjs_kecelakaan_kerja_pengeluaran,
                'bpjs_kematian_pengeluaran' => $acuanGaji->bpjs_kematian_pengeluaran,
                'bpjs_jht_pengeluaran' => $acuanGaji->bpjs_jht_pengeluaran,
                'bpjs_jp_pengeluaran' => $acuanGaji->bpjs_jp_pengeluaran,
                'koperasi' => $acuanGaji->koperasi,
                'kasbon' => $acuanGaji->kasbon,
                'umroh' => $acuanGaji->umroh,
                'kurban' => $acuanGaji->kurban,
                'mutabaah' => $acuanGaji->mutabaah,
                'potongan_absensi' => $potonganAbsensi,
                'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            ]);
            
            $updated++;
        }
        
        $this->info("Successfully synchronized {$updated} Hitung Gaji records.");
        
        return 0;
    }
}
