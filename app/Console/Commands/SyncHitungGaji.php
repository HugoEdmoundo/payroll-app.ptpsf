<?php

namespace App\Console\Commands;

use App\Models\HitungGaji;
use App\Services\SalaryCalculationService;
use Illuminate\Console\Command;

class SyncHitungGaji extends Command
{
    protected $signature = 'hitung-gaji:sync';

    protected $description = 'Sync Hitung Gaji data with Acuan Gaji and recalculate NKI & Absensi';

    public function handle()
    {
        $this->info('Starting Hitung Gaji synchronization...');

        $hitungGajiList = HitungGaji::with('acuanGaji')->get();
        $service = app(SalaryCalculationService::class);
        $updated = 0;

        foreach ($hitungGajiList as $hitungGaji) {
            $acuanGaji = $hitungGaji->acuanGaji;

            if (! $acuanGaji) {
                $this->warn("Acuan Gaji not found for Hitung Gaji ID: {$hitungGaji->id}");

                continue;
            }

            $data = $service->buildHitungGajiData($acuanGaji);
            $hitungGaji->update($data);
            $updated++;
        }

        $this->info("Successfully synchronized {$updated} Hitung Gaji records.");

        return 0;
    }
}
