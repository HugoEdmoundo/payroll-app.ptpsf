<?php

namespace App\Exports;

use App\Models\PengaturanGajiStatusPegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaturanGajiStatusPegawaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $statusPegawai;

    public function __construct($statusPegawai = null)
    {
        $this->statusPegawai = $statusPegawai;
    }

    public function collection()
    {
        $query = PengaturanGajiStatusPegawai::query();
        
        if ($this->statusPegawai) {
            $query->where('status_pegawai', $this->statusPegawai);
        }
        
        return $query->orderBy('status_pegawai')
                    ->orderBy('lokasi_kerja')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'Status Pegawai',
            'Lokasi Kerja',
            'Gaji Pokok',
            'Keterangan',
        ];
    }

    public function map($pengaturan): array
    {
        return [
            $pengaturan->status_pegawai,
            $pengaturan->lokasi_kerja,
            $pengaturan->gaji_pokok,
            $pengaturan->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E2E8F0']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 15,
            'D' => 30,
        ];
    }
}
