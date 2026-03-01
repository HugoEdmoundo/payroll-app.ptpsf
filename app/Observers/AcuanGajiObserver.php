<?php

namespace App\Observers;

use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\NKI;
use App\Models\Absensi;
use App\Models\PengaturanGaji;

class AcuanGajiObserver
{
    /**
     * Handle the AcuanGaji "created" event.
     * Auto-generate Hitung Gaji when Acuan Gaji is created
     */
    public function created(AcuanGaji $acuanGaji): void
    {
        // Check if Hitung Gaji already exists
        $exists = HitungGaji::where('karyawan_id', $acuanGaji->id_karyawan)
                           ->where('periode', $acuanGaji->periode)
                           ->exists();
        
        if ($exists) {
            return; // Skip if already exists
        }

        // Get karyawan
        $karyawan = $acuanGaji->karyawan;
        
        // Check if this is status pegawai (Harian/OJT only)
        $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

        // Calculate NKI (Tunjangan Prestasi) - ONLY for Kontrak (normal employees)
        // Formula: Tunjangan Prestasi (from Acuan Gaji) × Persentase Tunjangan (from NKI)
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi; // Default from acuan gaji
        if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0) {
            // Apply NKI percentage to tunjangan prestasi
            $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
        }

        // Calculate Absensi (Potongan Absensi)
        // Formula: (Absence + Tanpa Keterangan) ÷ Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = $acuanGaji->potongan_absensi; // Default from acuan gaji
        if ($absensi && $absensi->jumlah_hari_bulan > 0) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Create Hitung Gaji automatically with calculated values
        HitungGaji::create([
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan_id' => $acuanGaji->id_karyawan,
            'periode' => $acuanGaji->periode,
            'gaji_pokok' => $acuanGaji->gaji_pokok,
            'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
            'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
            'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
            'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
            'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
            'tunjangan_prestasi' => $tunjanganPrestasi, // Calculated with NKI
            'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
            'benefit_ibadah' => $acuanGaji->benefit_ibadah,
            'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
            'benefit_operasional' => $acuanGaji->benefit_operasional,
            'reward' => $acuanGaji->reward,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi, // Calculated with Absensi
            'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            'adjustments' => [],
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'keterangan' => 'Auto-generated from Acuan Gaji',
        ]);
    }

    /**
     * Handle the AcuanGaji "updated" event.
     * Update related Hitung Gaji when Acuan Gaji is updated
     */
    public function updated(AcuanGaji $acuanGaji): void
    {
        // Find related Hitung Gaji
        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuanGaji->id_acuan)->first();
        
        if (!$hitungGaji) {
            return; // No Hitung Gaji to update
        }

        // Get karyawan
        $karyawan = $acuanGaji->karyawan;
        
        // Check if this is status pegawai (Harian/OJT only)
        $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

        // Recalculate NKI = Tunjangan Prestasi (from Acuan Gaji) × Persentase Tunjangan (from NKI)
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi; // Default from acuan gaji
        if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0) {
            // Apply NKI percentage to tunjangan prestasi
            $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
        }

        // Recalculate Absensi
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = $acuanGaji->potongan_absensi; // Default from acuan gaji
        if ($absensi && $absensi->jumlah_hari_bulan > 0) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Update Hitung Gaji with all fields from Acuan Gaji + calculated values
        $hitungGaji->update([
            'gaji_pokok' => $acuanGaji->gaji_pokok,
            'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
            'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
            'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
            'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
            'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
            'tunjangan_prestasi' => $tunjanganPrestasi, // Calculated with NKI
            'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
            'benefit_ibadah' => $acuanGaji->benefit_ibadah,
            'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
            'benefit_operasional' => $acuanGaji->benefit_operasional,
            'reward' => $acuanGaji->reward,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi, // Calculated with Absensi
            'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
        ]);
    }

    /**
     * Handle the AcuanGaji "deleted" event.
     */
    public function deleted(AcuanGaji $acuanGaji): void
    {
        // Delete related Hitung Gaji
        HitungGaji::where('acuan_gaji_id', $acuanGaji->id_acuan)->delete();
    }
}
