<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kasbon;
use App\Models\Karyawan;
use Carbon\Carbon;

class KasbonSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Carbon::now()->format('Y-m');
        
        // Get random 10 employees for kasbon
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->inRandomOrder()
                               ->limit(10)
                               ->get();

        $kasbonTypes = [
            ['nominal' => 1000000, 'deskripsi' => 'Keperluan keluarga', 'metode' => 'Cicilan', 'jumlah' => 2],
            ['nominal' => 2000000, 'deskripsi' => 'Biaya pendidikan anak', 'metode' => 'Cicilan', 'jumlah' => 4],
            ['nominal' => 500000, 'deskripsi' => 'Keperluan mendesak', 'metode' => 'Langsung', 'jumlah' => null],
            ['nominal' => 1500000, 'deskripsi' => 'Renovasi rumah', 'metode' => 'Cicilan', 'jumlah' => 3],
            ['nominal' => 3000000, 'deskripsi' => 'Biaya pengobatan', 'metode' => 'Cicilan', 'jumlah' => 6],
        ];

        foreach ($karyawanList as $index => $karyawan) {
            $kasbonData = $kasbonTypes[$index % count($kasbonTypes)];
            
            Kasbon::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                'tanggal_pengajuan' => Carbon::now()->subDays(rand(1, 15)),
                'deskripsi' => $kasbonData['deskripsi'],
                'nominal' => $kasbonData['nominal'],
                'metode_pembayaran' => $kasbonData['metode'],
                'status_pembayaran' => 'Pending',
                'jumlah_cicilan' => $kasbonData['jumlah'],
                'cicilan_terbayar' => 0,
                'sisa_cicilan' => $kasbonData['nominal'],
                'keterangan' => 'Approved by manager',
            ]);
        }
    }
}
