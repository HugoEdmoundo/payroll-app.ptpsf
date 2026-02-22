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
        'potongan_koperasi',
        'gaji_nett',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'bpjs_kecelakaan_kerja',
        'bpjs_total',
        'total_gaji',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_operasional' => 'decimal:2',
        'potongan_koperasi' => 'decimal:2',
        'gaji_nett' => 'decimal:2',
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_ketenagakerjaan' => 'decimal:2',
        'bpjs_kecelakaan_kerja' => 'decimal:2',
        'bpjs_total' => 'decimal:2',
        'total_gaji' => 'decimal:2',
    ];

    // Auto-calculate fields before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate BPJS Total
            $model->bpjs_total = $model->bpjs_kesehatan + $model->bpjs_ketenagakerjaan + $model->bpjs_kecelakaan_kerja;
            
            // Calculate Gaji Nett (Gaji Pokok + Tunjangan - Potongan)
            $model->gaji_nett = $model->gaji_pokok + $model->tunjangan_operasional - $model->potongan_koperasi;
            
            // Calculate Total Gaji (Gaji Nett + BPJS Total)
            $model->total_gaji = $model->gaji_nett + $model->bpjs_total;
        });
    }
}
