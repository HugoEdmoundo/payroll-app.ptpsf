<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanBpjsKoperasi;

class PengaturanBpjsKoperasiSeeder extends Seeder
{
    public function run(): void
    {
        // Global configuration - only one record
        PengaturanBpjsKoperasi::updateOrCreate(
            ['id' => 1],
            [
                // BPJS Pendapatan (Auto for Kontrak)
                'bpjs_kesehatan_pendapatan' => 100000,
                'bpjs_kecelakaan_kerja_pendapatan' => 50000,
                'bpjs_kematian_pendapatan' => 25000,
                'bpjs_jht_pendapatan' => 150000,
                'bpjs_jp_pendapatan' => 75000,
                
                // Koperasi (Auto for Kontrak & OJT)
                'koperasi' => 100000,
            ]
        );
    }
}
