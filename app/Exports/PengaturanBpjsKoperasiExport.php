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
    protected $jenisKaryawan;
    protected $statusPegawai;

    public function __construct($jenisKaryawan = null, $statusPegawai = null)
    {
        $this->jenisKaryawan = $jenisKaryawan;
        $this->statusPegawai = $statusPegawai;
    }

    public function collection()
    {
        $query = PengaturanBpjsKoperasi::query();
        
        if ($this->jenisKaryawan) {
            $query->where('jenis_karyawan', $this->jenisKaryawan);
        }
        
        if ($this->statusPegawai) {
            $query->where('status_pegawai', $this->statusPegawai);
        }
        
        return $query->orderBy('jenis_karyawan')
                     ->orderBy('status_pegawai')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Jenis Karyawan',
            'Status Pegawai',
            'BPJS Kesehatan (Pendapatan)',
            'BPJS Kecelakaan Kerja (Pendapatan)',
            'BPJS Kematian (Pendapatan)',
            'BPJS JHT (Pendapatan)',
            'BPJS JP (Pendapatan)',
            'Total BPJS Pendapatan',
            'BPJS Kesehatan (Pengeluaran)',
            'BPJS Kecelakaan Kerja (Pengeluaran)',
            'BPJS Kematian (Pengeluaran)',
            'BPJS JHT (Pengeluaran)',
            'BPJS JP (Pengeluaran)',
            'Total BPJS Pengeluaran',
            'Koperasi',
            'Created At',
            'Updated At',
        ];
    }

    public function map($pengaturan): array
    {
        return [
            $pengaturan->id,
            $pengaturan->jenis_karyawan,
            $pengaturan->status_pegawai,
            $pengaturan->bpjs_kesehatan_pendapatan,
            $pengaturan->bpjs_kecelakaan_kerja_pendapatan,
            $pengaturan->bpjs_kematian_pendapatan,
            $pengaturan->bpjs_jht_pendapatan,
            $pengaturan->bpjs_jp_pendapatan,
            $pengaturan->total_bpjs_pendapatan,
            $pengaturan->bpjs_kesehatan_pengeluaran,
            $pengaturan->bpjs_kecelakaan_kerja_pengeluaran,
            $pengaturan->bpjs_kematian_pengeluaran,
            $pengaturan->bpjs_jht_pengeluaran,
            $pengaturan->bpjs_jp_pengeluaran,
            $pengaturan->total_bpjs_pengeluaran,
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
