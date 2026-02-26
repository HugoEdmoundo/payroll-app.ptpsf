<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicFields;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory, HasDynamicFields;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    
    protected $fillable = [
        'nama_karyawan',
        'email',
        'no_telp',
        'join_date',
        'jabatan',
        'lokasi_kerja',
        'jenis_karyawan',
        'status_pegawai',
        'npwp',
        'bpjs_kesehatan_no',
        'bpjs_kecelakaan_kerja_no',
        'bpjs_tk_no',
        'no_rekening',
        'bank',
        'status_perkawinan',
        'nama_istri',
        'jumlah_anak',
        'no_telp_istri',
        'status_karyawan'
    ];

    protected $casts = [
        'join_date' => 'datetime',
        'jumlah_anak' => 'integer'
    ];
    
    // Boot method untuk set join_date dengan waktu SEKARANG dan auto-calculate status_pegawai
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($karyawan) {
            if (request()->has('join_date')) {
                $tanggal = Carbon::parse(request()->join_date)->format('Y-m-d');
                $waktuSekarang = Carbon::now()->format('H:i:s');
                $karyawan->join_date = Carbon::parse($tanggal . ' ' . $waktuSekarang);
            }
            
            // Auto-calculate status_pegawai
            $karyawan->status_pegawai = $karyawan->calculateStatusPegawai();
        });
        
        static::updating(function ($karyawan) {
            if (request()->has('join_date') && request()->join_date != $karyawan->getOriginal('join_date')->format('Y-m-d')) {
                $tanggal = Carbon::parse(request()->join_date)->format('Y-m-d');
                $waktuSekarang = Carbon::now()->format('H:i:s');
                $karyawan->join_date = Carbon::parse($tanggal . ' ' . $waktuSekarang);
            }
            
            // Auto-update status_pegawai
            $karyawan->status_pegawai = $karyawan->calculateStatusPegawai();
        });
    }
    
    /**
     * Calculate Status Pegawai based on join_date
     * Harian: 14 hari pertama (90rb/hari)
     * OJT: 3 bulan setelah fase harian (gaji tetap)
     * Kontrak: Setelah OJT selesai (karyawan normal)
     */
    public function calculateStatusPegawai()
    {
        if (!$this->join_date) {
            return 'Harian';
        }
        
        $now = Carbon::now();
        $join = Carbon::parse($this->join_date);
        $daysSinceJoin = $join->diffInDays($now);
        
        // Fase 1: Harian (14 hari pertama)
        if ($daysSinceJoin < 14) {
            return 'Harian';
        }
        
        // Fase 2: OJT (3 bulan setelah 14 hari)
        // 14 hari + 90 hari (3 bulan) = 104 hari total
        if ($daysSinceJoin < 104) {
            return 'OJT';
        }
        
        // Fase 3: Kontrak (setelah OJT selesai)
        return 'Kontrak';
    }
    
    /**
     * Get current status pegawai (accessor)
     */
    public function getStatusPegawaiAttribute($value)
    {
        // Always recalculate to ensure it's up to date
        return $this->calculateStatusPegawai();
    }
    
    /**
     * Get appropriate salary configuration based on status_pegawai
     * Returns PengaturanGaji or PengaturanGajiStatusPegawai
     */
    public function getPengaturanGaji()
    {
        $statusPegawai = $this->status_pegawai;
        
        // Check if status pegawai is Harian, OJT, or Kontrak
        if (in_array($statusPegawai, ['Harian', 'OJT', 'Kontrak'])) {
            // Get from PengaturanGajiStatusPegawai
            return \App\Models\PengaturanGajiStatusPegawai::where('status_pegawai', $statusPegawai)
                ->where('jabatan', $this->jabatan)
                ->where('lokasi_kerja', $this->lokasi_kerja)
                ->first();
        }
        
        // Default: Get from PengaturanGaji (normal employee)
        return \App\Models\PengaturanGaji::where('jenis_karyawan', $this->jenis_karyawan)
            ->where('jabatan', $this->jabatan)
            ->where('lokasi_kerja', $this->lokasi_kerja)
            ->first();
    }

    // Masa Kerja dalam format readable (X Bulan Y Hari) - KEDUANYA HARUS ADA
    public function getMasaKerjaReadableAttribute()
    {
        if (!$this->join_date) {
            return '0 Bulan 0 Hari';
        }

        $now = Carbon::now();
        $join = Carbon::parse($this->join_date);
        
        // Use diff to get accurate months and days
        $diff = $join->diff($now);
        
        // Total months from years and months
        $totalMonths = ($diff->y * 12) + $diff->m;
        
        // Remaining days
        $days = $diff->d;
        
        // Format: X Bulan Y Hari (keduanya selalu ditampilkan)
        return $totalMonths . ' Bulan ' . $days . ' Hari';
    }
    
    // Masa Kerja dalam format DD:HH:MM:SS (untuk backward compatibility)
    public function getMasaKerjaAttribute()
    {
        if (!$this->join_date) {
            return '00:00:00:00';
        }

        $now = Carbon::now();
        $join = Carbon::parse($this->join_date);
        
        $diff = $join->diff($now);
        
        return sprintf(
            '%02d:%02d:%02d:%02d',
            $diff->days,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }
    // Format tanggal untuk display
    public function getFormattedJoinDateAttribute()
    {
        if (!$this->join_date) {
            return '-';
        }
        return $this->join_date->format('d/m/Y H:i:s') . ' WIB';
    }
}