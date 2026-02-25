<?php

namespace App\Observers;

use App\Models\NKI;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\PengaturanGaji;
use App\Models\Absensi;

class NKIObserver
{
    /**
     * Handle the NKI "created" or "updated" event.
     * Update related Acuan Gaji and Hitung Gaji when NKI changes
     */
    public function saved(NKI $nki): void
    {
        // Find Acuan Gaji for this karyawan and periode
        $acuan = AcuanGaji::where('id_karyawan', $nki->id_karyawan)
                         ->where('periode', $nki->periode)
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

        // Calculate new tunjangan_prestasi
        $tunjanganPrestasi = 0;
        if ($pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Update Acuan Gaji
        $acuan->update([
            'tunjangan_prestasi' => $tunjanganPrestasi,
        ]);

        // Update Hitung Gaji if exists
        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
        if ($hitungGaji) {
            // Recalculate potongan_absensi with new tunjangan_prestasi
            $absensi = Absensi::where('id_karyawan', $nki->id_karyawan)
                             ->where('periode', $nki->periode)
                             ->first();
            
            $potonganAbsensi = 0;
            if ($absensi) {
                $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
                $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
                $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
            }

            $hitungGaji->update([
                'tunjangan_prestasi' => $tunjanganPrestasi,
                'potongan_absensi' => $potonganAbsensi,
            ]);
        }
    }
}
