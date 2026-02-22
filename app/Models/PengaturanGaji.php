<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> fitur-baru
use Illuminate\Database\Eloquent\Model;

class PengaturanGaji extends Model
{
<<<<<<< HEAD
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
=======
    use HasFactory;

    protected $table = 'pengaturan_gaji';
    protected $primaryKey = 'id_pengaturan';

    protected $fillable = [
        'jenis_karyawan',
        'jabatan',
        'lokasi_kerja',
        'gaji_pokok',
        'tunjangan_operasional',
        'potongan_koperasi',
        'gaji_nett',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'bpjs_kecelakaan_kerja',
        'bpjs_total',
        'total_gaji',
        'keterangan',
>>>>>>> fitur-baru
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_operasional' => 'decimal:2',
<<<<<<< HEAD
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
=======
        'potongan_koperasi' => 'decimal:2',
        'gaji_nett' => 'decimal:2',
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_ketenagakerjaan' => 'decimal:2',
        'bpjs_kecelakaan_kerja' => 'decimal:2',
        'bpjs_total' => 'decimal:2',
        'total_gaji' => 'decimal:2',
    ];

    // Auto-calculate fields before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate BPJS Total
            $model->bpjs_total = $model->bpjs_kesehatan + $model->bpjs_ketenagakerjaan + $model->bpjs_kecelakaan_kerja;
            
            // Calculate Gaji Nett (Gaji Pokok + Tunjangan - Potongan)
            $model->gaji_nett = $model->gaji_pokok + $model->tunjangan_operasional - $model->potongan_koperasi;
            
            // Calculate Total Gaji (Gaji Nett + BPJS Total)
            $model->total_gaji = $model->gaji_nett + $model->bpjs_total;
        });
>>>>>>> fitur-baru
    }
}
