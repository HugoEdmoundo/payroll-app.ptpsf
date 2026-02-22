<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> fitur-baru
use Illuminate\Database\Eloquent\Model;

class NKI extends Model
{
<<<<<<< HEAD
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
=======
    use HasFactory;

    protected $table = 'nki';
    protected $primaryKey = 'id_nki';

    protected $fillable = [
        'id_karyawan',
        'periode',
        'kemampuan',
        'kontribusi',
        'kedisiplinan',
        'lainnya',
        'nilai_nki',
        'persentase_tunjangan',
        'keterangan',
>>>>>>> fitur-baru
    ];

    protected $casts = [
        'kemampuan' => 'decimal:2',
<<<<<<< HEAD
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
=======
        'kontribusi' => 'decimal:2',
        'kedisiplinan' => 'decimal:2',
        'lainnya' => 'decimal:2',
        'nilai_nki' => 'decimal:2',
        'persentase_tunjangan' => 'integer',
    ];

    // Auto-calculate NKI and percentage before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate NKI: Kemampuan(20%) + Kontribusi(20%) + Kedisiplinan(40%) + Lainnya(20%)
            $model->nilai_nki = ($model->kemampuan * 0.20) + 
                               ($model->kontribusi * 0.20) + 
                               ($model->kedisiplinan * 0.40) + 
                               ($model->lainnya * 0.20);
            
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
>>>>>>> fitur-baru
    }
}
