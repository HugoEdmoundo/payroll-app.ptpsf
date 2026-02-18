<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanGaji extends Model
{
    protected $table = 'pengaturan_gaji';
    
    protected $fillable = [
        'jenis_karyawan',
        'jabatan',
        'wilayah_id',
        'gaji_pokok',
        'tunjangan_operasional',
        'tunjangan_prestasi',
        'tunjangan_konjungtur',
        'benefit_ibadah',
        'benefit_komunikasi',
        'benefit_operasional',
        'bpjs_kesehatan',
        'bpjs_kecelakaan_kerja',
        'bpjs_kematian',
        'bpjs_jht',
        'bpjs_jp',
        'potongan_koperasi',
        'net_gaji',
        'total_bpjs',
        'nett',
        'is_active',
        'catatan'
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_operasional' => 'decimal:2',
        'tunjangan_prestasi' => 'decimal:2',
        'tunjangan_konjungtur' => 'decimal:2',
        'benefit_ibadah' => 'decimal:2',
        'benefit_komunikasi' => 'decimal:2',
        'benefit_operasional' => 'decimal:2',
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_kecelakaan_kerja' => 'decimal:2',
        'bpjs_kematian' => 'decimal:2',
        'bpjs_jht' => 'decimal:2',
        'bpjs_jp' => 'decimal:2',
        'potongan_koperasi' => 'decimal:2',
        'net_gaji' => 'decimal:2',
        'total_bpjs' => 'decimal:2',
        'nett' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function wilayah()
    {
        return $this->belongsTo(MasterWilayah::class, 'wilayah_id');
    }

    public function acuanGaji()
    {
        return $this->hasMany(AcuanGaji::class);
    }
}
