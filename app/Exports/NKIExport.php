<?php

namespace App\Exports;

use App\Models\NKI;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NKIExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = NKI::with('karyawan');
        
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        
        return $query->orderBy('periode', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID NKI',
            'Nama Karyawan',
            'Periode',
            'Kemampuan (20%)',
            'Kontribusi 1 (20%)',
            'Kontribusi 2 (40%)',
            'Kedisiplinan (20%)',
            'Nilai NKI',
            'Persentase Tunjangan (%)',
            'Keterangan',
            'Tanggal Dibuat',
        ];
    }

    public function map($nki): array
    {
        return [
            'NKI' . str_pad($nki->id_nki, 4, '0', STR_PAD_LEFT),
            $nki->karyawan->nama_karyawan ?? '-',
            $nki->periode,
            $nki->kemampuan,
            $nki->kontribusi_1,
            $nki->kontribusi_2,
            $nki->kedisiplinan,
            $nki->nilai_nki,
            $nki->persentase_tunjangan,
            $nki->keterangan ?? '-',
            $nki->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
