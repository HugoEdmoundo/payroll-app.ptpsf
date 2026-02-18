<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitungGaji extends Model
{
    protected $table = 'hitung_gaji';
    
    protected $fillable = [
        'acuan_gaji_id',
        'karyawan_id',
        'periode',
        'pendapatan_acuan',
        'pengeluaran_acuan',
        'penyesuaian_pendapatan',
        'penyesuaian_pengeluaran',
        'total_pendapatan_acuan',
        'total_penyesuaian_pendapatan',
        'total_pendapatan_akhir',
        'total_pengeluaran_acuan',
        'total_penyesuaian_pengeluaran',
        'total_pengeluaran_akhir',
        'take_home_pay',
        'status',
        'approved_at',
        'approved_by',
        'catatan_umum'
    ];

    protected $casts = [
        'pendapatan_acuan' => 'array',
        'pengeluaran_acuan' => 'array',
        'penyesuaian_pendapatan' => 'array',
        'penyesuaian_pengeluaran' => 'array',
        'total_pendapatan_acuan' => 'decimal:2',
        'total_penyesuaian_pendapatan' => 'decimal:2',
        'total_pendapatan_akhir' => 'decimal:2',
        'total_pengeluaran_acuan' => 'decimal:2',
        'total_penyesuaian_pengeluaran' => 'decimal:2',
        'total_pengeluaran_akhir' => 'decimal:2',
        'take_home_pay' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function acuanGaji()
    {
        return $this->belongsTo(AcuanGaji::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function slipGaji()
    {
        return $this->hasOne(SlipGaji::class);
    }
}
