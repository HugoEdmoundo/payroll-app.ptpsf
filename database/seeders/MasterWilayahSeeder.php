<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterWilayah;

class MasterWilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayah = [
            ['nama' => 'Jakarta', 'kode' => 'JKT', 'keterangan' => 'DKI Jakarta'],
            ['nama' => 'Bandung', 'kode' => 'BDG', 'keterangan' => 'Jawa Barat'],
            ['nama' => 'Surabaya', 'kode' => 'SBY', 'keterangan' => 'Jawa Timur'],
            ['nama' => 'Semarang', 'kode' => 'SMG', 'keterangan' => 'Jawa Tengah'],
            ['nama' => 'Medan', 'kode' => 'MDN', 'keterangan' => 'Sumatera Utara'],
        ];

        foreach ($wilayah as $data) {
            MasterWilayah::firstOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }
    }
}
