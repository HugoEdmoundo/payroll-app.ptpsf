<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\NKI;
use App\Models\PengaturanGaji;
use Illuminate\Database\Seeder;

class HitungGajiSeeder extends Seeder
{
    public function run(): void
    {
        // Get all acuan gaji
        $acuanGajiList = AcuanGaji::with('karyawan')->get();

        foreach ($acuanGajiList as $acuanGaji) {
            // Check if already exists
            $exists = HitungGaji::where('karyawan_id', $acuanGaji->id_karyawan)
                ->where('periode', $acuanGaji->periode)
                ->exists();

            if ($exists) {
                continue; // Skip if already exists
            }

            // Get Pengaturan Gaji for calculations
            $karyawan = $acuanGaji->karyawan;
            $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                ->where('jabatan', $karyawan->jabatan)
                ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                ->first();

            if (! $pengaturan) {
                continue; // Skip if no pengaturan found
            }

            // Calculate NKI (Tunjangan Prestasi)
            $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                ->where('periode', $acuanGaji->periode)
                ->first();

            $tunjanganPrestasi = 0;
            if ($nki && $pengaturan->tunjangan_prestasi > 0) {
                $tunjanganPrestasi = $pengaturan->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
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

            // Create sample adjustments for some fields (optional)
            $adjustments = [];

            // Random adjustment for demonstration
            if (rand(0, 1)) {
                $adjustments['reward'] = [
                    'type' => '+',
                    'nominal' => 500000,
                    'description' => 'Bonus kinerja bulan ini',
                ];
            }

            // Create Hitung Gaji
            HitungGaji::create([
                'acuan_gaji_id' => $acuanGaji->id_acuan,
                'karyawan_id' => $acuanGaji->id_karyawan,
                'periode' => $acuanGaji->periode,
                'gaji_pokok' => $acuanGaji->gaji_pokok,
                'bpjs_kesehatan' => $acuanGaji->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja' => $acuanGaji->bpjs_kecelakaan_kerja,
                'bpjs_kematian' => $acuanGaji->bpjs_kematian,
                'bpjs_jht' => $acuanGaji->bpjs_jht,
                'bpjs_jp' => $acuanGaji->bpjs_jp,
                'tunjangan_prestasi' => $tunjanganPrestasi,
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
                'potongan_absensi' => $potonganAbsensi,
                'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
                // Adjustments
                'adjustments' => $adjustments,
                'status' => 'draft',
                'keterangan' => 'Seeded data for testing',
            ]);
        }

        $this->command->info('Hitung Gaji seeded successfully!');
    }
}
