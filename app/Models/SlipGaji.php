<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model
{
    protected $table = 'slip_gaji';
    
    protected $fillable = [
        'hitung_gaji_id',
        'karyawan_id',
        'periode',
        'nomor_slip',
        'nama_karyawan',
        'jabatan',
        'status_pegawai',
        'tanggal_mulai_bekerja',
        'masa_kerja',
        'detail_pendapatan',
        'detail_pengeluaran',
        'total_pendapatan',
        'total_pengeluaran',
        'take_home_pay',
        'generated_at',
        'generated_by',
        'is_sent',
        'sent_at',
        'catatan'
    ];

    protected $casts = [
        'detail_pendapatan' => 'array',
        'detail_pengeluaran' => 'array',
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'take_home_pay' => 'decimal:2',
        'tanggal_mulai_bekerja' => 'date',
        'generated_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean'
    ];

    public function hitungGaji()
    {
        return $this->belongsTo(HitungGaji::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
