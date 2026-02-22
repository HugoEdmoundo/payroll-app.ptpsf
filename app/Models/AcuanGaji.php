<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcuanGaji extends Model
{
    use HasFactory;

    protected $table = 'acuan_gaji';
    protected $primaryKey = 'id_acuan';

    protected $fillable = [
        'id_karyawan',
        'periode',
        // Pendapatan
        'gaji_pokok',
        'bpjs_kesehatan_pendapatan',
        'bpjs_kecelakaan_kerja_pendapatan',
        'bpjs_kematian_pendapatan',
        'bpjs_jht_pendapatan',
        'bpjs_jp_pendapatan',
        'tunjangan_prestasi',
        'tunjangan_konjungtur',
        'benefit_ibadah',
        'benefit_komunikasi',
        'benefit_operasional',
        'reward',
        'total_pendapatan',
        // Pengeluaran
        'bpjs_kesehatan_pengeluaran',
        'bpjs_kecelakaan_kerja_pengeluaran',
        'bpjs_kematian_pengeluaran',
        'bpjs_jht_pengeluaran',
        'bpjs_jp_pengeluaran',
        'tabungan_koperasi',
        'koperasi',
        'kasbon',
        'umroh',
        'kurban',
        'mutabaah',
        'potongan_absensi',
        'potongan_kehadiran',
        'total_pengeluaran',
        'gaji_bersih',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'bpjs_kesehatan_pendapatan' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pendapatan' => 'decimal:2',
        'bpjs_kematian_pendapatan' => 'decimal:2',
        'bpjs_jht_pendapatan' => 'decimal:2',
        'bpjs_jp_pendapatan' => 'decimal:2',
        'tunjangan_prestasi' => 'decimal:2',
        'tunjangan_konjungtur' => 'decimal:2',
        'benefit_ibadah' => 'decimal:2',
        'benefit_komunikasi' => 'decimal:2',
        'benefit_operasional' => 'decimal:2',
        'reward' => 'decimal:2',
        'total_pendapatan' => 'decimal:2',
        'bpjs_kesehatan_pengeluaran' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pengeluaran' => 'decimal:2',
        'bpjs_kematian_pengeluaran' => 'decimal:2',
        'bpjs_jht_pengeluaran' => 'decimal:2',
        'bpjs_jp_pengeluaran' => 'decimal:2',
        'tabungan_koperasi' => 'decimal:2',
        'koperasi' => 'decimal:2',
        'kasbon' => 'decimal:2',
        'umroh' => 'decimal:2',
        'kurban' => 'decimal:2',
        'mutabaah' => 'decimal:2',
        'potongan_absensi' => 'decimal:2',
        'potongan_kehadiran' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
    ];

    // Auto-calculate totals
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate Total Pendapatan
            $model->total_pendapatan = $model->gaji_pokok +
                $model->bpjs_kesehatan_pendapatan +
                $model->bpjs_kecelakaan_kerja_pendapatan +
                $model->bpjs_kematian_pendapatan +
                $model->bpjs_jht_pendapatan +
                $model->bpjs_jp_pendapatan +
                $model->tunjangan_prestasi +
                $model->tunjangan_konjungtur +
                $model->benefit_ibadah +
                $model->benefit_komunikasi +
                $model->benefit_operasional +
                $model->reward;
            
            // Calculate Total Pengeluaran
            $model->total_pengeluaran = $model->bpjs_kesehatan_pengeluaran +
                $model->bpjs_kecelakaan_kerja_pengeluaran +
                $model->bpjs_kematian_pengeluaran +
                $model->bpjs_jht_pengeluaran +
                $model->bpjs_jp_pengeluaran +
                $model->tabungan_koperasi +
                $model->koperasi +
                $model->kasbon +
                $model->umroh +
                $model->kurban +
                $model->mutabaah +
                $model->potongan_absensi +
                $model->potongan_kehadiran;
            
            // Calculate Gaji Bersih
            $model->gaji_bersih = $model->total_pendapatan - $model->total_pengeluaran;
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
}
