<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\NKI;

class SalaryCalculationService
{
    /**
     * Calculate total income from income components
     */
    public function calculateTotalIncome(array $data): float
    {
        $total = 0;
        foreach (AcuanGaji::getIncomeComponents() as $field) {
            $total += (float) ($data[$field] ?? 0);
        }

        return $total;
    }

    /**
     * Calculate total deduction from deduction components
     */
    public function calculateTotalDeduction(array $data): float
    {
        $total = 0;
        foreach (AcuanGaji::getDeductionComponents() as $field) {
            $total += (float) ($data[$field] ?? 0);
        }

        return $total;
    }

    /**
     * Calculate net salary
     */
    public function calculateNetSalary(array $data): float
    {
        return $this->calculateTotalIncome($data) - $this->calculateTotalDeduction($data);
    }

    /**
     * Apply NKI adjustment to tunjangan_prestasi
     */
    public function applyNkiAdjustment(float $tunjanganPrestasi, ?NKI $nki, bool $isStatusPegawai = false): float
    {
        if ($isStatusPegawai || ! $nki || $tunjanganPrestasi <= 0) {
            return $tunjanganPrestasi;
        }

        return $tunjanganPrestasi * ($nki->persentase_tunjangan / 100);
    }

    /**
     * Calculate attendance deduction
     */
    public function calculateAttendanceDeduction(?Absensi $absensi, float $gajiPokok, float $tunjanganPrestasi, float $benefitOperasional): float
    {
        if (! $absensi || $absensi->jumlah_hari_bulan <= 0) {
            return 0;
        }

        $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
        $baseAmount = $gajiPokok + $tunjanganPrestasi + $benefitOperasional;

        return ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
    }

    /**
     * Build HitungGaji data from AcuanGaji with calculations
     */
    public function buildHitungGajiData(AcuanGaji $acuanGaji): array
    {
        $karyawan = $acuanGaji->karyawan;
        $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

        // Get NKI
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
            ->where('periode', $acuanGaji->periode)
            ->first();

        // Calculate tunjangan_prestasi with NKI
        $tunjanganPrestasi = $this->applyNkiAdjustment(
            $acuanGaji->tunjangan_prestasi,
            $nki,
            $isStatusPegawai
        );

        // Get attendance
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
            ->where('periode', $acuanGaji->periode)
            ->first();

        // Calculate potongan_absensi
        $potonganAbsensi = $this->calculateAttendanceDeduction(
            $absensi,
            $acuanGaji->gaji_pokok,
            $tunjanganPrestasi,
            $acuanGaji->benefit_operasional
        );

        $data = array_merge(
            $acuanGaji->only(AcuanGaji::getAllComponents()),
            [
                'tunjangan_prestasi' => $tunjanganPrestasi,
                'potongan_absensi' => $potonganAbsensi,
            ]
        );

        $data['total_pendapatan'] = $this->calculateTotalIncome($data);
        $data['total_pengeluaran'] = $this->calculateTotalDeduction($data);
        $data['gaji_bersih'] = $data['total_pendapatan'] - $data['total_pengeluaran'];

        return $data;
    }

    /**
     * Get final value with adjustment applied
     */
    public function getFinalValue(array $data, ?array $adjustments, string $field): float
    {
        $value = (float) ($data[$field] ?? 0);

        if (isset($adjustments[$field])) {
            $adj = $adjustments[$field];
            if (($adj['type'] ?? '+') === '+') {
                $value += (float) ($adj['nominal'] ?? 0);
            } else {
                $value -= (float) ($adj['nominal'] ?? 0);
            }
        }

        return $value;
    }

    /**
     * Calculate totals for HitungGaji including adjustments
     */
    public function calculateHitungGajiTotals(HitungGaji $hitungGaji): array
    {
        $data = $hitungGaji->toArray();
        $adjustments = $hitungGaji->adjustments ?? [];

        $totalPendapatan = 0;
        foreach (HitungGaji::getIncomeComponents() as $field) {
            $totalPendapatan += $this->getFinalValue($data, $adjustments, $field);
        }

        $totalPengeluaran = 0;
        foreach (HitungGaji::getDeductionComponents() as $field) {
            $totalPengeluaran += $this->getFinalValue($data, $adjustments, $field);
        }

        return [
            'total_pendapatan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
            'gaji_bersih' => $totalPendapatan - $totalPengeluaran,
        ];
    }
}
