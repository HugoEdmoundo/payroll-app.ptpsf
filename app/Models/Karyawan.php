<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    
    protected $fillable = [
        'nama_karyawan',
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
        'join_date' => 'date',
        'jumlah_anak' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($karyawan) {
            if ($karyawan->join_date) {
                $now = Carbon::now();
                $joinDate = Carbon::parse($karyawan->join_date);
                $karyawan->masa_kerja = $joinDate->diffInDays($now);
            }
        });
    }

    public function getMasaKerjaTahunAttribute()
    {
        if ($this->join_date) {
            $now = Carbon::now();
            $joinDate = Carbon::parse($this->join_date);
            return $joinDate->diffInYears($now);
        }
        return 0;
    }
}