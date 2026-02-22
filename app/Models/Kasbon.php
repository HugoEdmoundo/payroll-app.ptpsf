<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> fitur-baru
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
<<<<<<< HEAD
    protected $table = 'kasbon';
    
    protected $fillable = [
        'karyawan_id',
        'nomor_kasbon',
=======
    use HasFactory;

    protected $table = 'kasbon';
    protected $primaryKey = 'id_kasbon';

    protected $fillable = [
        'id_karyawan',
        'periode',
>>>>>>> fitur-baru
        'tanggal_pengajuan',
        'deskripsi',
        'nominal',
        'metode_pembayaran',
<<<<<<< HEAD
        'jumlah_cicilan',
        'nominal_cicilan',
        'status',
        'sisa_hutang',
        'approved_by',
        'approved_at',
        'catatan_approval'
=======
        'status_pembayaran',
        'jumlah_cicilan',
        'cicilan_terbayar',
        'sisa_cicilan',
        'keterangan',
>>>>>>> fitur-baru
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'nominal' => 'decimal:2',
        'jumlah_cicilan' => 'integer',
<<<<<<< HEAD
        'nominal_cicilan' => 'decimal:2',
        'sisa_hutang' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cicilan()
    {
        return $this->hasMany(KasbonCicilan::class);
    }

    public static function generateNomorKasbon()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'KB-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
=======
        'cicilan_terbayar' => 'integer',
        'sisa_cicilan' => 'decimal:2',
    ];

    // Auto-calculate sisa cicilan
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->metode_pembayaran === 'Langsung') {
                $model->jumlah_cicilan = null;
                $model->cicilan_terbayar = 0;
                $model->sisa_cicilan = 0;
            } else {
                // For Cicilan method
                if ($model->jumlah_cicilan > 0) {
                    $nominalPerCicilan = $model->nominal / $model->jumlah_cicilan;
                    $totalTerbayar = $nominalPerCicilan * $model->cicilan_terbayar;
                    $model->sisa_cicilan = $model->nominal - $totalTerbayar;
                    
                    // Update status if fully paid
                    if ($model->cicilan_terbayar >= $model->jumlah_cicilan) {
                        $model->status_pembayaran = 'Lunas';
                        $model->sisa_cicilan = 0;
                    }
                }
            }
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Get nominal per cicilan
    public function getNominalPerCicilanAttribute()
    {
        if ($this->metode_pembayaran === 'Cicilan' && $this->jumlah_cicilan > 0) {
            return $this->nominal / $this->jumlah_cicilan;
        }
        return 0;
>>>>>>> fitur-baru
    }
}
