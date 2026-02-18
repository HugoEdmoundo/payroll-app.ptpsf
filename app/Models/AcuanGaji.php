<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcuanGaji extends Model
{
    protected $table = 'acuan_gaji';
    
    protected $fillable = [
        'karyawan_id',
        'pengaturan_gaji_id',
        'periode',
        'gaji_pokok',
        'tunjangan_prestasi',
        'tunjangan_konjungtur',
        'benefit_ibadah',
        'benefit_komunikasi',
        'benefit_operasional',
        'reward',
        'bpjs_kesehatan',
        'bpjs_kecelakaan_kerja',
        'bpjs_kematian',
        'bpjs_jht',
        'bpjs_jp',
        'potongan_bpjs_kesehatan',
        'potongan_bpjs_kecelakaan',
        'potongan_bpjs_kematian',
        'potongan_bpjs_jht',
        'potongan_bpjs_jp',
        'potongan_koperasi',
        'potongan_tabungan_koperasi',
        'potongan_kasbon',
        'potongan_umroh',
        'potongan_kurban',
        'potongan_mutabaah',
        'potongan_absensi',
        'potongan_kehadiran',
        'total_pendapatan',
        'total_pengeluaran',
        'take_home_pay',
        'catatan'
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'take_home_pay' => 'decimal:2'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pengaturanGaji()
    {
        return $this->belongsTo(PengaturanGaji::class);
    }

    public function hitungGaji()
    {
        return $this->hasOne(HitungGaji::class);
    }
}
