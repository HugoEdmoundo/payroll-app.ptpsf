<?php

namespace App\Services;

use App\Models\Kasbon;
use App\Models\KasbonCicilan;

class LoanService
{
    /**
     * Calculate/sync installment fields before saving
     */
    public function calculateFields(Kasbon $kasbon): void
    {
        if ($kasbon->metode_pembayaran === 'Langsung') {
            $kasbon->jumlah_cicilan = null;
            $kasbon->cicilan_terbayar = 0;
            $kasbon->sisa_cicilan = 0;
        } elseif ($kasbon->jumlah_cicilan > 0) {
            $nominalPerCicilan = $kasbon->nominal / $kasbon->jumlah_cicilan;
            $totalTerbayar = $nominalPerCicilan * $kasbon->cicilan_terbayar;
            $kasbon->sisa_cicilan = $kasbon->nominal - $totalTerbayar;

            if ($kasbon->cicilan_terbayar >= $kasbon->jumlah_cicilan) {
                $kasbon->status_pembayaran = 'Lunas';
                $kasbon->sisa_cicilan = 0;
            }
        }
    }

    /**
     * Update kasbon status based on payment progress
     */
    public function updateKasbonStatus(Kasbon $kasbon): void
    {
        $totalPaid = KasbonCicilan::where('id_kasbon', $kasbon->id_kasbon)->sum('nominal_cicilan');
        $nominal = $kasbon->nominal;

        if ($totalPaid >= $nominal) {
            $kasbon->status_pembayaran = 'Lunas';
            $kasbon->sisa_cicilan = 0;
            $kasbon->cicilan_terbayar = $kasbon->jumlah_cicilan ?? 0;
        } else {
            $kasbon->status_pembayaran = 'Pending';
            $kasbon->sisa_cicilan = $nominal - $totalPaid;

            if ($kasbon->metode_pembayaran === 'Cicilan' && $kasbon->jumlah_cicilan > 0) {
                $nominalPerCicilan = $nominal / $kasbon->jumlah_cicilan;
                $kasbon->cicilan_terbayar = (int) floor($totalPaid / $nominalPerCicilan);
            }
        }

        $kasbon->saveQuietly();
    }

    /**
     * Record installment from HitungGaji kasbon amount
     */
    public function recordInstallment(int $kasbonId, string $periode, float $amount): ?KasbonCicilan
    {
        $kasbon = Kasbon::find($kasbonId);
        if (! $kasbon || $amount <= 0) {
            return null;
        }

        $cicilan = KasbonCicilan::where('id_kasbon', $kasbonId)
            ->where('periode', $periode)
            ->first();

        if (! $cicilan) {
            $lastCicilan = KasbonCicilan::where('id_kasbon', $kasbonId)
                ->orderBy('cicilan_ke', 'desc')
                ->first();

            $cicilanKe = $lastCicilan ? $lastCicilan->cicilan_ke + 1 : 1;

            $cicilan = new KasbonCicilan;
            $cicilan->id_kasbon = $kasbonId;
            $cicilan->periode = $periode;
            $cicilan->cicilan_ke = $cicilanKe;
        }

        $cicilan->nominal_cicilan = $amount;
        $cicilan->tanggal_bayar = now();
        $cicilan->status = 'Terbayar';
        $cicilan->save();

        $this->updateKasbonStatus($kasbon);

        return $cicilan;
    }

    /**
     * Remove installment record when HitungGaji is deleted
     */
    public function removeInstallment(int $karyawanId, string $periode): void
    {
        KasbonCicilan::where('periode', $periode)
            ->whereHas('kasbon', function ($query) use ($karyawanId) {
                $query->where('id_karyawan', $karyawanId);
            })
            ->delete();

        $kasbon = Kasbon::where('id_karyawan', $karyawanId)
            ->whereIn('status_pembayaran', ['Pending', 'Lunas'])
            ->orderBy('tanggal_pengajuan')
            ->first();

        if ($kasbon) {
            $this->updateKasbonStatus($kasbon);
        }
    }
}
