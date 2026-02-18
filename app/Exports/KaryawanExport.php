<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Karyawan::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Email',
            'No Telp',
            'Join Date',
            'Masa Kerja (Hari)',
            'Jabatan',
            'Lokasi Kerja',
            'Jenis Karyawan',
            'Status Pegawai',
            'NPWP',
            'BPJS Kesehatan',
            'BPJS TK',
            'No Rekening',
            'Bank',
            'Status Perkawinan',
            'Nama Istri',
            'Jumlah Anak',
            'No Telp Istri',
            'Status Karyawan',
            'Created At',
            'Updated At'
        ];
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->id_karyawan,
            $karyawan->nama_karyawan,
            $karyawan->email,
            $karyawan->no_telp,
            $karyawan->join_date->format('Y-m-d'),
            $karyawan->masa_kerja,
            $karyawan->jabatan,
            $karyawan->lokasi_kerja,
            $karyawan->jenis_karyawan,
            $karyawan->status_pegawai,
            $karyawan->npwp,
            $karyawan->bpjs_kesehatan_no,
            $karyawan->bpjs_tk_no,
            $karyawan->no_rekening,
            $karyawan->bank,
            $karyawan->status_perkawinan,
            $karyawan->nama_istri,
            $karyawan->jumlah_anak,
            $karyawan->no_telp_istri,
            $karyawan->status_karyawan,
            $karyawan->created_at->format('Y-m-d H:i:s'),
            $karyawan->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}