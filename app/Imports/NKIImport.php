<?php

namespace App\Imports;

use App\Models\NKI;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NKIImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find karyawan by name
        $karyawan = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();
        
        if (!$karyawan) {
            return null; // Skip if karyawan not found
        }

        // Check if already exists
        $exists = NKI::where('id_karyawan', $karyawan->id_karyawan)
                    ->where('periode', $row['periode'])
                    ->exists();
        
        if ($exists) {
            return null; // Skip if already exists
        }

        return new NKI([
            'id_karyawan' => $karyawan->id_karyawan,
            'periode' => $row['periode'],
            'kemampuan' => $row['kemampuan'],
            'kontribusi' => $row['kontribusi'],
            'kedisiplinan' => $row['kedisiplinan'],
            'lainnya' => $row['lainnya'],
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'kontribusi' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'lainnya' => 'required|numeric|min:0|max:10',
        ];
    }
}
