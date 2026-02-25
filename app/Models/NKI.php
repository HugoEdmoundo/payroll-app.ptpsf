<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NKI extends Model
{
    use HasFactory;

    protected $table = 'nki';
    protected $primaryKey = 'id_nki';

    protected $fillable = [
        'id_karyawan',
        'periode',
        'kemampuan',
        'kontribusi_1',
        'kontribusi_2',
        'kedisiplinan',
        'nilai_nki',
        'persentase_tunjangan',
        'keterangan',
    ];

    protected $casts = [
        'kemampuan' => 'decimal:2',
        'kontribusi_1' => 'decimal:2',
        'kontribusi_2' => 'decimal:2',
        'kedisiplinan' => 'decimal:2',
        'nilai_nki' => 'decimal:2',
        'persentase_tunjangan' => 'integer',
    ];

    // Auto-calculate NKI and percentage before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate NKI: Kemampuan(20%) + Kontribusi_1(20%) + Kontribusi_2(40%) + Kedisiplinan(20%)
            $model->nilai_nki = ($model->kemampuan * 0.20) + 
                               ($model->kontribusi_1 * 0.20) + 
                               ($model->kontribusi_2 * 0.40) + 
                               ($model->kedisiplinan * 0.20);
            
            // Determine percentage based on NKI value
            if ($model->nilai_nki >= 8.5) {
                $model->persentase_tunjangan = 100;
            } elseif ($model->nilai_nki >= 8.0) {
                $model->persentase_tunjangan = 80;
            } else {
                $model->persentase_tunjangan = 70;
            }
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
}
