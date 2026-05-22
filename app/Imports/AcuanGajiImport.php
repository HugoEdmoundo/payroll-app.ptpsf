<?php

namespace App\Imports;

use App\Models\AcuanGaji;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AcuanGajiImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find karyawan by nama_karyawan
        $karyawan = Karyawan::where('nama_karyawan', $row['nama_karyawan'])->first();

        if (! $karyawan) {
            \Log::warning("Karyawan tidak ditemukan: {$row['nama_karyawan']}");

            return null; // Skip if employee not found
        }

        // Skip if karyawan is not Active
        if ($karyawan->status_karyawan !== 'Active') {
            return null; // Skip non-active/resign karyawan
        }

        // Check if already exists
        $exists = AcuanGaji::where('id_karyawan', $karyawan->id_karyawan)
            ->where('periode', $row['periode'])
            ->first();

        if ($exists) {
            \Log::warning("Data acuan gaji sudah ada untuk: {$row['nama_karyawan']} periode {$row['periode']}");
            // Update existing record
            $exists->update([
                'gaji_pokok' => $row['gaji_pokok'] ?? 0,
                'bpjs_kesehatan' => $row['bpjs_kesehatan'] ?? 0,
                'bpjs_kecelakaan_kerja' => $row['bpjs_kecelakaan_kerja'] ?? 0,
                'bpjs_kematian' => $row['bpjs_kematian'] ?? 0,
                'bpjs_jht' => $row['bpjs_jht'] ?? 0,
                'bpjs_jp' => $row['bpjs_jp'] ?? 0,
                'tunjangan_prestasi' => $row['tunjangan_prestasi'] ?? 0,
                'tunjangan_konjungtur' => $row['tunjangan_konjungtur'] ?? 0,
                'benefit_ibadah' => $row['benefit_ibadah'] ?? 0,
                'benefit_komunikasi' => $row['benefit_komunikasi'] ?? 0,
                'benefit_operasional' => $row['benefit_operasional'] ?? 0,
                'reward' => $row['reward'] ?? 0,
                'koperasi' => $row['koperasi'] ?? 0,
                'kasbon' => $row['kasbon'] ?? 0,
                'umroh' => $row['umroh'] ?? 0,
                'kurban' => $row['kurban'] ?? 0,
                'mutabaah' => $row['mutabaah'] ?? 0,
                'potongan_absensi' => $row['potongan_absensi'] ?? 0,
                'potongan_kehadiran' => $row['potongan_kehadiran'] ?? 0,
                'keterangan' => $row['keterangan'] ?? null,
            ]);

            return null;
        }

        // Create new record
        return new AcuanGaji([
            'id_karyawan' => $karyawan->id_karyawan,
            'periode' => $row['periode'],
            'gaji_pokok' => $row['gaji_pokok'] ?? 0,
            'bpjs_kesehatan' => $row['bpjs_kesehatan'] ?? 0,
            'bpjs_kecelakaan_kerja' => $row['bpjs_kecelakaan_kerja'] ?? 0,
            'bpjs_kematian' => $row['bpjs_kematian'] ?? 0,
            'bpjs_jht' => $row['bpjs_jht'] ?? 0,
            'bpjs_jp' => $row['bpjs_jp'] ?? 0,
            'tunjangan_prestasi' => $row['tunjangan_prestasi'] ?? 0,
            'tunjangan_konjungtur' => $row['tunjangan_konjungtur'] ?? 0,
            'benefit_ibadah' => $row['benefit_ibadah'] ?? 0,
            'benefit_komunikasi' => $row['benefit_komunikasi'] ?? 0,
            'benefit_operasional' => $row['benefit_operasional'] ?? 0,
            'reward' => $row['reward'] ?? 0,
            'koperasi' => $row['koperasi'] ?? 0,
            'kasbon' => $row['kasbon'] ?? 0,
            'umroh' => $row['umroh'] ?? 0,
            'kurban' => $row['kurban'] ?? 0,
            'mutabaah' => $row['mutabaah'] ?? 0,
            'potongan_absensi' => $row['potongan_absensi'] ?? 0,
            'potongan_kehadiran' => $row['potongan_kehadiran'] ?? 0,
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string|max:255',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'gaji_pokok' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
            'bpjs_kematian' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'tunjangan_prestasi' => 'nullable|numeric|min:0',
            'tunjangan_konjungtur' => 'nullable|numeric|min:0',
            'benefit_ibadah' => 'nullable|numeric|min:0',
            'benefit_komunikasi' => 'nullable|numeric|min:0',
            'benefit_operasional' => 'nullable|numeric|min:0',
            'reward' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
            'kasbon' => 'nullable|numeric|min:0',
            'umroh' => 'nullable|numeric|min:0',
            'kurban' => 'nullable|numeric|min:0',
            'mutabaah' => 'nullable|numeric|min:0',
            'potongan_absensi' => 'nullable|numeric|min:0',
            'potongan_kehadiran' => 'nullable|numeric|min:0',
        ];
    }
}
