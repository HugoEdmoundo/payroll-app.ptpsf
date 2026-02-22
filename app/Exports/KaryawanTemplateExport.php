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
                'Manager',
                'Jakarta',
                'Organik',
                'Tetap',
                '1234567890123456',
                '0001234567890',
                '0009876543210',
                '1234567890123',
                '1234567890',
                'BCA',
                'Menikah',
                'Jane Doe',
                '2',
                '081298765432',
                'Active'
            ],
            [
                'Jane Smith',
                'jane.smith@example.com',
                '082345678901',
                '2024-02-20',
                'Staff',
                'Bandung',
                'Konsultan',
                'Kontrak',
                '9876543210987654',
                '0009876543210',
                '0001234567890',
                '9876543210987',
                '9876543210',
                'Mandiri',
                'Belum Menikah',
                '',
                '0',
                '',
                'Active'
            ],
            [
                'Bob Johnson',
                'bob.johnson@example.com',
                '083456789012',
                '2024-03-10',
                'Supervisor',
                'Surabaya',
                'Organik',
                'Tetap',
                '5555666677778888',
                '0005555666677',
                '0008888777766',
                '5555666677778',
                '5555666677',
                'BNI',
                'Menikah',
                'Alice Johnson',
                '1',
                '083987654321',
                'Active'
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
            'jabatan',
            'lokasi_kerja',
            'jenis_karyawan',
            'status_pegawai',
            'npwp',
            'bpjs_kesehatan_no',
            'bpjs_kecelakaan_kerja_no',
            'bpjs_tk_no',
            'no_rekening',
            'bank',
            'status_perkawinan',
            'nama_istri',
            'jumlah_anak',
            'no_telp_istri',
            'status_karyawan'
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
            'E' => 15, // jabatan
            'F' => 15, // lokasi_kerja
            'G' => 15, // jenis_karyawan
            'H' => 15, // status_pegawai
            'I' => 20, // npwp
            'J' => 18, // bpjs_kesehatan_no
            'K' => 22, // bpjs_kecelakaan_kerja_no
            'L' => 18, // bpjs_tk_no
            'M' => 18, // no_rekening
            'N' => 12, // bank
            'O' => 18, // status_perkawinan
            'P' => 20, // nama_istri
            'Q' => 12, // jumlah_anak
            'R' => 15, // no_telp_istri
            'S' => 15, // status_karyawan
        ];
    }
}
