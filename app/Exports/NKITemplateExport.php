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
                1, // id_karyawan (example)
                '2024-01', // periode (YYYY-MM format)
                8.5, // kemampuan (0-10)
                9.0, // kontribusi (0-10)
                8.0, // kedisiplinan (0-10)
                7.5, // lainnya (0-10)
                'Contoh keterangan', // keterangan
            ],
            [
                2,
                '2024-01',
                7.0,
                8.0,
                9.0,
                8.5,
                '',
            ],
            [
                3,
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
            'id_karyawan',
            'periode',
            'kemampuan',
            'kontribusi',
            'kedisiplinan',
            'lainnya',
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
