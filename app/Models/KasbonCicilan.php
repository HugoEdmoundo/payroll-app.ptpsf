<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasbonCicilan extends Model
{
    use HasFactory;

    protected $table = 'kasbon_cicilan';
    protected $primaryKey = 'id_cicilan';

    protected $fillable = [
        'id_kasbon',
        'cicilan_ke',
        'periode',
        'nominal_cicilan',
        'tanggal_bayar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal_cicilan' => 'decimal:2',
        'cicilan_ke' => 'integer',
    ];

    public function kasbon()
    {
        return $this->belongsTo(Kasbon::class, 'id_kasbon', 'id_kasbon');
    }

    // Mark cicilan as paid
    public function markAsPaid()
    {
        $this->update([
            'status' => 'Terbayar',
            'tanggal_bayar' => now(),
        ]);

        // Update kasbon cicilan_terbayar
        $kasbon = $this->kasbon;
        $kasbon->cicilan_terbayar = $kasbon->cicilan()->where('status', 'Terbayar')->count();
        $kasbon->save();
    }
}
