<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\AcuanGaji;
use App\Models\HitungGaji;
use App\Models\SlipGaji;
use App\Models\NKI;
use App\Models\Absensi;
use App\Models\Kasbon;
use App\Models\PengaturanGaji;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return response()->json([
                'results' => [],
                'message' => 'Please enter a search term'
            ]);
        }

        $results = [];

        // Search Karyawan
        if (auth()->user()->hasPermission('karyawan.view')) {
            $karyawan = Karyawan::where('nama_karyawan', 'like', "%{$query}%")
                               ->orWhere('email', 'like', "%{$query}%")
                               ->orWhere('no_telp', 'like', "%{$query}%")
                               ->orWhere('jabatan', 'like', "%{$query}%")
                               ->orWhere('lokasi_kerja', 'like', "%{$query}%")
                               ->orWhere('jenis_karyawan', 'like', "%{$query}%")
                               ->limit(5)
                               ->get();
            
            foreach ($karyawan as $k) {
                $results[] = [
                    'type' => 'Karyawan',
                    'title' => $k->nama_karyawan,
                    'subtitle' => $k->jabatan . ' - ' . $k->lokasi_kerja,
                    'url' => route('karyawan.show', $k->id_karyawan),
                    'icon' => 'fa-user'
                ];
            }
        }

        // Search Acuan Gaji by Periode (direct access)
        if (auth()->user()->hasPermission('acuan_gaji.view')) {
            $periodes = AcuanGaji::select('periode')
                                ->where('periode', 'like', "%{$query}%")
                                ->distinct()
                                ->limit(3)
                                ->get();
            
            foreach ($periodes as $p) {
                $count = AcuanGaji::where('periode', $p->periode)->count();
                $results[] = [
                    'type' => 'Acuan Gaji - Periode',
                    'title' => \Carbon\Carbon::createFromFormat('Y-m', $p->periode)->format('F Y'),
                    'subtitle' => "Total {$count} karyawan",
                    'url' => route('payroll.acuan-gaji.periode', $p->periode),
                    'icon' => 'fa-calendar-alt'
                ];
            }
            
            // Search by Jenis Karyawan in Acuan Gaji
            $jenisKaryawan = AcuanGaji::with('karyawan')
                                     ->whereHas('karyawan', function($q) use ($query) {
                                         $q->where('jenis_karyawan', 'like', "%{$query}%");
                                     })
                                     ->select('periode')
                                     ->distinct()
                                     ->limit(3)
                                     ->get();
            
            foreach ($jenisKaryawan as $jk) {
                $results[] = [
                    'type' => 'Acuan Gaji',
                    'title' => 'Periode ' . \Carbon\Carbon::createFromFormat('Y-m', $jk->periode)->format('F Y'),
                    'subtitle' => 'Filter: ' . $query,
                    'url' => route('payroll.acuan-gaji.periode', $jk->periode),
                    'icon' => 'fa-file-invoice-dollar'
                ];
            }
        }

        // Search Hitung Gaji by Periode (direct access)
        if (auth()->user()->hasPermission('hitung_gaji.view')) {
            $periodes = HitungGaji::select('periode')
                                 ->where('periode', 'like', "%{$query}%")
                                 ->distinct()
                                 ->limit(3)
                                 ->get();
            
            foreach ($periodes as $p) {
                $count = HitungGaji::where('periode', $p->periode)->count();
                $results[] = [
                    'type' => 'Hitung Gaji - Periode',
                    'title' => \Carbon\Carbon::createFromFormat('Y-m', $p->periode)->format('F Y'),
                    'subtitle' => "Total {$count} karyawan",
                    'url' => route('payroll.hitung-gaji.periode', $p->periode),
                    'icon' => 'fa-calculator'
                ];
            }
        }

        // Search Slip Gaji by Periode (direct access)
        if (auth()->user()->hasPermission('slip_gaji.view')) {
            $periodes = SlipGaji::select('periode')
                               ->where('periode', 'like', "%{$query}%")
                               ->distinct()
                               ->limit(3)
                               ->get();
            
            foreach ($periodes as $p) {
                $count = SlipGaji::where('periode', $p->periode)->count();
                $results[] = [
                    'type' => 'Slip Gaji - Periode',
                    'title' => \Carbon\Carbon::createFromFormat('Y-m', $p->periode)->format('F Y'),
                    'subtitle' => "Total {$count} slip gaji",
                    'url' => route('payroll.slip-gaji.periode', $p->periode),
                    'icon' => 'fa-receipt'
                ];
            }
        }

        // Search Pengaturan Gaji
        if (auth()->user()->hasPermission('pengaturan_gaji.view')) {
            $pengaturan = PengaturanGaji::where('jenis_karyawan', 'like', "%{$query}%")
                                       ->orWhere('jabatan', 'like', "%{$query}%")
                                       ->orWhere('lokasi_kerja', 'like', "%{$query}%")
                                       ->limit(5)
                                       ->get();
            
            foreach ($pengaturan as $pg) {
                $results[] = [
                    'type' => 'Pengaturan Gaji',
                    'title' => $pg->jenis_karyawan . ' - ' . $pg->jabatan,
                    'subtitle' => $pg->lokasi_kerja . ' - Rp ' . number_format($pg->gaji_pokok, 0, ',', '.'),
                    'url' => route('payroll.pengaturan-gaji.show', $pg->id_pengaturan),
                    'icon' => 'fa-cog'
                ];
            }
        }

        // Search NKI
        if (auth()->user()->hasPermission('nki.view')) {
            $nki = NKI::with('karyawan')
                     ->whereHas('karyawan', function($q) use ($query) {
                         $q->where('nama_karyawan', 'like', "%{$query}%");
                     })
                     ->orWhere('periode', 'like', "%{$query}%")
                     ->limit(5)
                     ->get();
            
            foreach ($nki as $n) {
                $results[] = [
                    'type' => 'NKI',
                    'title' => $n->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $n->periode)->format('F Y') . ' - NKI: ' . number_format($n->nilai_nki, 2),
                    'url' => route('payroll.nki.show', $n->id_nki),
                    'icon' => 'fa-chart-line'
                ];
            }
        }

        // Search Absensi
        if (auth()->user()->hasPermission('absensi.view')) {
            $absensi = Absensi::with('karyawan')
                             ->whereHas('karyawan', function($q) use ($query) {
                                 $q->where('nama_karyawan', 'like', "%{$query}%");
                             })
                             ->orWhere('periode', 'like', "%{$query}%")
                             ->limit(5)
                             ->get();
            
            foreach ($absensi as $a) {
                $results[] = [
                    'type' => 'Absensi',
                    'title' => $a->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $a->periode)->format('F Y'),
                    'url' => route('payroll.absensi.show', $a->id_absensi),
                    'icon' => 'fa-calendar-check'
                ];
            }
        }

        // Search Kasbon
        if (auth()->user()->hasPermission('kasbon.view')) {
            $kasbon = Kasbon::with('karyawan')
                           ->whereHas('karyawan', function($q) use ($query) {
                               $q->where('nama_karyawan', 'like', "%{$query}%");
                           })
                           ->limit(5)
                           ->get();
            
            foreach ($kasbon as $k) {
                $results[] = [
                    'type' => 'Kasbon',
                    'title' => $k->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Rp ' . number_format($k->nominal, 0, ',', '.') . ' - ' . $k->status_pembayaran,
                    'url' => route('payroll.kasbon.show', $k->id_kasbon),
                    'icon' => 'fa-hand-holding-usd'
                ];
            }
        }

        return response()->json([
            'results' => $results,
            'total' => count($results),
            'query' => $query
        ]);
    }
}
