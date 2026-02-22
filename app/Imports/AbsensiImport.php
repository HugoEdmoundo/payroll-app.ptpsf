<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AbsensiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find karyawan by name
        $karyawan = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();
        
        if (!$karyawan) {
            return null; // Skip if karyawan not found
        }

        // Check if already exists
        $exists = Absensi::where('id_karyawan', $karyawan->id_karyawan)
                        ->where('periode', $row['periode'])
                        ->exists();
        
        if ($exists) {
            return null; // Skip if already exists
        }

        return new Absensi([
            'id_karyawan' => $karyawan->id_karyawan,
            'periode' => $row['periode'],
            'hadir' => $row['hadir'] ?? 0,
            'on_site' => $row['on_site'] ?? 0,
            'absence' => $row['absence'] ?? 0,
            'idle_rest' => $row['idle_rest'] ?? 0,
            'izin_sakit_cuti' => $row['izin_sakit_cuti'] ?? 0,
            'tanpa_keterangan' => $row['tanpa_keterangan'] ?? 0,
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'hadir' => 'required|integer|min:0',
            'on_site' => 'nullable|integer|min:0',
            'absence' => 'nullable|integer|min:0',
            'idle_rest' => 'nullable|integer|min:0',
            'izin_sakit_cuti' => 'nullable|integer|min:0',
            'tanpa_keterangan' => 'nullable|integer|min:0',
        ];
    }
}
