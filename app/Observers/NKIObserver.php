<?php

namespace App\Observers;

use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\NKI;
use App\Services\SalaryCalculationService;

class NKIObserver
{
    public function saved(NKI $nki): void
    {
        $acuan = AcuanGaji::where('id_karyawan', $nki->id_karyawan)
            ->where('periode', $nki->periode)
            ->first();

        if (! $acuan) {
            return;
        }

        $service = app(SalaryCalculationService::class);
        $data = $service->buildHitungGajiData($acuan);

        // Update acuan with recalculated tunjangan_prestasi
        $acuan->updateQuietly([
            'tunjangan_prestasi' => $data['tunjangan_prestasi'],
        ]);

        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
        if ($hitungGaji) {
            $hitungGaji->updateQuietly($data);
        }
    }
}
