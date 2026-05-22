<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Calculate days in month from periode (YYYY-MM)
     */
    public function getDaysInMonth(string $periode): int
    {
        return Carbon::createFromFormat('Y-m', $periode)->daysInMonth;
    }

    /**
     * Recalculate attendance deduction and sync to related payroll entries
     */
    public function recalculateDeduction(Absensi $absensi): void
    {
        $absensi->jumlah_hari_bulan = $this->getDaysInMonth($absensi->periode);
        $absensi->saveQuietly();

        $salaryService = app(SalaryCalculationService::class);

        $acuan = AcuanGaji::where('id_karyawan', $absensi->id_karyawan)
            ->where('periode', $absensi->periode)
            ->first();

        if (! $acuan) {
            return;
        }

        $potonganAbsensi = $salaryService->calculateAttendanceDeduction(
            $absensi,
            $acuan->gaji_pokok,
            $acuan->tunjangan_prestasi,
            $acuan->benefit_operasional
        );

        $acuan->updateQuietly(['potongan_absensi' => $potonganAbsensi]);

        $hitungGaji = HitungGaji::where('acuan_gaji_id', $acuan->id_acuan)->first();
        if ($hitungGaji) {
            $hitungGaji->updateQuietly(['potongan_absensi' => $potonganAbsensi]);
        }
    }
}
