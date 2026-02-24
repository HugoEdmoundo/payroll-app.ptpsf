<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;
use App\Models\NKI;
use App\Models\Absensi;
use App\Models\Kasbon;
use App\Models\KasbonCicilan;
use Carbon\Carbon;

class KomponenGajiSeeder extends Seeder
{
    /**
     * Seed komponen gaji: NKI, Absensi, Kasbon
     * Menggunakan data karyawan yang sudah ada
     */
    public function run(): void
    {
        $this->command->info('Seeding Komponen Gaji (NKI, Absensi, Kasbon)...');
        
        // Ambil HANYA karyawan ACTIVE
        $karyawans = Karyawan::where('status_karyawan', 'Active')->get();
        
        if ($karyawans->count() === 0) {
            $this->command->warn('No active karyawan found. Please run KaryawanSeeder first.');
            return;
        }
        
        $this->command->info("Found {$karyawans->count()} active karyawan");
        
        $periode = Carbon::now()->format('Y-m'); // Periode bulan ini
        $jumlahHariBulan = Carbon::now()->daysInMonth;
        
        $this->command->info("Creating data for periode: {$periode}");
        
        // Seed NKI untuk setiap karyawan ACTIVE
        $this->seedNKI($karyawans, $periode);
        
        // Seed Absensi untuk setiap karyawan ACTIVE
        $this->seedAbsensi($karyawans, $periode, $jumlahHariBulan);
        
        // Seed Kasbon untuk beberapa karyawan ACTIVE (random)
        $this->seedKasbon($karyawans, $periode);
        
        $this->command->info('Komponen Gaji seeded successfully!');
    }
    
    private function seedNKI($karyawans, $periode)
    {
        $this->command->info('Seeding NKI data...');
        
        foreach ($karyawans as $karyawan) {
            // Generate nilai NKI random tapi realistis
            $kemampuan = rand(70, 100) / 10; // 7.0 - 10.0
            $kontribusi = rand(70, 100) / 10;
            $kedisiplinan = rand(70, 100) / 10;
            $lainnya = rand(70, 100) / 10;
            
            // Hitung nilai NKI (weighted average)
            $nilaiNKI = ($kemampuan * 0.2) + ($kontribusi * 0.2) + ($kedisiplinan * 0.4) + ($lainnya * 0.2);
            
            // Tentukan persentase tunjangan berdasarkan NKI
            if ($nilaiNKI >= 8.5) {
                $persentase = 100;
            } elseif ($nilaiNKI >= 8.0) {
                $persentase = 80;
            } else {
                $persentase = 70;
            }
            
            NKI::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                'kemampuan' => $kemampuan,
                'kontribusi' => $kontribusi,
                'kedisiplinan' => $kedisiplinan,
                'lainnya' => $lainnya,
                'nilai_nki' => round($nilaiNKI, 2),
                'persentase_tunjangan' => $persentase,
            ]);
        }
        
        $this->command->info("  ✓ Created NKI for {$karyawans->count()} karyawan");
    }
    
    private function seedAbsensi($karyawans, $periode, $jumlahHariBulan)
    {
        $this->command->info('Seeding Absensi data...');
        
        foreach ($karyawans as $karyawan) {
            // Generate absensi realistis
            $absence = rand(0, 3); // 0-3 hari absence
            $tanpaKeterangan = rand(0, 1); // 0-1 hari tanpa keterangan
            $hadir = $jumlahHariBulan - $absence - $tanpaKeterangan;
            
            Absensi::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                'jumlah_hari_bulan' => $jumlahHariBulan,
                'hadir' => $hadir,
                'absence' => $absence,
                'tanpa_keterangan' => $tanpaKeterangan,
            ]);
        }
        
        $this->command->info("  ✓ Created Absensi for {$karyawans->count()} karyawan");
    }
    
    private function seedKasbon($karyawans, $periode)
    {
        $this->command->info('Seeding Kasbon data...');
        
        // Hanya 30% karyawan yang punya kasbon
        $karyawansWithKasbon = $karyawans->random(min(ceil($karyawans->count() * 0.3), $karyawans->count()));
        
        $deskripsiOptions = [
            'Keperluan Mendadak',
            'Biaya Pendidikan Anak',
            'Renovasi Rumah',
            'Biaya Kesehatan',
            'Modal Usaha',
            'Keperluan Keluarga',
        ];
        
        foreach ($karyawansWithKasbon as $karyawan) {
            $metodePembayaran = rand(0, 1) ? 'Langsung' : 'Cicilan';
            $nominal = rand(500000, 5000000); // 500rb - 5jt
            $jumlahCicilan = $metodePembayaran === 'Cicilan' ? rand(3, 12) : null;
            
            $kasbon = Kasbon::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                'tanggal_pengajuan' => Carbon::now()->subDays(rand(1, 15)),
                'nominal' => $nominal,
                'deskripsi' => $deskripsiOptions[array_rand($deskripsiOptions)],
                'metode_pembayaran' => $metodePembayaran,
                'jumlah_cicilan' => $jumlahCicilan,
                'cicilan_terbayar' => 0,
                'status_pembayaran' => 'Pending',
            ]);
            
            // Create cicilan records if method is Cicilan
            if ($metodePembayaran === 'Cicilan' && $jumlahCicilan > 0) {
                $nominalPerCicilan = $nominal / $jumlahCicilan;
                $startPeriode = Carbon::createFromFormat('Y-m', $periode);
                
                for ($i = 1; $i <= $jumlahCicilan; $i++) {
                    $cicilanPeriode = $startPeriode->copy()->addMonths($i - 1)->format('Y-m');
                    
                    KasbonCicilan::create([
                        'id_kasbon' => $kasbon->id_kasbon,
                        'cicilan_ke' => $i,
                        'periode' => $cicilanPeriode,
                        'nominal_cicilan' => $nominalPerCicilan,
                        'status' => 'Pending',
                    ]);
                }
                
                $this->command->info("    → Created {$jumlahCicilan} cicilan for kasbon #{$kasbon->id_kasbon}");
            }
        }
        
        $this->command->info("  ✓ Created Kasbon for {$karyawansWithKasbon->count()} karyawan");
    }
}
