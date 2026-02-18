<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    protected $table = 'komponen_gaji';
    
    protected $fillable = [
        'nama',
        'kode',
        'tipe',
        'kategori',
        'deskripsi',
        'is_system',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
}
