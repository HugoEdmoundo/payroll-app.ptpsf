<?php

namespace App\Exports;

use App\Models\HitungGaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SlipGajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $hitungGajiId;
    protected $periode;
    protected $rowNumber = 0;

    public function __construct($hitungGajiId = null, $periode = null)
    {
        $this->hitungGajiId = $hitungGajiId;
        $this->periode = $periode;
    }

    public function collection()
    {
        if ($this->hitungGajiId) {
            // Single slip gaji
            return HitungGaji::with(['karyawan'])
                ->where('id', $this->hitungGajiId)
                ->get();
        } else {
            // All slip gaji for periode
            return HitungGaji::with(['karyawan'])
                ->where('periode', $this->periode)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function headings(): array
    {
        if ($this->hitungGajiId) {
            // Single slip format
            return [
                ['PT. PSF Pangestu Suryaning Family'],
                ['SLIP GAJI KARYAWAN'],
                [''],
                ['INFORMASI KARYAWAN'],
                ['Nama', 'Jabatan', 'Jenis Karyawan', 'Lokasi Kerja', 'No. Rekening', 'Bank'],
                [],
                ['RINCIAN GAJI'],
                ['PENDAPATAN', 'JUMLAH', '', 'PENGELUARAN', 'JUMLAH'],
            ];
        } else {
            // Multiple slip format (table)
            return [
                ['PT. PSF Pangestu Suryaning Family'],
                ['SLIP GAJI KARYAWAN - Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $this->periode)->format('F Y')],
                [''],
                [
                    'No',
                    'Nama Karyawan',
                    'Jabatan',
                    'Jenis Karyawan',
                    'Lokasi Kerja',
                    'Gaji Pokok',
                    'Tunjangan Prestasi',
                    'Benefit Operasional',
                    'Total Pendapatan',
                    'BPJS Kesehatan',
                    'BPJS JHT',
                    'BPJS JP',
                    'Kasbon',
                    'Potongan Absensi',
                    'Total Pengeluaran',
                    'Gaji Bersih'
                ],
            ];
        }
    }

    public function map($hitungGaji): array
    {
        $this->rowNumber++;
        
        if ($this->hitungGajiId) {
            // Single slip format (detailed)
            return $this->mapSingleSlip($hitungGaji);
        } else {
            // Multiple slip format (table row)
            return [
                $this->rowNumber,
                $hitungGaji->karyawan->nama_karyawan ?? '-',
                $hitungGaji->karyawan->jabatan ?? '-',
                $hitungGaji->karyawan->jenis_karyawan ?? '-',
                $hitungGaji->karyawan->lokasi_kerja ?? '-',
                $hitungGaji->gaji_pokok,
                $hitungGaji->tunjangan_prestasi,
                $hitungGaji->benefit_operasional,
                $hitungGaji->total_pendapatan,
                $hitungGaji->bpjs_kesehatan_pengeluaran,
                $hitungGaji->bpjs_jht_pengeluaran,
                $hitungGaji->bpjs_jp_pengeluaran,
                $hitungGaji->kasbon,
                $hitungGaji->potongan_absensi,
                $hitungGaji->total_pengeluaran,
                $hitungGaji->gaji_bersih,
            ];
        }
    }

    private function mapSingleSlip($hitungGaji): array
    {
        $rows = [];
        
        // Row 1: Info Karyawan
        $rows[] = [
            $hitungGaji->karyawan->nama_karyawan ?? '-',
            $hitungGaji->karyawan->jabatan ?? '-',
            $hitungGaji->karyawan->jenis_karyawan ?? '-',
            $hitungGaji->karyawan->lokasi_kerja ?? '-',
            $hitungGaji->karyawan->no_rekening ?? '-',
            $hitungGaji->karyawan->bank ?? '-',
        ];
        
        $rows[] = ['', '', '', '', '', ''];
        
        // Pendapatan & Pengeluaran rows
        $pendapatan = [
            ['Gaji Pokok', $hitungGaji->gaji_pokok],
            ['Tunjangan Prestasi', $hitungGaji->tunjangan_prestasi],
            ['Benefit Operasional', $hitungGaji->benefit_operasional],
            ['Tunjangan Konjungtur', $hitungGaji->tunjangan_konjungtur],
            ['Benefit Ibadah', $hitungGaji->benefit_ibadah],
            ['Benefit Komunikasi', $hitungGaji->benefit_komunikasi],
            ['Reward', $hitungGaji->reward],
        ];
        
        $pengeluaran = [
            ['BPJS Kesehatan', $hitungGaji->bpjs_kesehatan_pengeluaran],
            ['BPJS JHT', $hitungGaji->bpjs_jht_pengeluaran],
            ['BPJS JP', $hitungGaji->bpjs_jp_pengeluaran],
            ['Koperasi', $hitungGaji->koperasi],
            ['Kasbon', $hitungGaji->kasbon],
            ['Potongan Absensi', $hitungGaji->potongan_absensi],
            ['Potongan Kehadiran', $hitungGaji->potongan_kehadiran],
        ];
        
        for ($i = 0; $i < max(count($pendapatan), count($pengeluaran)); $i++) {
            $rows[] = [
                $pendapatan[$i][0] ?? '',
                isset($pendapatan[$i][1]) ? 'Rp ' . number_format($pendapatan[$i][1], 0, ',', '.') : '',
                '',
                $pengeluaran[$i][0] ?? '',
                isset($pengeluaran[$i][1]) ? 'Rp ' . number_format($pengeluaran[$i][1], 0, ',', '.') : '',
            ];
        }
        
        // Total rows
        $rows[] = [
            'Total Pendapatan',
            'Rp ' . number_format($hitungGaji->total_pendapatan, 0, ',', '.'),
            '',
            'Total Pengeluaran',
            'Rp ' . number_format($hitungGaji->total_pengeluaran, 0, ',', '.'),
        ];
        
        $rows[] = ['', '', '', '', '', ''];
        
        // Gaji Bersih
        $rows[] = [
            'GAJI BERSIH',
            'Rp ' . number_format($hitungGaji->gaji_bersih, 0, ',', '.'),
            '',
            '',
            '',
        ];
        
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        if ($this->hitungGajiId) {
            return $this->stylesSingleSlip($sheet);
        } else {
            return $this->stylesMultipleSlip($sheet);
        }
    }

    private function stylesSingleSlip(Worksheet $sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        
        // Header company
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Title
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Section titles
        $sheet->mergeCells('A4:F4');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB']
            ],
        ]);
        
        $sheet->mergeCells('A7:F7');
        $sheet->getStyle('A7')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB']
            ],
        ]);
        
        // Headings row
        $sheet->getStyle('A5:F5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1D5DB']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        $sheet->getStyle('A8:E8')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1D5DB']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        return [];
    }

    private function stylesMultipleSlip(Worksheet $sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(18);
        $sheet->getColumnDimension('O')->setWidth(18);
        $sheet->getColumnDimension('P')->setWidth(18);
        
        // Header company
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Title
        $sheet->mergeCells('A2:P2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Headings row
        $sheet->getStyle('A4:P4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1D5DB']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Format currency columns
        $lastRow = $sheet->getHighestRow();
        for ($row = 5; $row <= $lastRow; $row++) {
            // Gaji Pokok to Gaji Bersih (F to P)
            for ($col = 'F'; $col <= 'P'; $col++) {
                $sheet->getStyle($col . $row)->getNumberFormat()
                    ->setFormatCode('#,##0');
            }
        }
        
        return [];
    }

    public function title(): string
    {
        return 'Slip Gaji';
    }
}
