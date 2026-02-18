<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterWilayah extends Model
{
    protected $table = 'master_wilayah';
    
    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function pengaturanGaji()
    {
        return $this->hasMany(PengaturanGaji::class, 'wilayah_id');
    }
}
