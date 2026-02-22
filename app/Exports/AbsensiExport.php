<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = Absensi::with('karyawan');
        
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        
        return $query->orderBy('periode', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Absensi',
            'Nama Karyawan',
            'Periode',
            'Jumlah Hari',
            'Hadir',
            'On Site',
            'Absence',
            'Idle/Rest',
            'Izin/Sakit/Cuti',
            'Tanpa Keterangan',
            'Keterangan',
            'Tanggal Dibuat',
        ];
    }

    public function map($absensi): array
    {
        return [
            'ABS' . str_pad($absensi->id_absensi, 4, '0', STR_PAD_LEFT),
            $absensi->karyawan->nama_karyawan ?? '-',
            $absensi->periode,
            $absensi->jumlah_hari_bulan,
            $absensi->hadir,
            $absensi->on_site,
            $absensi->absence,
            $absensi->idle_rest,
            $absensi->izin_sakit_cuti,
            $absensi->tanpa_keterangan,
            $absensi->keterangan ?? '-',
            $absensi->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
