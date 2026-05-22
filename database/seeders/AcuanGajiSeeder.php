<?php

namespace Database\Seeders;

use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\PengaturanGaji;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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

            if (! $pengaturan) {
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

            // Create Acuan Gaji
            AcuanGaji::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'bpjs_kesehatan' => 0,
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_kematian' => 0,
                'bpjs_jht' => 0,
                'bpjs_jp' => 0,
                'tunjangan_prestasi' => 0,
                'tunjangan_konjungtur' => 0,
                'benefit_ibadah' => 0,
                'benefit_komunikasi' => 0,
                'benefit_operasional' => $pengaturan->tunjangan_operasional,
                'reward' => 0,
                'koperasi' => 0,
                'kasbon' => $kasbonTotal,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_absensi' => 0,
                'potongan_kehadiran' => 0,
            ]);

            $generated++;
        }

        $this->command->info("✓ Generated {$generated} acuan gaji for periode {$periode}");
    }
}
