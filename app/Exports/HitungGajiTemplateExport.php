<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HitungGajiTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Return example data for template
        return [
            [
                'KARYAWAN001', // NIK
                '2026-02', // Periode
                // Adjustments for each field (optional)
                '', '', '', // gaji_pokok: type, nominal, description
                '', '', '', // bpjs_kesehatan_pendapatan
                '', '', '', // bpjs_kecelakaan_kerja_pendapatan
                '', '', '', // bpjs_kematian_pendapatan
                '', '', '', // bpjs_jht_pendapatan
                '', '', '', // bpjs_jp_pendapatan
                '', '', '', // tunjangan_prestasi
                '', '', '', // tunjangan_konjungtur
                '', '', '', // benefit_ibadah
                '', '', '', // benefit_komunikasi
                '', '', '', // benefit_operasional
                '', '', '', // reward
                '', '', '', // bpjs_kesehatan_pengeluaran
                '', '', '', // bpjs_kecelakaan_kerja_pengeluaran
                '', '', '', // bpjs_kematian_pengeluaran
                '', '', '', // bpjs_jht_pengeluaran
                '', '', '', // bpjs_jp_pengeluaran
                '', '', '', // tabungan_koperasi
                '', '', '', // koperasi
                '', '', '', // kasbon
                '', '', '', // umroh
                '', '', '', // kurban
                '', '', '', // mutabaah
                '', '', '', // potongan_absensi
                '', '', '', // potongan_kehadiran
                'Optional notes' // keterangan
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Periode (YYYY-MM)',
            // Pendapatan adjustments
            'Gaji Pokok - Type (+/-)', 'Gaji Pokok - Nominal', 'Gaji Pokok - Description',
            'BPJS Kesehatan (P) - Type', 'BPJS Kesehatan (P) - Nominal', 'BPJS Kesehatan (P) - Description',
            'BPJS Kecelakaan Kerja (P) - Type', 'BPJS Kecelakaan Kerja (P) - Nominal', 'BPJS Kecelakaan Kerja (P) - Description',
            'BPJS Kematian (P) - Type', 'BPJS Kematian (P) - Nominal', 'BPJS Kematian (P) - Description',
            'BPJS JHT (P) - Type', 'BPJS JHT (P) - Nominal', 'BPJS JHT (P) - Description',
            'BPJS JP (P) - Type', 'BPJS JP (P) - Nominal', 'BPJS JP (P) - Description',
            'Tunjangan Prestasi - Type', 'Tunjangan Prestasi - Nominal', 'Tunjangan Prestasi - Description',
            'Tunjangan Konjungtur - Type', 'Tunjangan Konjungtur - Nominal', 'Tunjangan Konjungtur - Description',
            'Benefit Ibadah - Type', 'Benefit Ibadah - Nominal', 'Benefit Ibadah - Description',
            'Benefit Komunikasi - Type', 'Benefit Komunikasi - Nominal', 'Benefit Komunikasi - Description',
            'Benefit Operasional - Type', 'Benefit Operasional - Nominal', 'Benefit Operasional - Description',
            'Reward - Type', 'Reward - Nominal', 'Reward - Description',
            // Pengeluaran adjustments
            'BPJS Kesehatan (D) - Type', 'BPJS Kesehatan (D) - Nominal', 'BPJS Kesehatan (D) - Description',
            'BPJS Kecelakaan Kerja (D) - Type', 'BPJS Kecelakaan Kerja (D) - Nominal', 'BPJS Kecelakaan Kerja (D) - Description',
            'BPJS Kematian (D) - Type', 'BPJS Kematian (D) - Nominal', 'BPJS Kematian (D) - Description',
            'BPJS JHT (D) - Type', 'BPJS JHT (D) - Nominal', 'BPJS JHT (D) - Description',
            'BPJS JP (D) - Type', 'BPJS JP (D) - Nominal', 'BPJS JP (D) - Description',
            'Tabungan Koperasi - Type', 'Tabungan Koperasi - Nominal', 'Tabungan Koperasi - Description',
            'Koperasi - Type', 'Koperasi - Nominal', 'Koperasi - Description',
            'Kasbon - Type', 'Kasbon - Nominal', 'Kasbon - Description',
            'Umroh - Type', 'Umroh - Nominal', 'Umroh - Description',
            'Kurban - Type', 'Kurban - Nominal', 'Kurban - Description',
            'Mutabaah - Type', 'Mutabaah - Nominal', 'Mutabaah - Description',
            'Potongan Absensi - Type', 'Potongan Absensi - Nominal', 'Potongan Absensi - Description',
            'Potongan Kehadiran - Type', 'Potongan Kehadiran - Nominal', 'Potongan Kehadiran - Description',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
