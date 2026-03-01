<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanGaji extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_gaji';
    protected $primaryKey = 'id_pengaturan';

    protected $fillable = [
        'jenis_karyawan',
        'jabatan',
        'lokasi_kerja',
        'gaji_pokok',
        'tunjangan_operasional',
        'tunjangan_prestasi',
        'gaji_nett',
        'total_gaji',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_operasional' => 'decimal:2',
        'tunjangan_prestasi' => 'decimal:2',
        'gaji_nett' => 'decimal:2',
        'total_gaji' => 'decimal:2',
    ];

    // Auto-calculate fields before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate Gaji Nett (Gaji Pokok + Tunjangan Prestasi)
            $model->gaji_nett = $model->gaji_pokok + ($model->tunjangan_prestasi ?? 0);
            
            // Total Gaji = Gaji Nett (BPJS & Koperasi handled separately in Acuan Gaji)
            $model->total_gaji = $model->gaji_nett;
        });
    }
}
