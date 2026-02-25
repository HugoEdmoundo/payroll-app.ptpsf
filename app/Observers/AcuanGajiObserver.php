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

        // Get Pengaturan Gaji for calculations
        $karyawan = $acuanGaji->karyawan;
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return; // Skip if no pengaturan
        }

        // Calculate NKI (Tunjangan Prestasi)
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Calculate Absensi (Potongan Absensi)
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Create Hitung Gaji automatically
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
            'tabungan_koperasi' => $acuanGaji->tabungan_koperasi,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi,
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

        // Get Pengaturan Gaji for recalculation
        $karyawan = $acuanGaji->karyawan;
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return;
        }

        // Recalculate NKI
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Recalculate Absensi
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
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
            'tabungan_koperasi' => $acuanGaji->tabungan_koperasi,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi,
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
