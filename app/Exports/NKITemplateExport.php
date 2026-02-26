<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NKITemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Return 3 example rows
        return [
            [
                'John Doe', // nama_karyawan
                '2024-01', // periode (YYYY-MM format)
                8.5, // kemampuan (20%) - Max: 10
                9.0, // kontribusi_1 (20%) - Max: 10
                8.0, // kontribusi_2 (40%) - Max: 10
                7.5, // kedisiplinan (20%) - Max: 10
                'Contoh keterangan', // keterangan
            ],
            [
                'Jane Smith',
                '2024-01',
                7.0,
                8.0,
                9.0,
                8.5,
                '',
            ],
            [
                'Bob Johnson',
                '2024-01',
                9.5,
                9.0,
                8.5,
                9.0,
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_karyawan',
            'periode',
            'kemampuan_20',
            'kontribusi_1_20',
            'kontribusi_2_40',
            'kedisiplinan_20',
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
