<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NKI;
use App\Models\Karyawan;
use Carbon\Carbon;

class NKISeeder extends Seeder
{
    public function run(): void
    {
        $periode = Carbon::now()->format('Y-m');
        $karyawanList = Karyawan::where('status_karyawan', 'Active')->get();

        foreach ($karyawanList as $karyawan) {
            // Skip Harian/OJT status (they don't have NKI)
            if (in_array($karyawan->status_pegawai, ['Harian', 'OJT'])) {
                continue;
            }

            // Random NKI value between 7.5 and 9.5
            $nilaiNKI = rand(75, 95) / 10;
            
            // Calculate persentase tunjangan based on NKI
            if ($nilaiNKI >= 8.5) {
                $persentaseTunjangan = 100;
            } elseif ($nilaiNKI >= 8.0) {
                $persentaseTunjangan = 80;
            } else {
                $persentaseTunjangan = 70;
            }

            NKI::updateOrCreate(
                [
                    'id_karyawan' => $karyawan->id_karyawan,
                    'periode' => $periode,
                ],
                [
                    'nilai_nki' => $nilaiNKI,
                    'persentase_tunjangan' => $persentaseTunjangan,
                    'keterangan' => $nilaiNKI >= 8.5 ? 'Excellent performance' : ($nilaiNKI >= 8.0 ? 'Good performance' : 'Satisfactory performance'),
                ]
            );
        }
    }
}
