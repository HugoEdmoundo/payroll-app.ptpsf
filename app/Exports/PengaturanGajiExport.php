<?php

namespace App\Exports;

use App\Models\PengaturanGaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaturanGajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $jenisKaryawan;

    public function __construct($jenisKaryawan = null)
    {
        $this->jenisKaryawan = $jenisKaryawan;
    }

    public function collection()
    {
        $query = PengaturanGaji::query();
        
        if ($this->jenisKaryawan) {
            $query->where('jenis_karyawan', $this->jenisKaryawan);
        }
        
        return $query->orderBy('jenis_karyawan')
                    ->orderBy('jabatan')
                    ->orderBy('lokasi_kerja')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Jenis Karyawan',
            'Jabatan',
            'Lokasi Kerja',
            'Gaji Pokok',
            'Tunjangan Operasional',
            'Potongan Koperasi',
            'BPJS Kesehatan',
            'BPJS Ketenagakerjaan',
            'BPJS Kecelakaan Kerja',
            'BPJS Total',
            'Gaji Nett',
            'Total Gaji',
            'Keterangan',
            'Tanggal Dibuat',
            'Tanggal Update',
        ];
    }

    public function map($pengaturan): array
    {
        return [
            $pengaturan->id_pengaturan,
            $pengaturan->jenis_karyawan,
            $pengaturan->jabatan,
            $pengaturan->lokasi_kerja,
            $pengaturan->gaji_pokok,
            $pengaturan->tunjangan_operasional,
            $pengaturan->potongan_koperasi,
            $pengaturan->bpjs_kesehatan,
            $pengaturan->bpjs_ketenagakerjaan,
            $pengaturan->bpjs_kecelakaan_kerja,
            $pengaturan->bpjs_total,
            $pengaturan->gaji_nett,
            $pengaturan->total_gaji,
            $pengaturan->keterangan ?? '-',
            $pengaturan->created_at->format('d/m/Y H:i'),
            $pengaturan->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
