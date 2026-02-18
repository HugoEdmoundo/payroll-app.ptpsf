<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusPegawai extends Model
{
    protected $table = 'master_status_pegawai';
    
    protected $fillable = [
        'nama',
        'durasi_hari',
        'keterangan',
        'gunakan_nki',
        'is_active',
        'order'
    ];

    protected $casts = [
        'durasi_hari' => 'integer',
        'gunakan_nki' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
}
