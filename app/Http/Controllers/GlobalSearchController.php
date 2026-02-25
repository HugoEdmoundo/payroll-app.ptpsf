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

        // Search Acuan Gaji
        if (auth()->user()->hasPermission('acuan_gaji.view')) {
            $acuanGaji = AcuanGaji::with('karyawan')
                                 ->whereHas('karyawan', function($q) use ($query) {
                                     $q->where('nama_karyawan', 'like', "%{$query}%");
                                 })
                                 ->orWhere('periode', 'like', "%{$query}%")
                                 ->limit(5)
                                 ->get();
            
            foreach ($acuanGaji as $ag) {
                $results[] = [
                    'type' => 'Acuan Gaji',
                    'title' => $ag->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $ag->periode)->format('F Y'),
                    'url' => route('payroll.acuan-gaji.show', $ag->id_acuan),
                    'icon' => 'fa-file-invoice-dollar'
                ];
            }
        }

        // Search Hitung Gaji
        if (auth()->user()->hasPermission('hitung_gaji.view')) {
            $hitungGaji = HitungGaji::with('karyawan')
                                   ->whereHas('karyawan', function($q) use ($query) {
                                       $q->where('nama_karyawan', 'like', "%{$query}%");
                                   })
                                   ->orWhere('periode', 'like', "%{$query}%")
                                   ->limit(5)
                                   ->get();
            
            foreach ($hitungGaji as $hg) {
                $results[] = [
                    'type' => 'Hitung Gaji',
                    'title' => $hg->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $hg->periode)->format('F Y'),
                    'url' => route('payroll.hitung-gaji.show', $hg->id),
                    'icon' => 'fa-calculator'
                ];
            }
        }

        // Search Slip Gaji
        if (auth()->user()->hasPermission('slip_gaji.view')) {
            $slipGaji = SlipGaji::with('karyawan')
                               ->whereHas('karyawan', function($q) use ($query) {
                                   $q->where('nama_karyawan', 'like', "%{$query}%");
                               })
                               ->orWhere('periode', 'like', "%{$query}%")
                               ->limit(5)
                               ->get();
            
            foreach ($slipGaji as $sg) {
                $results[] = [
                    'type' => 'Slip Gaji',
                    'title' => $sg->karyawan->nama_karyawan ?? 'Unknown',
                    'subtitle' => 'Periode: ' . \Carbon\Carbon::createFromFormat('Y-m', $sg->periode)->format('F Y'),
                    'url' => route('payroll.slip-gaji.show', $sg->id_slip),
                    'icon' => 'fa-receipt'
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
