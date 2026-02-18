<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    
    protected $fillable = [
        'karyawan_id',
        'periode',
        'hadir',
        'onsite',
        'absence',
        'idle_rest',
        'izin_sakit_cuti',
        'tanpa_keterangan',
        'total_hari_kerja',
        'potongan_absensi',
        'catatan'
    ];

    protected $casts = [
        'hadir' => 'integer',
        'onsite' => 'integer',
        'absence' => 'integer',
        'idle_rest' => 'integer',
        'izin_sakit_cuti' => 'integer',
        'tanpa_keterangan' => 'integer',
        'total_hari_kerja' => 'integer',
        'potongan_absensi' => 'decimal:2'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public static function hitungPotongan($absence, $tanpaKeterangan, $totalHari, $gajiPokok, $tunjanganPrestasi, $operasional)
    {
        if ($totalHari == 0) return 0;
        return (($absence + $tanpaKeterangan) / $totalHari) * ($gajiPokok + $tunjanganPrestasi + $operasional);
    }
}
