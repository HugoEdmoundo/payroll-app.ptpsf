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
        return new Karyawan([
            'nama_karyawan' => $row['nama_karyawan'] ?? '',
            'join_date' => isset($row['join_date']) ? Carbon::parse($row['join_date']) : now(),
            'jabatan' => $row['jabatan'] ?? '',
            'lokasi_kerja' => $row['lokasi_kerja'] ?? '',
            'jenis_karyawan' => $row['jenis_karyawan'] ?? '',
            'status_pegawai' => $row['status_pegawai'] ?? 'Aktif',
            'npwp' => $row['npwp'] ?? null,
            'bpjs_kesehatan_no' => $row['bpjs_kesehatan_no'] ?? null,
            'bpjs_tk_no' => $row['bpjs_tk_no'] ?? null,
            'no_rekening' => $row['no_rekening'] ?? '',
            'bank' => $row['bank'] ?? '',
            'status_perkawinan' => $row['status_perkawinan'] ?? null,
            'nama_istri' => $row['nama_istri'] ?? null,
            'jumlah_anak' => $row['jumlah_anak'] ?? 0,
            'no_telp_istri' => $row['no_telp_istri'] ?? null,
            'status_karyawan' => $row['status_karyawan'] ?? '',
        ]);
    }
}