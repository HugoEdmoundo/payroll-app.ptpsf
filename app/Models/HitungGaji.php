<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitungGaji extends Model
{
    protected $table = 'hitung_gaji';
    
    protected $fillable = [
        'acuan_gaji_id',
        'karyawan_id',
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
        // Pengeluaran
        'bpjs_kesehatan_pengeluaran',
        'bpjs_kecelakaan_kerja_pengeluaran',
        'bpjs_kematian_pengeluaran',
        'bpjs_jht_pengeluaran',
        'bpjs_jp_pengeluaran',
        'koperasi',
        'kasbon',
        'umroh',
        'kurban',
        'mutabaah',
        'potongan_absensi',
        'potongan_kehadiran',
        // Adjustments & Totals
        'adjustments',
        'total_pendapatan',
        'total_pengeluaran',
        'gaji_bersih',
        'status',
        'approved_at',
        'approved_by',
        'keterangan'
    ];

    protected $casts = [
        'adjustments' => 'array',
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
        'bpjs_kesehatan_pengeluaran' => 'decimal:2',
        'bpjs_kecelakaan_kerja_pengeluaran' => 'decimal:2',
        'bpjs_kematian_pengeluaran' => 'decimal:2',
        'bpjs_jht_pengeluaran' => 'decimal:2',
        'bpjs_jp_pengeluaran' => 'decimal:2',
        'koperasi' => 'decimal:2',
        'kasbon' => 'decimal:2',
        'umroh' => 'decimal:2',
        'kurban' => 'decimal:2',
        'mutabaah' => 'decimal:2',
        'potongan_absensi' => 'decimal:2',
        'potongan_kehadiran' => 'decimal:2',
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    // Auto-calculate totals
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate total pendapatan with adjustments
            $pendapatanFields = [
                'gaji_pokok', 'bpjs_kesehatan_pendapatan', 'bpjs_kecelakaan_kerja_pendapatan',
                'bpjs_kematian_pendapatan', 'bpjs_jht_pendapatan', 'bpjs_jp_pendapatan',
                'tunjangan_prestasi', 'tunjangan_konjungtur', 'benefit_ibadah',
                'benefit_komunikasi', 'benefit_operasional', 'reward'
            ];
            
            $totalPendapatan = 0;
            foreach ($pendapatanFields as $field) {
                $value = $model->$field;
                // Add adjustment if exists
                if (isset($model->adjustments[$field])) {
                    $adj = $model->adjustments[$field];
                    if ($adj['type'] === '+') {
                        $value += $adj['nominal'];
                    } else {
                        $value -= $adj['nominal'];
                    }
                }
                $totalPendapatan += $value;
            }
            
            // Calculate total pengeluaran with adjustments
            $pengeluaranFields = [
                'bpjs_kesehatan_pengeluaran', 'bpjs_kecelakaan_kerja_pengeluaran',
                'bpjs_kematian_pengeluaran', 'bpjs_jht_pengeluaran', 'bpjs_jp_pengeluaran',
                'koperasi', 'kasbon', 'umroh', 'kurban',
                'mutabaah', 'potongan_absensi', 'potongan_kehadiran'
            ];
            
            $totalPengeluaran = 0;
            foreach ($pengeluaranFields as $field) {
                $value = $model->$field;
                // Add adjustment if exists
                if (isset($model->adjustments[$field])) {
                    $adj = $model->adjustments[$field];
                    if ($adj['type'] === '+') {
                        $value += $adj['nominal'];
                    } else {
                        $value -= $adj['nominal'];
                    }
                }
                $totalPengeluaran += $value;
            }
            
            $model->total_pendapatan = $totalPendapatan;
            $model->total_pengeluaran = $totalPengeluaran;
            $model->gaji_bersih = $totalPendapatan - $totalPengeluaran;
        });
    }

    public function acuanGaji()
    {
        return $this->belongsTo(AcuanGaji::class, 'acuan_gaji_id', 'id_acuan');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function slipGaji()
    {
        return $this->hasOne(SlipGaji::class);
    }
    
    // Get final value for a field (with adjustment)
    public function getFinalValue($field)
    {
        $value = $this->$field;
        
        if (isset($this->adjustments[$field])) {
            $adj = $this->adjustments[$field];
            if ($adj['type'] === '+') {
                $value += $adj['nominal'];
            } else {
                $value -= $adj['nominal'];
            }
        }
        
        return $value;
    }
    
    // Get adjustment for a field
    public function getAdjustment($field)
    {
        return $this->adjustments[$field] ?? null;
    }
}
