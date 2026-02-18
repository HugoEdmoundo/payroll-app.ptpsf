<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicFields;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory, HasDynamicFields;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    
    protected $fillable = [
        'nama_karyawan',
        'email',
        'no_telp',
        'join_date',
        'jabatan',
        'lokasi_kerja',
        'jenis_karyawan',
        'status_pegawai',
        'npwp',
        'bpjs_kesehatan_no',
        'bpjs_tk_no',
        'no_rekening',
        'bank',
        'status_perkawinan',
        'nama_istri',
        'jumlah_anak',
        'no_telp_istri',
        'status_karyawan'
    ];

    protected $casts = [
        'join_date' => 'datetime',
        'jumlah_anak' => 'integer'
    ];
    
    // Boot method untuk set join_date dengan waktu SEKARANG
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($karyawan) {
            if (request()->has('join_date')) {
                $tanggal = Carbon::parse(request()->join_date)->format('Y-m-d');
                $waktuSekarang = Carbon::now()->format('H:i:s');
                $karyawan->join_date = Carbon::parse($tanggal . ' ' . $waktuSekarang);
            }
        });
        
        static::updating(function ($karyawan) {
            if (request()->has('join_date') && request()->join_date != $karyawan->getOriginal('join_date')->format('Y-m-d')) {
                $tanggal = Carbon::parse(request()->join_date)->format('Y-m-d');
                $waktuSekarang = Carbon::now()->format('H:i:s');
                $karyawan->join_date = Carbon::parse($tanggal . ' ' . $waktuSekarang);
            }
        });
    }

    // Masa Kerja dalam format DD:HH:MM:SS
    public function getMasaKerjaAttribute()
    {
        if (!$this->join_date) {
            return '00:00:00:00';
        }

        $now = Carbon::now();
        $join = Carbon::parse($this->join_date);
        
        $diff = $join->diff($now);
        
        return sprintf(
            '%02d:%02d:%02d:%02d',
            $diff->days,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }
    // Format tanggal untuk display
    public function getFormattedJoinDateAttribute()
    {
        if (!$this->join_date) {
            return '-';
        }
        return $this->join_date->format('d/m/Y H:i:s') . ' WIB';
    }
}