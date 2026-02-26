<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Carbon::now()->format('Y-m');
        $jumlahHariBulan = Carbon::now()->daysInMonth;
        $karyawanList = Karyawan::where('status_karyawan', 'Active')->get();

        foreach ($karyawanList as $karyawan) {
            // Random attendance data
            $hadir = rand($jumlahHariBulan - 5, $jumlahHariBulan); // Most employees have good attendance
            $absence = rand(0, 2); // 0-2 days absence
            $tanpaKeterangan = rand(0, 1); // 0-1 days without notice
            $onBase = rand(0, 3); // 0-3 days on base

            Absensi::updateOrCreate(
                [
                    'id_karyawan' => $karyawan->id_karyawan,
                    'periode' => $periode,
                ],
                [
                    'hadir' => $hadir,
                    'absence' => $absence,
                    'tanpa_keterangan' => $tanpaKeterangan,
                    'on_base' => $onBase,
                    'jumlah_hari_bulan' => $jumlahHariBulan,
                    'keterangan' => $absence > 0 ? 'Sick leave' : null,
                ]
            );
        }
    }
}
