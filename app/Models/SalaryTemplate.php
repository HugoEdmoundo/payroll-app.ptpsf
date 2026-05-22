<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryTemplate extends Model
{
    protected $table = 'salary_templates';

    protected $fillable = [
        'type',
        'employee_status',
        'jenis_karyawan',
        'jabatan',
        'lokasi_kerja',
        'gaji_pokok',
        'tunjangan_operasional',
        'tunjangan_prestasi',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_operasional' => 'decimal:2',
        'tunjangan_prestasi' => 'decimal:2',
    ];

    public function getGajiNettAttribute()
    {
        return $this->gaji_pokok + ($this->tunjangan_prestasi ?? 0);
    }

    public function getTotalGajiAttribute()
    {
        return $this->gaji_nett;
    }

    public function scopeStandard($query)
    {
        return $query->where('type', 'standard');
    }

    public function scopeStatus($query)
    {
        return $query->where('type', 'status');
    }

    /**
     * Find salary template for a given employee
     */
    public static function findByEmployee(Karyawan $karyawan): ?self
    {
        $statusPegawai = $karyawan->status_pegawai;

        if (in_array($statusPegawai, ['Harian', 'OJT'])) {
            return static::status()
                ->where('employee_status', $statusPegawai)
                ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                ->first();
        }

        return static::standard()
            ->where('employee_status', 'Kontrak')
            ->where('jenis_karyawan', $karyawan->jenis_karyawan)
            ->where('jabatan', $karyawan->jabatan)
            ->where('lokasi_kerja', $karyawan->lokasi_kerja)
            ->first();
    }
}
