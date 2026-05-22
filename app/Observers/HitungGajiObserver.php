<?php

namespace App\Observers;

use App\Models\HitungGaji;
use App\Services\LoanService;

class HitungGajiObserver
{
    public function created(HitungGaji $hitungGaji): void
    {
        $this->syncLoanInstallment($hitungGaji);
    }

    public function updated(HitungGaji $hitungGaji): void
    {
        $this->syncLoanInstallment($hitungGaji);
    }

    public function deleted(HitungGaji $hitungGaji): void
    {
        $loanService = app(LoanService::class);
        $loanService->removeInstallment($hitungGaji->karyawan_id, $hitungGaji->periode);
    }

    private function syncLoanInstallment(HitungGaji $hitungGaji): void
    {
        $kasbonAmount = $hitungGaji->getFinalValue('kasbon');

        if ($kasbonAmount <= 0) {
            return;
        }

        $kasbon = \App\Models\Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
            ->whereIn('status_pembayaran', ['Pending', 'Lunas'])
            ->orderBy('tanggal_pengajuan')
            ->first();

        if (! $kasbon) {
            return;
        }

        $loanService = app(LoanService::class);
        $loanService->recordInstallment($kasbon->id_kasbon, $hitungGaji->periode, $kasbonAmount);
    }
}
