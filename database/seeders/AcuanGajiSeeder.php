<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcuanGaji;
use App\Models\Karyawan;

class AcuanGajiSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanList = Karyawan::all();
        $periode = now()->format('Y-m');

        foreach ($karyawanList as $karyawan) {
            // Generate acuan gaji based on jabatan
            $gajiData = $this->getGajiByJabatan($karyawan->jabatan, $karyawan->jenis_karyawan);
            
            AcuanGaji::firstOrCreate(
                [
                    'id_karyawan' => $karyawan->id_karyawan,
                    'periode' => $periode,
                ],
                array_merge($gajiData, [
                    'keterangan' => 'Acuan gaji periode ' . $periode,
                ])
            );
        }
    }

    private function getGajiByJabatan($jabatan, $jenisKaryawan)
    {
        $gajiMap = [
            'Manager' => [
                'gaji_pokok' => 15000000,
                'bpjs_kesehatan_pendapatan' => 750000,
                'bpjs_kecelakaan_kerja_pendapatan' => 150000,
                'bpjs_kematian_pendapatan' => 75000,
                'bpjs_jht_pendapatan' => 500000,
                'bpjs_jp_pendapatan' => 250000,
                'tunjangan_prestasi' => 3000000,
                'tunjangan_konjungtur' => 1000000,
                'benefit_ibadah' => 500000,
                'benefit_komunikasi' => 300000,
                'benefit_operasional' => 500000,
                'reward' => 0,
                'bpjs_kesehatan_pengeluaran' => 100000,
                'bpjs_kecelakaan_kerja_pengeluaran' => 30000,
                'bpjs_kematian_pengeluaran' => 15000,
                'bpjs_jht_pengeluaran' => 300000,
                'bpjs_jp_pengeluaran' => 150000,
                'tabungan_koperasi' => 200000,
                'koperasi' => 100000,
                'kasbon' => 0,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_absensi' => 0,
                'potongan_kehadiran' => 0,
            ],
            'Supervisor' => [
                'gaji_pokok' => 10000000,
                'bpjs_kesehatan_pendapatan' => 500000,
                'bpjs_kecelakaan_kerja_pendapatan' => 100000,
                'bpjs_kematian_pendapatan' => 50000,
                'bpjs_jht_pendapatan' => 350000,
                'bpjs_jp_pendapatan' => 175000,
                'tunjangan_prestasi' => 2000000,
                'tunjangan_konjungtur' => 750000,
                'benefit_ibadah' => 400000,
                'benefit_komunikasi' => 250000,
                'benefit_operasional' => 400000,
                'reward' => 0,
                'bpjs_kesehatan_pengeluaran' => 75000,
                'bpjs_kecelakaan_kerja_pengeluaran' => 20000,
                'bpjs_kematian_pengeluaran' => 10000,
                'bpjs_jht_pengeluaran' => 200000,
                'bpjs_jp_pengeluaran' => 100000,
                'tabungan_koperasi' => 150000,
                'koperasi' => 100000,
                'kasbon' => 0,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_absensi' => 0,
                'potongan_kehadiran' => 0,
            ],
            'Staff' => [
                'gaji_pokok' => $jenisKaryawan === 'Kontrak' ? 6000000 : 7000000,
                'bpjs_kesehatan_pendapatan' => $jenisKaryawan === 'Kontrak' ? 300000 : 350000,
                'bpjs_kecelakaan_kerja_pendapatan' => $jenisKaryawan === 'Kontrak' ? 60000 : 70000,
                'bpjs_kematian_pendapatan' => $jenisKaryawan === 'Kontrak' ? 30000 : 35000,
                'bpjs_jht_pendapatan' => $jenisKaryawan === 'Kontrak' ? 200000 : 250000,
                'bpjs_jp_pendapatan' => $jenisKaryawan === 'Kontrak' ? 100000 : 125000,
                'tunjangan_prestasi' => $jenisKaryawan === 'Kontrak' ? 1000000 : 1500000,
                'tunjangan_konjungtur' => $jenisKaryawan === 'Kontrak' ? 400000 : 500000,
                'benefit_ibadah' => $jenisKaryawan === 'Kontrak' ? 250000 : 300000,
                'benefit_komunikasi' => $jenisKaryawan === 'Kontrak' ? 150000 : 200000,
                'benefit_operasional' => $jenisKaryawan === 'Kontrak' ? 250000 : 300000,
                'reward' => 0,
                'bpjs_kesehatan_pengeluaran' => $jenisKaryawan === 'Kontrak' ? 50000 : 60000,
                'bpjs_kecelakaan_kerja_pengeluaran' => $jenisKaryawan === 'Kontrak' ? 15000 : 18000,
                'bpjs_kematian_pengeluaran' => $jenisKaryawan === 'Kontrak' ? 8000 : 10000,
                'bpjs_jht_pengeluaran' => $jenisKaryawan === 'Kontrak' ? 120000 : 150000,
                'bpjs_jp_pengeluaran' => $jenisKaryawan === 'Kontrak' ? 60000 : 75000,
                'tabungan_koperasi' => $jenisKaryawan === 'Kontrak' ? 75000 : 100000,
                'koperasi' => $jenisKaryawan === 'Kontrak' ? 50000 : 100000,
                'kasbon' => 0,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_absensi' => 0,
                'potongan_kehadiran' => 0,
            ],
        ];

        $data = $gajiMap[$jabatan] ?? $gajiMap['Staff'];
        
        // Calculate totals
        $data['total_pendapatan'] = 
            $data['gaji_pokok'] +
            $data['bpjs_kesehatan_pendapatan'] +
            $data['bpjs_kecelakaan_kerja_pendapatan'] +
            $data['bpjs_kematian_pendapatan'] +
            $data['bpjs_jht_pendapatan'] +
            $data['bpjs_jp_pendapatan'] +
            $data['tunjangan_prestasi'] +
            $data['tunjangan_konjungtur'] +
            $data['benefit_ibadah'] +
            $data['benefit_komunikasi'] +
            $data['benefit_operasional'] +
            $data['reward'];

        $data['total_pengeluaran'] = 
            $data['bpjs_kesehatan_pengeluaran'] +
            $data['bpjs_kecelakaan_kerja_pengeluaran'] +
            $data['bpjs_kematian_pengeluaran'] +
            $data['bpjs_jht_pengeluaran'] +
            $data['bpjs_jp_pengeluaran'] +
            $data['tabungan_koperasi'] +
            $data['koperasi'] +
            $data['kasbon'] +
            $data['umroh'] +
            $data['kurban'] +
            $data['mutabaah'] +
            $data['potongan_absensi'] +
            $data['potongan_kehadiran'];

        $data['gaji_bersih'] = $data['total_pendapatan'] - $data['total_pengeluaran'];

        return $data;
    }
}
