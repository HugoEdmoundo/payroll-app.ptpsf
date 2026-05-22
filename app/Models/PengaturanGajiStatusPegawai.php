<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PengaturanGajiStatusPegawai extends Model
{
    protected $table = 'salary_templates';

    protected $primaryKey = 'id';

    protected $fillable = [
        'status_pegawai',
        'lokasi_kerja',
        'gaji_pokok',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope('status_type', function (Builder $builder) {
            $builder->where('type', 'status');
        });
    }

    // Map status_pegawai <-> employee_status
    public function getStatusPegawaiAttribute($value)
    {
        return $this->attributes['employee_status'] ?? $value;
    }

    public function setStatusPegawaiAttribute($value)
    {
        $this->attributes['employee_status'] = $value;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->type = 'status';
            $model->jenis_karyawan = '';
            $model->jabatan = '';
            $model->tunjangan_operasional = 0;
            $model->tunjangan_prestasi = 0;
        });
    }
}
