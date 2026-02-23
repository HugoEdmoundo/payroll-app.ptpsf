<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcuanGajiTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Return empty array for template
        return [
            ['KARYAWAN001', '2026-02', 5000000, 1000000, 500000, 200000, 150000, 100000, 0, 50000, 25000, 15000, 100000, 50000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 'Contoh data'],
        ];
    }

    public function headings(): array
    {
        return [
            'nik',
            'periode',
            'gaji_pokok',
            'tunjangan_prestasi',
            'tunjangan_konjungtur',
            'benefit_ibadah',
            'benefit_komunikasi',
            'benefit_operasional',
            'reward',
            'bpjs_kesehatan',
            'bpjs_kecelakaan_kerja',
            'bpjs_kematian',
            'bpjs_jht',
            'bpjs_jp',
            'potongan_bpjs_kesehatan',
            'potongan_bpjs_kecelakaan',
            'potongan_bpjs_kematian',
            'potongan_bpjs_jht',
            'potongan_bpjs_jp',
            'potongan_koperasi',
            'potongan_tabungan_koperasi',
            'potongan_kasbon',
            'potongan_umroh',
            'potongan_kurban',
            'potongan_mutabaah',
            'potongan_absensi',
            'potongan_kehadiran',
            'catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
