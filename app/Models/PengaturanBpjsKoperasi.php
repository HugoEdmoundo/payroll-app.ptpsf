<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBpjsKoperasi extends Model
{
    protected $table = 'pengaturan_bpjs_koperasi';
    
    protected $fillable = [
        'bpjs_kesehatan_pendapatan',
        'bpjs_kecelakaan_kerja_pendapatan',
        'bpjs_kematian_pendapatan',
        'bpjs_jht_pendapatan',
        'bpjs_jp_pendapatan',
        'koperasi',
    ];
    
    protected $casts = [
        'bpjs_kesehatan_pendapatan' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pendapatan' => 'decimal:2',
        'bpjs_kematian_pendapatan' => 'decimal:2',
        'bpjs_jht_pendapatan' => 'decimal:2',
        'bpjs_jp_pendapatan' => 'decimal:2',
        'koperasi' => 'decimal:2',
    ];
    
    /**
     * Get total BPJS (Pendapatan only)
     */
    public function getTotalBpjsAttribute()
    {
        return $this->bpjs_kesehatan_pendapatan + 
               $this->bpjs_kecelakaan_kerja_pendapatan + 
               $this->bpjs_kematian_pendapatan + 
               $this->bpjs_jht_pendapatan + 
               $this->bpjs_jp_pendapatan;
    }
    
    /**
     * Get the global BPJS & Koperasi configuration (singleton)
     */
    public static function getGlobal()
    {
        return static::first();
    }
}
