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
        // Return example data for template
        return [
            [
                'John Doe', // nama_karyawan
                '2026-02', // periode
                5000000, // gaji_pokok
                100000, // bpjs_kesehatan_pendapatan
                50000, // bpjs_kecelakaan_kerja_pendapatan
                25000, // bpjs_kematian_pendapatan
                150000, // bpjs_jht_pendapatan
                75000, // bpjs_jp_pendapatan
                1000000, // tunjangan_prestasi
                500000, // tunjangan_konjungtur
                200000, // benefit_ibadah
                150000, // benefit_komunikasi
                300000, // benefit_operasional
                0, // reward
                100000, // koperasi
                0, // kasbon
                0, // umroh
                0, // kurban
                0, // mutabaah
                0, // potongan_absensi
                0, // potongan_kehadiran
                'Contoh data', // keterangan
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_karyawan',
            'periode',
            'gaji_pokok',
            'bpjs_kesehatan_pendapatan',
            'bpjs_kecelakaan_kerja_pendapatan',
            'bpjs_kematian_pendapatan',
            'bpjs_jht_pendapatan',
            'bpjs_jp_pendapatan',
            'tunjangan_prestasi',
            'tunjangan_konjungtur',
            'benefit_ibadah',
            'benefit_komunikasi',
            'benefit_operasional',
            'reward',
            'koperasi',
            'kasbon',
            'umroh',
            'kurban',
            'mutabaah',
            'potongan_absensi',
            'potongan_kehadiran',
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
