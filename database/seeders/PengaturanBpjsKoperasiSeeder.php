<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanBpjsKoperasi;

class PengaturanBpjsKoperasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'status_pegawai' => 'Kontrak',
                'bpjs_kesehatan' => 50000,
                'bpjs_kecelakaan_kerja' => 25000,
                'bpjs_kematian' => 15000,
                'bpjs_jht' => 100000,
                'bpjs_jp' => 50000,
                'koperasi' => 50000,
            ],
            [
                'status_pegawai' => 'OJT',
                'bpjs_kesehatan' => 0, // OJT tidak dapat BPJS
                'bpjs_kecelakaan_kerja' => 0,
                'bpjs_kematian' => 0,
                'bpjs_jht' => 0,
                'bpjs_jp' => 0,
                'koperasi' => 30000, // OJT dapat koperasi
            ],
        ];

        foreach ($data as $item) {
            PengaturanBpjsKoperasi::updateOrCreate(
                ['status_pegawai' => $item['status_pegawai']],
                $item
            );
        }
    }
}
