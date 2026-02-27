<?php

namespace App\Exports;

use App\Models\PengaturanBpjsKoperasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaturanBpjsKoperasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $statusPegawai;

    public function __construct($statusPegawai = null)
    {
        $this->statusPegawai = $statusPegawai;
    }

    public function collection()
    {
        $query = PengaturanBpjsKoperasi::query();
        
        if ($this->statusPegawai) {
            $query->where('status_pegawai', $this->statusPegawai);
        }
        
        return $query->orderBy('status_pegawai')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Status Pegawai',
            'BPJS Kesehatan',
            'BPJS Kecelakaan Kerja',
            'BPJS Kematian',
            'BPJS JHT',
            'BPJS JP',
            'Total BPJS',
            'Koperasi',
            'Created At',
            'Updated At',
        ];
    }

    public function map($pengaturan): array
    {
        return [
            $pengaturan->id,
            $pengaturan->status_pegawai,
            $pengaturan->bpjs_kesehatan,
            $pengaturan->bpjs_kecelakaan_kerja,
            $pengaturan->bpjs_kematian,
            $pengaturan->bpjs_jht,
            $pengaturan->bpjs_jp,
            $pengaturan->total_bpjs,
            $pengaturan->koperasi,
            $pengaturan->created_at->format('Y-m-d H:i:s'),
            $pengaturan->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
