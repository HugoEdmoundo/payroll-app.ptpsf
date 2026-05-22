<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitungGaji extends Model
{
    use \App\Traits\HasSalaryComponents;

    protected $table = 'hitung_gaji';

    protected $fillable = [
        'acuan_gaji_id',
        'karyawan_id',
        'periode',
        'lokasi_kerja',
        'gaji_pokok', 'bpjs_kesehatan', 'bpjs_kecelakaan_kerja',
        'bpjs_kematian', 'bpjs_jht', 'bpjs_jp',
        'tunjangan_prestasi', 'tunjangan_konjungtur',
        'benefit_ibadah', 'benefit_komunikasi', 'benefit_operasional', 'reward',
        'koperasi', 'kasbon', 'umroh', 'kurban', 'mutabaah',
        'potongan_absensi', 'potongan_kehadiran',
        'adjustments',
        'total_pendapatan',
        'total_pengeluaran',
        'gaji_bersih',
        'status',
        'approved_at',
        'approved_by',
        'keterangan',
    ];

    public function getCasts()
    {
        return array_merge(parent::getCasts(), self::salaryComponentCasts(), [
            'adjustments' => 'array',
            'total_pendapatan' => 'decimal:2',
            'total_pengeluaran' => 'decimal:2',
            'gaji_bersih' => 'decimal:2',
            'approved_at' => 'datetime',
        ]);
    }

    public function acuanGaji()
    {
        return $this->belongsTo(AcuanGaji::class, 'acuan_gaji_id', 'id_acuan');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function slipGaji()
    {
        return $this->hasOne(SlipGaji::class);
    }

    public function getFinalValue($field)
    {
        $value = (float) ($this->$field ?? 0);
        $adjustments = $this->adjustments ?? [];

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

    public function getAdjustment($field)
    {
        return ($this->adjustments ?? [])[$field] ?? null;
    }

    public function getTotalPendapatanAttribute($value)
    {
        return $value ?: $this->calculateTotalIncome();
    }

    public function getTotalPengeluaranAttribute($value)
    {
        return $value ?: $this->calculateTotalDeduction();
    }

    public function getGajiBersihAttribute($value)
    {
        return $value ?: $this->calculateNetSalary();
    }
}
