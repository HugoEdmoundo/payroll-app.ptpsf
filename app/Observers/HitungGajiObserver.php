<?php

namespace App\Observers;

use App\Models\HitungGaji;
use App\Models\Kasbon;
use App\Models\KasbonCicilan;

class HitungGajiObserver
{
    /**
     * Handle the HitungGaji "created" event.
     * Auto-update Kasbon status when Hitung Gaji is created
     */
    public function created(HitungGaji $hitungGaji): void
    {
        $this->updateKasbonStatus($hitungGaji);
    }

    /**
     * Handle the HitungGaji "updated" event.
     * Auto-update Kasbon status when Hitung Gaji is updated
     */
    public function updated(HitungGaji $hitungGaji): void
    {
        $this->updateKasbonStatus($hitungGaji);
    }

    /**
     * Update Kasbon status based on Hitung Gaji kasbon amount
     */
    private function updateKasbonStatus(HitungGaji $hitungGaji)
    {
        // Get final kasbon value (with adjustments)
        $kasbonAmount = $hitungGaji->getFinalValue('kasbon');
        
        if ($kasbonAmount <= 0) {
            return; // No kasbon in this periode
        }

        // Find active kasbon for this karyawan (including Lunas to allow updates)
        $kasbon = Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
                       ->whereIn('status_pembayaran', ['Pending', 'Cicilan', 'Lunas'])
                       ->orderBy('tanggal_pengajuan', 'asc')
                       ->first();

        if (!$kasbon) {
            return; // No active kasbon found
        }

        // Record or update cicilan
        $cicilan = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)
                                ->where('periode', $hitungGaji->periode)
                                ->first();

        if (!$cicilan) {
            // Create new cicilan - calculate cicilan_ke
            $lastCicilan = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)
                                       ->orderBy('cicilan_ke', 'desc')
                                       ->first();
            
            $cicilanKe = $lastCicilan ? $lastCicilan->cicilan_ke + 1 : 1;
            
            $cicilan = new KasbonCicilan();
            $cicilan->id_kasbon = $kasbon->id_kasbon;
            $cicilan->periode = $hitungGaji->periode;
            $cicilan->cicilan_ke = $cicilanKe;
        }

        $cicilan->nominal_cicilan = $kasbonAmount;
        $cicilan->tanggal_bayar = now();
        $cicilan->status = 'Terbayar';
        $cicilan->keterangan = "Auto-recorded from Hitung Gaji";
        $cicilan->save();

        // Calculate total paid
        $totalPaid = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)->sum('nominal_cicilan');
        
        // Update kasbon status
        if ($totalPaid >= $kasbon->nominal) {
            // Fully paid or overpaid
            $kasbon->status_pembayaran = 'Lunas';
            $kasbon->sisa_cicilan = 0;
            $kasbon->cicilan_terbayar = $kasbon->jumlah_cicilan ?? 0;
        } else {
            // Still pending
            $kasbon->status_pembayaran = 'Pending';
            $kasbon->sisa_cicilan = $kasbon->nominal - $totalPaid;
            
            // Calculate cicilan terbayar
            if ($kasbon->metode_pembayaran === 'Cicilan' && $kasbon->jumlah_cicilan > 0) {
                $nominalPerCicilan = $kasbon->nominal / $kasbon->jumlah_cicilan;
                $kasbon->cicilan_terbayar = floor($totalPaid / $nominalPerCicilan);
            }
        }
        
        $kasbon->save();
    }

    /**
     * Handle the HitungGaji "deleted" event.
     */
    public function deleted(HitungGaji $hitungGaji): void
    {
        // When hitung gaji is deleted, remove the cicilan record
        $kasbonAmount = $hitungGaji->getFinalValue('kasbon');
        
        if ($kasbonAmount <= 0) {
            return;
        }

        // Find and delete cicilan for this periode
        KasbonCicilan::where('periode', $hitungGaji->periode)
                    ->whereHas('kasbon', function($query) use ($hitungGaji) {
                        $query->where('id_karyawan', $hitungGaji->karyawan_id);
                    })
                    ->delete();

        // Recalculate kasbon status
        $kasbon = Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
                       ->whereIn('status_pembayaran', ['Pending', 'Cicilan', 'Lunas'])
                       ->orderBy('tanggal_pengajuan', 'asc')
                       ->first();

        if ($kasbon) {
            $totalPaid = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)->sum('nominal_cicilan');
            
            if ($totalPaid >= $kasbon->nominal) {
                $kasbon->status_pembayaran = 'Lunas';
                $kasbon->sisa_cicilan = 0;
            } else {
                $kasbon->status_pembayaran = 'Pending';
                $kasbon->sisa_cicilan = $kasbon->nominal - $totalPaid;
            }
            
            $kasbon->save();
        }
    }
}
