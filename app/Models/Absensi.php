<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    
    protected $fillable = [
        'karyawan_id',
        'periode',
        'hadir',
        'onsite',
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
>>>>>>> fitur-baru
        'absence',
        'idle_rest',
        'izin_sakit_cuti',
        'tanpa_keterangan',
<<<<<<< HEAD
        'total_hari_kerja',
        'potongan_absensi',
        'catatan'
    ];

    protected $casts = [
        'hadir' => 'integer',
        'onsite' => 'integer',
=======
        'potongan_absensi',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_hari_bulan' => 'integer',
        'hadir' => 'integer',
        'on_site' => 'integer',
>>>>>>> fitur-baru
        'absence' => 'integer',
        'idle_rest' => 'integer',
        'izin_sakit_cuti' => 'integer',
        'tanpa_keterangan' => 'integer',
<<<<<<< HEAD
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
=======
        'potongan_absensi' => 'decimal:2',
    ];

    // Auto-calculate days in month and absence deduction
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-detect days in month from periode (YYYY-MM)
            if ($model->periode) {
                $date = Carbon::createFromFormat('Y-m', $model->periode);
                $model->jumlah_hari_bulan = $date->daysInMonth;
            }
            
            // Potongan absensi will be calculated when generating acuan gaji
            // Formula: (Absence + Tanpa Keterangan) / Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
            // We'll calculate this in the AcuanGaji model
        });
    }

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
>>>>>>> fitur-baru
    }
}
