<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NKI extends Model
{
    protected $table = 'nki';
    
    protected $fillable = [
        'karyawan_id',
        'periode',
        'kemampuan',
        'konstribusi',
        'kedisiplinan',
        'lainnya',
        'nilai_nki',
        'persentase_prestasi',
        'catatan',
        'dinilai_oleh'
    ];

    protected $casts = [
        'kemampuan' => 'decimal:2',
        'konstribusi' => 'decimal:2',
        'kedisiplinan' => 'decimal:2',
        'lainnya' => 'decimal:2',
        'nilai_nki' => 'decimal:2',
        'persentase_prestasi' => 'decimal:2'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    public static function hitungNKI($kemampuan, $konstribusi, $kedisiplinan, $lainnya)
    {
        return ($kemampuan * 0.2) + ($konstribusi * 0.2) + ($kedisiplinan * 0.4) + ($lainnya * 0.2);
    }

    public static function hitungPersentasePrestasi($nilaiNKI)
    {
        if ($nilaiNKI >= 8.5) return 100;
        if ($nilaiNKI >= 8.0) return 80;
        if ($nilaiNKI >= 7.0) return 70;
        return 70;
    }
}
