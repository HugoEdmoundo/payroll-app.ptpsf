<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class KaryawanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check for duplicate by nama_karyawan
        $exists = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();
        
        if ($exists) {
            // Check if data is similar (only different in 1 field)
            $differences = 0;
            $diffFields = [];
            
            $fieldsToCheck = [
                'email', 'no_telp', 'jenis_karyawan', 'jabatan', 
                'lokasi_kerja', 'bank', 'no_rekening'
            ];
            
            foreach ($fieldsToCheck as $field) {
                if (isset($row[$field]) && $exists->$field != $row[$field]) {
                    $differences++;
                    $diffFields[] = $field;
                }
            }
            
            // If only 1 difference, log warning but skip
            if ($differences == 1) {
                \Log::warning("Data mirip dengan karyawan existing: {$row['nama_karyawan']}, berbeda di: " . implode(', ', $diffFields));
            }
            
            return null; // Skip duplicate
        }

        return new Karyawan([
            'nama_karyawan' => $row['nama_karyawan'] ?? '',
            'email' => $row['email'] ?? null,
            'no_telp' => $row['no_telp'] ?? null,
            'join_date' => isset($row['join_date']) ? Carbon::parse($row['join_date']) : now(),
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
}