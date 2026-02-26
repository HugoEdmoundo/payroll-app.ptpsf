<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Return 3 example rows
        return [
            [
                'John Doe', // nama_karyawan
                '2024-01', // periode (YYYY-MM format)
                20, // hadir
                5, // on_site
                3, // on_base
                2, // absence
                1, // idle_rest
                1, // izin_sakit_cuti
                0, // tanpa_keterangan
                'Contoh keterangan', // keterangan
            ],
            [
                'Jane Smith',
                '2024-01',
                22,
                3,
                2,
                0,
                0,
                0,
                0,
                '',
            ],
            [
                'Bob Johnson',
                '2024-01',
                21,
                4,
                1,
                1,
                0,
                0,
                0,
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_karyawan',
            'periode',
            'hadir',
            'on_site',
            'on_base',
            'absence',
            'idle_rest',
            'izin_sakit_cuti',
            'tanpa_keterangan',
            'keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
