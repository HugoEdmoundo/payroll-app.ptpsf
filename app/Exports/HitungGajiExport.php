<?php

namespace App\Exports;

use App\Models\HitungGaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HitungGajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = HitungGaji::with('karyawan');
        
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        
        return $query->orderBy('periode', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Periode',
            'Status',
            // Pendapatan
            'Gaji Pokok',
            'BPJS Kesehatan (P)',
            'BPJS Kecelakaan Kerja (P)',
            'BPJS Kematian (P)',
            'BPJS JHT (P)',
            'BPJS JP (P)',
            'Tunjangan Prestasi',
            'Tunjangan Konjungtur',
            'Benefit Ibadah',
            'Benefit Komunikasi',
            'Benefit Operasional',
            'Reward',
            // Pengeluaran
            'BPJS Kesehatan (D)',
            'BPJS Kecelakaan Kerja (D)',
            'BPJS Kematian (D)',
            'BPJS JHT (D)',
            'BPJS JP (D)',
            'Tabungan Koperasi',
            'Koperasi',
            'Kasbon',
            'Umroh',
            'Kurban',
            'Mutabaah',
            'Potongan Absensi',
            'Potongan Kehadiran',
            // Totals
            'Total Pendapatan',
            'Total Pengeluaran',
            'Gaji Bersih',
            'Keterangan',
        ];
    }

    public function map($hitung): array
    {
        return [
            $hitung->karyawan->nama_karyawan ?? '-',
            $hitung->periode,
            ucfirst($hitung->status),
            // Pendapatan - with adjustments
            $hitung->getFinalValue('gaji_pokok'),
            $hitung->getFinalValue('bpjs_kesehatan_pendapatan'),
            $hitung->getFinalValue('bpjs_kecelakaan_kerja_pendapatan'),
            $hitung->getFinalValue('bpjs_kematian_pendapatan'),
            $hitung->getFinalValue('bpjs_jht_pendapatan'),
            $hitung->getFinalValue('bpjs_jp_pendapatan'),
            $hitung->getFinalValue('tunjangan_prestasi'),
            $hitung->getFinalValue('tunjangan_konjungtur'),
            $hitung->getFinalValue('benefit_ibadah'),
            $hitung->getFinalValue('benefit_komunikasi'),
            $hitung->getFinalValue('benefit_operasional'),
            $hitung->getFinalValue('reward'),
            // Pengeluaran - with adjustments
            $hitung->getFinalValue('bpjs_kesehatan_pengeluaran'),
            $hitung->getFinalValue('bpjs_kecelakaan_kerja_pengeluaran'),
            $hitung->getFinalValue('bpjs_kematian_pengeluaran'),
            $hitung->getFinalValue('bpjs_jht_pengeluaran'),
            $hitung->getFinalValue('bpjs_jp_pengeluaran'),
            $hitung->getFinalValue('tabungan_koperasi'),
            $hitung->getFinalValue('koperasi'),
            $hitung->getFinalValue('kasbon'),
            $hitung->getFinalValue('umroh'),
            $hitung->getFinalValue('kurban'),
            $hitung->getFinalValue('mutabaah'),
            $hitung->getFinalValue('potongan_absensi'),
            $hitung->getFinalValue('potongan_kehadiran'),
            // Totals
            $hitung->total_pendapatan,
            $hitung->total_pengeluaran,
            $hitung->gaji_bersih,
            $hitung->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
