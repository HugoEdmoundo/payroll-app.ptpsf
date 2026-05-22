<?php

namespace App\Observers;

use App\Models\Absensi;
use App\Services\AttendanceService;

class AbsensiObserver
{
    public function saved(Absensi $absensi): void
    {
        app(AttendanceService::class)->recalculateDeduction($absensi);
    }
}
