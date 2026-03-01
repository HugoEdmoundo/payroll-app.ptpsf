<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanBpjsKoperasi;

class PengaturanBpjsKoperasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create single global BPJS & Koperasi configuration
        PengaturanBpjsKoperasi::updateOrCreate(
            ['id' => 1],
            [
                'bpjs_kesehatan_pendapatan' => 1111.00,
                'bpjs_kecelakaan_kerja_pendapatan' => 1111.00,
                'bpjs_kematian_pendapatan' => 1111.00,
                'bpjs_jht_pendapatan' => 11111.00,
                'bpjs_jp_pendapatan' => 1111.00,
                'koperasi' => 11111.00,
            ]
        );
        
        $this->command->info('✅ BPJS & Koperasi configuration created successfully!');
    }
}
