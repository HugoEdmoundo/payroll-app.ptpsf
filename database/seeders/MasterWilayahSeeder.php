<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterWilayah;

class MasterWilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayah = [
            ['nama_wilayah' => 'Jakarta', 'kode_wilayah' => 'JKT', 'keterangan' => 'DKI Jakarta'],
            ['nama_wilayah' => 'Bandung', 'kode_wilayah' => 'BDG', 'keterangan' => 'Jawa Barat'],
            ['nama_wilayah' => 'Surabaya', 'kode_wilayah' => 'SBY', 'keterangan' => 'Jawa Timur'],
            ['nama_wilayah' => 'Semarang', 'kode_wilayah' => 'SMG', 'keterangan' => 'Jawa Tengah'],
            ['nama_wilayah' => 'Medan', 'kode_wilayah' => 'MDN', 'keterangan' => 'Sumatera Utara'],
        ];

        foreach ($wilayah as $data) {
            MasterWilayah::firstOrCreate(
                ['kode_wilayah' => $data['kode_wilayah']],
                $data
            );
        }
    }
}
