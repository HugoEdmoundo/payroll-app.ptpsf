<?php

namespace App\Imports;

use App\Models\HitungGaji;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HitungGajiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find karyawan by NIK
        $karyawan = Karyawan::where('nik', $row['nik'])->first();
        
        if (!$karyawan) {
            return null; // Skip if employee not found
        }

        // Find existing hitung gaji
        $hitungGaji = HitungGaji::where('karyawan_id', $karyawan->id_karyawan)
                               ->where('periode', $row['periode_yyyy_mm'])
                               ->first();
        
        if (!$hitungGaji) {
            return null; // Skip if hitung gaji not found
        }

        // Only update if status is draft
        if ($hitungGaji->status !== 'draft') {
            return null;
        }

        // Build adjustments array from row data
        $adjustments = [];
        $fields = [
            'gaji_pokok', 'bpjs_kesehatan_pendapatan', 'bpjs_kecelakaan_kerja_pendapatan',
            'bpjs_kematian_pendapatan', 'bpjs_jht_pendapatan', 'bpjs_jp_pendapatan',
            'tunjangan_prestasi', 'tunjangan_konjungtur', 'benefit_ibadah',
            'benefit_komunikasi', 'benefit_operasional', 'reward',
            'bpjs_kesehatan_pengeluaran', 'bpjs_kecelakaan_kerja_pengeluaran',
            'bpjs_kematian_pengeluaran', 'bpjs_jht_pengeluaran', 'bpjs_jp_pengeluaran',
            'tabungan_koperasi', 'koperasi', 'kasbon', 'umroh', 'kurban',
            'mutabaah', 'potongan_absensi', 'potongan_kehadiran'
        ];

        foreach ($fields as $field) {
            $typeKey = str_replace('_', '_', $field) . '_type';
            $nominalKey = str_replace('_', '_', $field) . '_nominal';
            $descKey = str_replace('_', '_', $field) . '_description';

            if (!empty($row[$nominalKey]) && !empty($row[$descKey])) {
                $adjustments[$field] = [
                    'type' => $row[$typeKey] ?? '+',
                    'nominal' => (float) $row[$nominalKey],
                    'description' => $row[$descKey]
                ];
            }
        }

        // Update hitung gaji with adjustments
        $hitungGaji->update([
            'adjustments' => $adjustments,
            'keterangan' => $row['keterangan'] ?? $hitungGaji->keterangan,
        ]);

        return null; // Return null because we're updating, not creating
    }

    public function rules(): array
    {
        return [
            'nik' => 'required',
            'periode_yyyy_mm' => 'required',
        ];
    }
}
