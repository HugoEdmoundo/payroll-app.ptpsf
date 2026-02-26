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
                    ->orderBy('jabatan')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'Status Pegawai',
            'Jabatan',
            'Lokasi Kerja',
            'Gaji Pokok',
            'BPJS Kesehatan',
            'BPJS Kecelakaan Kerja',
            'BPJS Ketenagakerjaan',
            'Tunjangan Operasional',
            'Potongan Koperasi',
            'Keterangan',
        ];
    }

    public function map($pengaturan): array
    {
        return [
            $pengaturan->status_pegawai,
            $pengaturan->jabatan,
            $pengaturan->lokasi_kerja,
            $pengaturan->gaji_pokok,
            $pengaturan->bpjs_kesehatan,
            $pengaturan->bpjs_kecelakaan_kerja,
            $pengaturan->bpjs_ketenagakerjaan,
            $pengaturan->tunjangan_operasional,
            $pengaturan->potongan_koperasi,
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
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 18,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 20,
            'J' => 30,
        ];
    }
}
