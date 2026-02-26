<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasFactory;

    protected $table = 'kasbon';
    protected $primaryKey = 'id_kasbon';

    protected $fillable = [
        'id_karyawan',
        'periode',
        'tanggal_pengajuan',
        'deskripsi',
        'nominal',
        'metode_pembayaran',
        'status_pembayaran',
        'jumlah_cicilan',
        'cicilan_terbayar',
        'sisa_cicilan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'nominal' => 'decimal:2',
        'jumlah_cicilan' => 'integer',
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
    
    public function cicilan()
    {
        return $this->hasMany(KasbonCicilan::class, 'id_kasbon', 'id_kasbon')->orderBy('cicilan_ke');
    }

    // Get nominal per cicilan
    public function getNominalPerCicilanAttribute()
    {
        if ($this->metode_pembayaran === 'Cicilan' && $this->jumlah_cicilan > 0) {
            return $this->nominal / $this->jumlah_cicilan;
        }
        return 0;
    }
    
    // Get cicilan untuk periode tertentu
    public function getCicilanForPeriode($periode)
    {
        return $this->cicilan()->where('periode', $periode)->first();
    }
    
    // Get potongan kasbon untuk periode tertentu (untuk acuan gaji)
    public function getPotonganForPeriode($periode)
    {
        if ($this->metode_pembayaran === 'Langsung') {
            // Jika langsung, potong di periode kasbon dibuat
            return $this->periode === $periode ? $this->nominal : 0;
        } else {
            // Jika cicilan, check if there's already a cicilan record
            $cicilan = $this->getCicilanForPeriode($periode);
            if ($cicilan) {
                return $cicilan->nominal_cicilan;
            }
            
            // If no cicilan record yet, calculate nominal per cicilan
            // Only if kasbon is still pending (not fully paid)
            if ($this->status_pembayaran !== 'Lunas' && $this->jumlah_cicilan > 0) {
                return $this->nominal / $this->jumlah_cicilan;
            }
            
            return 0;
        }
    }
    
    // Get total yang sudah dibayar
    public function getTotalPaidAttribute()
    {
        return $this->cicilan()->sum('nominal_cicilan');
    }
    
    // Get payment status info with color
    public function getPaymentStatusInfo()
    {
        $totalPaid = $this->total_paid;
        $nominal = $this->nominal;
        
        // Build cicilan info if applicable
        $cicilanInfo = '';
        if ($this->metode_pembayaran === 'Cicilan' && $this->jumlah_cicilan > 0) {
            $cicilanPaid = $this->cicilan()->count();
            $cicilanRemaining = $this->jumlah_cicilan - $cicilanPaid;
            if ($cicilanRemaining > 0) {
                $cicilanInfo = " - Sisa {$cicilanRemaining} cicilan";
            }
        }
        
        if ($totalPaid >= $nominal) {
            $diff = $totalPaid - $nominal;
            if ($diff > 0) {
                // Overpaid
                return [
                    'status' => 'Overpaid',
                    'color' => 'red',
                    'bg_color' => 'bg-red-100',
                    'text_color' => 'text-red-800',
                    'border_color' => 'border-red-300',
                    'message' => 'Lebih bayar Rp ' . number_format($diff, 0, ',', '.'),
                    'diff' => $diff
                ];
            } else {
                // Exactly paid
                return [
                    'status' => 'Lunas',
                    'color' => 'green',
                    'bg_color' => 'bg-green-100',
                    'text_color' => 'text-green-800',
                    'border_color' => 'border-green-300',
                    'message' => 'Lunas',
                    'diff' => 0
                ];
            }
        } else {
            // Still pending
            $remaining = $nominal - $totalPaid;
            return [
                'status' => 'Pending',
                'color' => 'yellow',
                'bg_color' => 'bg-yellow-100',
                'text_color' => 'text-yellow-800',
                'border_color' => 'border-yellow-300',
                'message' => 'Sisa Rp ' . number_format($remaining, 0, ',', '.') . $cicilanInfo,
                'diff' => -$remaining
            ];
        }
    }
}
