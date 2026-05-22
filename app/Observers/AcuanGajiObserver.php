<?php

namespace App\Observers;

use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Services\SalaryCalculationService;

class AcuanGajiObserver
{
    public function created(AcuanGaji $acuanGaji): void
    {
        $exists = HitungGaji::where('karyawan_id', $acuanGaji->id_karyawan)
            ->where('periode', $acuanGaji->periode)
            ->exists();

        if ($exists) {
            return;
        }

        $service = app(SalaryCalculationService::class);
        $data = $service->buildHitungGajiData($acuanGaji);

        HitungGaji::create(array_merge($data, [
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan_id' => $acuanGaji->id_karyawan,
            'periode' => $acuanGaji->periode,
            'adjustments' => [],
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'keterangan' => 'Auto-generated from Acuan Gaji',
        ]));
    }

    public function updated(AcuanGaji $acuanGaji): void
    {
        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuanGaji->id_acuan)->first();

        if (! $hitungGaji) {
            return;
        }

        $service = app(SalaryCalculationService::class);
        $data = $service->buildHitungGajiData($acuanGaji);

        $hitungGaji->updateQuietly($data);
    }

    public function deleted(AcuanGaji $acuanGaji): void
    {
        HitungGaji::where('acuan_gaji_id', $acuanGaji->id_acuan)->delete();
    }
}
