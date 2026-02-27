<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanBpjsKoperasi;

class PengaturanBpjsKoperasiSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update single active configuration
        PengaturanBpjsKoperasi::updateOrCreate(
            ['is_active' => true],
            [
                // BPJS Components (Pendapatan)
                'bpjs_kesehatan' => 400000,
                'bpjs_ketenagakerjaan' => 250000,
                'bpjs_kecelakaan_kerja' => 150000,
                'bpjs_jht' => 200000,
                'bpjs_jp' => 100000,
                
                // Koperasi (Potongan)
                'koperasi' => 100000,
                
                'keterangan' => 'Default BPJS & Koperasi configuration',
            ]
        );
        
        $this->command->info('✓ Pengaturan BPJS & Koperasi seeded successfully!');
    }
}
