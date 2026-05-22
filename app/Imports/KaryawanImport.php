<?php

namespace App\Imports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KaryawanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Check for duplicate by nama_karyawan
        $exists = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();

        if ($exists) {
            $differences = 0;
            $diffFields = [];

            $fieldsToCheck = [
                'email', 'no_telp', 'jenis_karyawan', 'jabatan',
                'lokasi_kerja', 'bank', 'no_rekening',
            ];

            foreach ($fieldsToCheck as $field) {
                if (isset($row[$field]) && $row[$field] != $exists->$field) {
                    $differences++;
                    $diffFields[] = $field;
                }
            }

            if ($differences == 1) {
                \Log::warning("Data mirip dengan karyawan existing: {$row['nama_karyawan']}, berbeda di: ".implode(', ', $diffFields));
            }

            return null;
        }

        $joinDate = now();
        if (! empty($row['join_date'])) {
            try {
                $joinDate = Carbon::parse($row['join_date']);
            } catch (\Exception $e) {
                $joinDate = now();
            }
        }

        return new Karyawan([
            'nama_karyawan' => $row['nama_karyawan'] ?? '',
            'email' => $row['email'] ?? null,
            'no_telp' => $row['no_telp'] ?? null,
            'join_date' => $joinDate,
            'jenis_karyawan' => $row['jenis_karyawan'] ?? '',
            'jabatan' => $row['jabatan'] ?? '',
            'lokasi_kerja' => $row['lokasi_kerja'] ?? '',
            'status_karyawan' => $row['status_karyawan'] ?? 'Active',
            'bank' => $row['bank'] ?? '',
            'no_rekening' => $row['no_rekening'] ?? '',
            'npwp' => $row['npwp'] ?? null,
            'bpjs_kesehatan_no' => $row['bpjs_kesehatan_no'] ?? null,
            'bpjs_kecelakaan_kerja_no' => $row['bpjs_kecelakaan_kerja_no'] ?? null,
            'bpjs_tk_no' => $row['bpjs_tk_no'] ?? null,
            'status_perkawinan' => $row['status_perkawinan'] ?? null,
            'jumlah_anak' => $row['jumlah_anak'] ?? 0,
            'nama_istri' => $row['nama_istri'] ?? null,
            'no_telp_istri' => $row['no_telp_istri'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string|max:255',
            'email' => 'nullable|email',
            'jenis_karyawan' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'lokasi_kerja' => 'nullable|string|max:100',
            'status_karyawan' => 'nullable|in:Active,Resigned,Terminated',
            'join_date' => 'nullable|date',
            'no_telp' => 'nullable|string|max:20',
            'bank' => 'nullable|string|max:100',
            'no_rekening' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:30',
            'bpjs_kesehatan_no' => 'nullable|string|max:50',
            'bpjs_kecelakaan_kerja_no' => 'nullable|string|max:50',
            'bpjs_tk_no' => 'nullable|string|max:50',
            'status_perkawinan' => 'nullable|string|max:20',
            'jumlah_anak' => 'nullable|integer|min:0|max:99',
        ];
    }
}
