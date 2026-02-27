<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBpjsKoperasi extends Model
{
    protected $table = 'pengaturan_bpjs_koperasi';
    
    protected $fillable = [
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'bpjs_kecelakaan_kerja',
        'bpjs_jht',
        'bpjs_jp',
        'koperasi',
        'keterangan',
        'is_active',
    ];
    
    protected $casts = [
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_ketenagakerjaan' => 'decimal:2',
        'bpjs_kecelakaan_kerja' => 'decimal:2',
        'bpjs_jht' => 'decimal:2',
        'bpjs_jp' => 'decimal:2',
        'koperasi' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get active BPJS & Koperasi configuration
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
    
    /**
     * Get total BPJS (pendapatan)
     */
    public function getTotalBpjsAttribute()
    {
        return $this->bpjs_kesehatan + 
               $this->bpjs_ketenagakerjaan + 
               $this->bpjs_kecelakaan_kerja + 
               $this->bpjs_jht + 
               $this->bpjs_jp;
    }
    
    /**
     * Check if karyawan eligible for BPJS (only Kontrak)
     */
    public static function isEligibleForBpjs($statusPegawai)
    {
        return $statusPegawai === 'Kontrak';
    }
    
    /**
     * Check if karyawan eligible for Koperasi (all ACTIVE except Harian)
     */
    public static function isEligibleForKoperasi($statusPegawai, $statusKaryawan)
    {
        return $statusKaryawan === 'Active' && $statusPegawai !== 'Harian';
    }
}
