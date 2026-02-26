<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanJenisKaryawan extends Model
{
    protected $table = 'jabatan_jenis_karyawan';
    
    protected $fillable = [
        'jenis_karyawan',
        'jabatan',
    ];
    
    /**
     * Get jabatan list by jenis karyawan
     */
    public static function getJabatanByJenis($jenisKaryawan)
    {
        return self::where('jenis_karyawan', $jenisKaryawan)
                   ->pluck('jabatan')
                   ->toArray();
    }
    
    /**
     * Get all jenis karyawan with their jabatan
     */
    public static function getAllGrouped()
    {
        return self::all()
                   ->groupBy('jenis_karyawan')
                   ->map(function($items) {
                       return $items->pluck('jabatan')->toArray();
                   });
    }
}
