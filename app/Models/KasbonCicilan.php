<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasbonCicilan extends Model
{
    protected $table = 'kasbon_cicilan';
    
    protected $fillable = [
        'kasbon_id',
        'periode',
        'nominal',
        'tanggal_bayar',
        'catatan'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_bayar' => 'date'
    ];

    public function kasbon()
    {
        return $this->belongsTo(Kasbon::class);
    }
}
