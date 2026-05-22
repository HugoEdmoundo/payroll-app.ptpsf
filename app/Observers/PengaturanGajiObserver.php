<?php

namespace App\Observers;

use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\PengaturanGaji;
use App\Services\SalaryCalculationService;

class PengaturanGajiObserver
{
    public function updated(PengaturanGaji $pengaturan): void
    {
        $acuanList = AcuanGaji::whereHas('karyawan', function ($q) use ($pengaturan) {
            $q->where('jenis_karyawan', $pengaturan->jenis_karyawan)
                ->where('jabatan', $pengaturan->jabatan)
                ->where('lokasi_kerja', $pengaturan->lokasi_kerja);
        })->get();

        $service = app(SalaryCalculationService::class);

        foreach ($acuanList as $acuan) {
            $acuan->updateQuietly([
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'benefit_operasional' => $pengaturan->tunjangan_operasional,
                'tunjangan_prestasi' => $pengaturan->tunjangan_prestasi,
            ]);

            $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
            if ($hitungGaji) {
                $data = $service->buildHitungGajiData($acuan);
                $hitungGaji->updateQuietly($data);
            }
        }
    }
}
