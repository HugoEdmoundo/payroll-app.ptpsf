<?php

namespace App\Observers;

use App\Models\Absensi;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\PengaturanGaji;
use App\Models\NKI;

class AbsensiObserver
{
    /**
     * Handle the Absensi "created" or "updated" event.
     * Update related Acuan Gaji and Hitung Gaji when Absensi changes
     */
    public function saved(Absensi $absensi): void
    {
        // Find Acuan Gaji for this karyawan and periode
        $acuan = AcuanGaji::where('id_karyawan', $absensi->id_karyawan)
                         ->where('periode', $absensi->periode)
                         ->first();
        
        if (!$acuan) {
            return; // No Acuan Gaji to update
        }

        // Get Pengaturan Gaji
        $karyawan = $acuan->karyawan;
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return;
        }

        // Get NKI for tunjangan_prestasi calculation
        $nki = NKI::where('id_karyawan', $absensi->id_karyawan)
                 ->where('periode', $absensi->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Calculate new potongan_absensi
        $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
        $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
        $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;

        // Update Acuan Gaji
        $acuan->update([
            'potongan_absensi' => $potonganAbsensi,
        ]);

        // Update Hitung Gaji if exists
        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
        if ($hitungGaji) {
            $hitungGaji->update([
                'potongan_absensi' => $potonganAbsensi,
            ]);
        }
    }
}
