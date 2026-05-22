<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_karyawan',
        'periode',
        'jumlah_hari_bulan',
        'hadir',
        'on_site',
        'on_base',
        'absence',
        'idle_rest',
        'izin_sakit_cuti',
        'tanpa_keterangan',
        'potongan_absensi',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_hari_bulan' => 'integer',
        'hadir' => 'integer',
        'on_site' => 'integer',
        'on_base' => 'integer',
        'absence' => 'integer',
        'idle_rest' => 'integer',
        'izin_sakit_cuti' => 'integer',
        'tanpa_keterangan' => 'integer',
        'potongan_absensi' => 'decimal:2',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Calculate potongan based on acuan gaji
    public function calculatePotongan($gajiPokok, $tunjanganPrestasi, $operasional)
    {
        $totalAbsence = $this->absence + $this->tanpa_keterangan;
        $baseAmount = $gajiPokok + $tunjanganPrestasi + $operasional;

        return ($totalAbsence / $this->jumlah_hari_bulan) * $baseAmount;
    }
}
