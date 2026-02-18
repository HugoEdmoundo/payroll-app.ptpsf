<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    protected $table = 'kasbon';
    
    protected $fillable = [
        'karyawan_id',
        'nomor_kasbon',
        'tanggal_pengajuan',
        'deskripsi',
        'nominal',
        'metode_pembayaran',
        'jumlah_cicilan',
        'nominal_cicilan',
        'status',
        'sisa_hutang',
        'approved_by',
        'approved_at',
        'catatan_approval'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'nominal' => 'decimal:2',
        'jumlah_cicilan' => 'integer',
        'nominal_cicilan' => 'decimal:2',
        'sisa_hutang' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cicilan()
    {
        return $this->hasMany(KasbonCicilan::class);
    }

    public static function generateNomorKasbon()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'KB-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
