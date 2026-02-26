<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class KaryawanTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return 3 example rows
        return [
            [
                'John Doe',
                'john.doe@example.com',
                '081234567890',
                '2024-01-15',
                'Organik',
                'Manager',
                'Jakarta',
                'Active',
                'BCA',
                '1234567890',
                '1234567890123456',
                '0001234567890',
                '0009876543210',
                '1234567890123',
                'Menikah',
                '2',
                'Jane Doe',
                '081298765432',
            ],
            [
                'Jane Smith',
                'jane.smith@example.com',
                '082345678901',
                '2024-02-20',
                'Konsultan',
                'Staff',
                'Bandung',
                'Active',
                'Mandiri',
                '9876543210',
                '9876543210987654',
                '0009876543210',
                '0001234567890',
                '9876543210987',
                'Belum Menikah',
                '0',
                '',
                '',
            ],
            [
                'Bob Johnson',
                'bob.johnson@example.com',
                '083456789012',
                '2024-03-10',
                'Organik',
                'Supervisor',
                'Surabaya',
                'Active',
                'BNI',
                '5555666677',
                '5555666677778888',
                '0005555666677',
                '0008888777766',
                '5555666677778',
                'Menikah',
                '1',
                'Alice Johnson',
                '083987654321',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_karyawan',
            'email',
            'no_telp',
            'join_date',
            'jenis_karyawan',
            'jabatan',
            'lokasi_kerja',
            'status_karyawan',
            'bank',
            'no_rekening',
            'npwp',
            'bpjs_kesehatan_no',
            'bpjs_kecelakaan_kerja_no',
            'bpjs_tk_no',
            'status_perkawinan',
            'jumlah_anak',
            'nama_istri',
            'no_telp_istri',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // nama_karyawan
            'B' => 25, // email
            'C' => 15, // no_telp
            'D' => 12, // join_date
            'E' => 15, // jenis_karyawan
            'F' => 15, // jabatan
            'G' => 15, // lokasi_kerja
            'H' => 15, // status_karyawan
            'I' => 12, // bank
            'J' => 18, // no_rekening
            'K' => 20, // npwp
            'L' => 18, // bpjs_kesehatan_no
            'M' => 22, // bpjs_kecelakaan_kerja_no
            'N' => 18, // bpjs_tk_no
            'O' => 18, // status_perkawinan
            'P' => 12, // jumlah_anak
            'Q' => 20, // nama_istri
            'R' => 15, // no_telp_istri
        ];
    }
}
