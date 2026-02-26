<?php

namespace App\Exports;

use App\Models\AcuanGaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcuanGajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    public function __construct($periode = null)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $query = AcuanGaji::with('karyawan');
        
        if ($this->periode) {
            $query->where('periode', $this->periode);
        }
        
        return $query->orderBy('periode', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'nama_karyawan',
            'periode',
            'gaji_pokok',
            'bpjs_kesehatan_pendapatan',
            'bpjs_kecelakaan_kerja_pendapatan',
            'bpjs_kematian_pendapatan',
            'bpjs_jht_pendapatan',
            'bpjs_jp_pendapatan',
            'tunjangan_prestasi',
            'tunjangan_konjungtur',
            'benefit_ibadah',
            'benefit_komunikasi',
            'benefit_operasional',
            'reward',
            'total_pendapatan',
            'bpjs_kesehatan_pengeluaran',
            'bpjs_kecelakaan_kerja_pengeluaran',
            'bpjs_kematian_pengeluaran',
            'bpjs_jht_pengeluaran',
            'bpjs_jp_pengeluaran',
            'koperasi',
            'kasbon',
            'umroh',
            'kurban',
            'mutabaah',
            'potongan_absensi',
            'potongan_kehadiran',
            'total_pengeluaran',
            'gaji_bersih',
            'keterangan',
        ];
    }

    public function map($acuan): array
    {
        return [
            $acuan->karyawan->nama_karyawan ?? '-',
            $acuan->periode,
            $acuan->gaji_pokok,
            $acuan->bpjs_kesehatan_pendapatan,
            $acuan->bpjs_kecelakaan_kerja_pendapatan,
            $acuan->bpjs_kematian_pendapatan,
            $acuan->bpjs_jht_pendapatan,
            $acuan->bpjs_jp_pendapatan,
            $acuan->tunjangan_prestasi,
            $acuan->tunjangan_konjungtur,
            $acuan->benefit_ibadah,
            $acuan->benefit_komunikasi,
            $acuan->benefit_operasional,
            $acuan->reward,
            $acuan->total_pendapatan,
            $acuan->bpjs_kesehatan_pengeluaran,
            $acuan->bpjs_kecelakaan_kerja_pengeluaran,
            $acuan->bpjs_kematian_pengeluaran,
            $acuan->bpjs_jht_pengeluaran,
            $acuan->bpjs_jp_pengeluaran,
            $acuan->koperasi,
            $acuan->kasbon,
            $acuan->umroh,
            $acuan->kurban,
            $acuan->mutabaah,
            $acuan->potongan_absensi,
            $acuan->potongan_kehadiran,
            $acuan->total_pengeluaran,
            $acuan->gaji_bersih,
            $acuan->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
