<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBpjsKoperasi extends Model
{
    protected $table = 'pengaturan_bpjs_koperasi';
    
    protected $fillable = [
        'jenis_karyawan',
        'status_pegawai',
        'bpjs_kesehatan_pendapatan',
        'bpjs_kesehatan_pengeluaran',
        'bpjs_kecelakaan_kerja_pendapatan',
        'bpjs_kecelakaan_kerja_pengeluaran',
        'bpjs_kematian_pendapatan',
        'bpjs_kematian_pengeluaran',
        'bpjs_jht_pendapatan',
        'bpjs_jht_pengeluaran',
        'bpjs_jp_pendapatan',
        'bpjs_jp_pengeluaran',
        'koperasi',
    ];
    
    protected $casts = [
        'bpjs_kesehatan_pendapatan' => 'decimal:2',
        'bpjs_kesehatan_pengeluaran' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pendapatan' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pengeluaran' => 'decimal:2',
        'bpjs_kematian_pendapatan' => 'decimal:2',
        'bpjs_kematian_pengeluaran' => 'decimal:2',
        'bpjs_jht_pendapatan' => 'decimal:2',
        'bpjs_jht_pengeluaran' => 'decimal:2',
        'bpjs_jp_pendapatan' => 'decimal:2',
        'bpjs_jp_pengeluaran' => 'decimal:2',
        'koperasi' => 'decimal:2',
    ];
    
    /**
     * Get total BPJS Pendapatan
     */
    public function getTotalBpjsPendapatanAttribute()
    {
        return $this->bpjs_kesehatan_pendapatan + 
               $this->bpjs_kecelakaan_kerja_pendapatan + 
               $this->bpjs_kematian_pendapatan + 
               $this->bpjs_jht_pendapatan + 
               $this->bpjs_jp_pendapatan;
    }
    
    /**
     * Get total BPJS Pengeluaran
     */
    public function getTotalBpjsPengeluaranAttribute()
    {
        return $this->bpjs_kesehatan_pengeluaran + 
               $this->bpjs_kecelakaan_kerja_pengeluaran + 
               $this->bpjs_kematian_pengeluaran + 
               $this->bpjs_jht_pengeluaran + 
               $this->bpjs_jp_pengeluaran;
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
