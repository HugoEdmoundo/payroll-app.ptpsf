<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanGajiStatusPegawai extends Model
{
    protected $table = 'pengaturan_gaji_status_pegawai';
    protected $primaryKey = 'id_pengaturan';
    
    protected $fillable = [
        'status_pegawai',
        'lokasi_kerja',
        'gaji_pokok',
        'keterangan',
    ];
    
    protected $casts = [
        'gaji_pokok' => 'decimal:2',
    ];
}
