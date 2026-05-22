<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PengaturanGaji extends Model
{
    protected $table = 'salary_templates';

    protected $primaryKey = 'id';

    protected $fillable = [
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

    protected static function booted()
    {
        static::addGlobalScope('standard_type', function (Builder $builder) {
            $builder->where('type', 'standard');
        });
    }

    // gaji_nett and total_gaji are computed accessors (not stored)
    public function getGajiNettAttribute()
    {
        return $this->gaji_pokok + ($this->tunjangan_prestasi ?? 0);
    }

    public function getTotalGajiAttribute()
    {
        return $this->gaji_nett;
    }

    // Fill employee_status automatically when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'standard';
            $model->employee_status = 'Kontrak';
        });
    }
}
