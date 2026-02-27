<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBpjsKoperasi extends Model
{
    protected $table = 'pengaturan_bpjs_koperasi';
    
    protected $fillable = [
        'status_pegawai',
        'bpjs_kesehatan',
        'bpjs_kecelakaan_kerja',
        'bpjs_kematian',
        'bpjs_jht',
        'bpjs_jp',
        'koperasi',
    ];
    
    protected $casts = [
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_kecelakaan_kerja' => 'decimal:2',
        'bpjs_kematian' => 'decimal:2',
        'bpjs_jht' => 'decimal:2',
        'bpjs_jp' => 'decimal:2',
        'koperasi' => 'decimal:2',
    ];
    
    /**
     * Get total BPJS (Pendapatan only)
     */
    public function getTotalBpjsAttribute()
    {
        return $this->bpjs_kesehatan + 
               $this->bpjs_kecelakaan_kerja + 
               $this->bpjs_kematian + 
               $this->bpjs_jht + 
               $this->bpjs_jp;
    }
}
