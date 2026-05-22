<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcuanGaji extends Model
{
    use \App\Traits\HasSalaryComponents;

    protected $table = 'acuan_gaji';

    protected $primaryKey = 'id_acuan';

    protected $fillable = [
        'id_karyawan',
        'lokasi_kerja',
        'periode',
        'gaji_pokok', 'bpjs_kesehatan', 'bpjs_kecelakaan_kerja',
        'bpjs_kematian', 'bpjs_jht', 'bpjs_jp',
        'tunjangan_prestasi', 'tunjangan_konjungtur',
        'benefit_ibadah', 'benefit_komunikasi', 'benefit_operasional', 'reward',
        'koperasi', 'kasbon', 'umroh', 'kurban', 'mutabaah',
        'potongan_absensi', 'potongan_kehadiran',
        'total_pendapatan', 'total_pengeluaran', 'gaji_bersih',
        'keterangan',
    ];

    public function getCasts()
    {
        return array_merge(parent::getCasts(), self::salaryComponentCasts(), [
            'total_pendapatan' => 'decimal:2',
            'total_pengeluaran' => 'decimal:2',
            'gaji_bersih' => 'decimal:2',
        ]);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function hitungGaji()
    {
        return $this->hasOne(HitungGaji::class, 'acuan_gaji_id', 'id_acuan');
    }

    public function getTotalPendapatanAttribute($value)
    {
        return $value ?: $this->calculateTotalIncome();
    }

    public function getTotalPengeluaranAttribute($value)
    {
        return $value ?: $this->calculateTotalDeduction();
    }

    public function getGajiBersihAttribute($value)
    {
        return $value ?: $this->calculateNetSalary();
    }
}
