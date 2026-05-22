<?php

namespace App\Traits;

trait HasSalaryComponents
{
    public static function getIncomeComponents(): array
    {
        return [
            'gaji_pokok',
            'bpjs_kesehatan',
            'bpjs_kecelakaan_kerja',
            'bpjs_kematian',
            'bpjs_jht',
            'bpjs_jp',
            'tunjangan_prestasi',
            'tunjangan_konjungtur',
            'benefit_ibadah',
            'benefit_komunikasi',
            'benefit_operasional',
            'reward',
        ];
    }

    public static function getDeductionComponents(): array
    {
        return [
            'koperasi',
            'kasbon',
            'umroh',
            'kurban',
            'mutabaah',
            'potongan_absensi',
            'potongan_kehadiran',
        ];
    }

    public static function getAllComponents(): array
    {
        return array_merge(static::getIncomeComponents(), static::getDeductionComponents());
    }

    public static function salaryComponentCasts(): array
    {
        $fields = [];
        foreach (static::getAllComponents() as $component) {
            $fields[$component] = 'decimal:2';
        }

        return $fields;
    }

    public function calculateTotalIncome(): float
    {
        $total = 0;
        foreach (static::getIncomeComponents() as $field) {
            $total += (float) ($this->$field ?? 0);
        }

        return $total;
    }

    public function calculateTotalDeduction(): float
    {
        $total = 0;
        foreach (static::getDeductionComponents() as $field) {
            $total += (float) ($this->$field ?? 0);
        }

        return $total;
    }

    public function calculateNetSalary(): float
    {
        return $this->calculateTotalIncome() - $this->calculateTotalDeduction();
    }
}
