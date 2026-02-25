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

        // Skip if karyawan is not Active
        if ($karyawan->status_karyawan !== 'Active') {
            return null; // Skip non-active/resign karyawan
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
            'kemampuan' => $row['kemampuan_20'],
            'kontribusi_1' => $row['kontribusi_1_20'],
            'kontribusi_2' => $row['kontribusi_2_40'],
            'kedisiplinan' => $row['kedisiplinan_20'],
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'kemampuan_20' => 'required|numeric|min:0|max:10',
            'kontribusi_1_20' => 'required|numeric|min:0|max:10',
            'kontribusi_2_40' => 'required|numeric|min:0|max:10',
            'kedisiplinan_20' => 'required|numeric|min:0|max:10',
        ];
    }
}
