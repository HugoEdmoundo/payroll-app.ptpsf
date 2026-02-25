<?php

namespace App\Observers;

use App\Models\PengaturanGaji;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\NKI;
use App\Models\Absensi;

class PengaturanGajiObserver
{
    /**
     * Handle the PengaturanGaji "updated" event.
     * When Pengaturan Gaji changes, update all related Acuan Gaji and Hitung Gaji
     */
    public function updated(PengaturanGaji $pengaturan): void
    {
        // Find all Acuan Gaji with matching criteria
        $acuanGajiList = AcuanGaji::whereHas('karyawan', function($q) use ($pengaturan) {
            $q->where('jenis_karyawan', $pengaturan->jenis_karyawan)
              ->where('jabatan', $pengaturan->jabatan)
              ->where('lokasi_kerja', $pengaturan->lokasi_kerja);
        })->get();
        
        foreach ($acuanGajiList as $acuan) {
            // Update Acuan Gaji with new values from Pengaturan
            $acuan->update([
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'bpjs_kesehatan_pendapatan' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pendapatan' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pendapatan' => $pengaturan->bpjs_ketenagakerjaan,
                'benefit_operasional' => $pengaturan->tunjangan_operasional,
                'bpjs_kesehatan_pengeluaran' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pengeluaran' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pengeluaran' => $pengaturan->bpjs_ketenagakerjaan,
                'koperasi' => $pengaturan->potongan_koperasi,
            ]);
            
            // Find and update related Hitung Gaji
            $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
            if ($hitungGaji) {
                $this->recalculateHitungGaji($hitungGaji, $acuan, $pengaturan);
            }
        }
    }

    /**
     * Recalculate Hitung Gaji based on updated Acuan Gaji and Pengaturan
     */
    private function recalculateHitungGaji($hitungGaji, $acuan, $pengaturan)
    {
        // Get fresh NKI data
        $nki = NKI::where('id_karyawan', $acuan->id_karyawan)
                  ->where('periode', $acuan->periode)
                  ->first();
        
        // Recalculate tunjangan_prestasi
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }
        
        // Get fresh Absensi data
        $absensi = Absensi::where('id_karyawan', $acuan->id_karyawan)
                          ->where('periode', $acuan->periode)
                          ->first();
        
        // Recalculate potongan_absensi
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }
        
        // Update Hitung Gaji
        $hitungGaji->update([
            'gaji_pokok' => $acuan->gaji_pokok,
            'bpjs_kesehatan_pendapatan' => $acuan->bpjs_kesehatan_pendapatan,
            'bpjs_kecelakaan_kerja_pendapatan' => $acuan->bpjs_kecelakaan_kerja_pendapatan,
            'bpjs_kematian_pendapatan' => $acuan->bpjs_kematian_pendapatan,
            'benefit_operasional' => $acuan->benefit_operasional,
            'tunjangan_prestasi' => $tunjanganPrestasi,
            'potongan_absensi' => $potonganAbsensi,
            'bpjs_kesehatan_pengeluaran' => $acuan->bpjs_kesehatan_pengeluaran,
            'bpjs_kecelakaan_kerja_pengeluaran' => $acuan->bpjs_kecelakaan_kerja_pengeluaran,
            'bpjs_kematian_pengeluaran' => $acuan->bpjs_kematian_pengeluaran,
            'koperasi' => $acuan->koperasi,
        ]);
    }
}
