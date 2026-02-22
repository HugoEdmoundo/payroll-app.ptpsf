<?php

namespace App\Exports;

use App\Models\Kasbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KasbonExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = Kasbon::with('karyawan');
        
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        
        return $query->orderBy('tanggal_pengajuan', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Kasbon',
            'Nama Karyawan',
            'Periode',
            'Tanggal Pengajuan',
            'Deskripsi',
            'Nominal',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Jumlah Cicilan',
            'Cicilan Terbayar',
            'Sisa Cicilan',
            'Keterangan',
            'Tanggal Dibuat',
        ];
    }

    public function map($kasbon): array
    {
        return [
            'KSB' . str_pad($kasbon->id_kasbon, 4, '0', STR_PAD_LEFT),
            $kasbon->karyawan->nama_karyawan ?? '-',
            $kasbon->periode,
            $kasbon->tanggal_pengajuan->format('d/m/Y'),
            $kasbon->deskripsi,
            $kasbon->nominal,
            $kasbon->metode_pembayaran,
            $kasbon->status_pembayaran,
            $kasbon->jumlah_cicilan ?? '-',
            $kasbon->cicilan_terbayar,
            $kasbon->sisa_cicilan,
            $kasbon->keterangan ?? '-',
            $kasbon->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
