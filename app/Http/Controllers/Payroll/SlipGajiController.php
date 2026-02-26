<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HitungGaji;
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SlipGajiExport;

class SlipGajiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    public function index(Request $request)
    {
        // Get all unique periodes from Hitung Gaji
        $periodes = HitungGaji::select('periode')
                            ->distinct()
                            ->orderBy('periode', 'desc')
                            ->get()
                            ->map(function($item) {
                                $totalKaryawan = HitungGaji::where('periode', $item->periode)->count();
                                
                                return [
                                    'periode' => $item->periode,
                                    'total_karyawan' => $totalKaryawan,
                                ];
                            });

        return view('payroll.slip-gaji.index', compact('periodes'));
    }

    public function showPeriode(Request $request, $periode)
    {
        $query = HitungGaji::with(['karyawan'])
                          ->where('periode', $periode);

        // Global search using trait
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'karyawan' => ['nama_karyawan', 'jenis_karyawan', 'lokasi_kerja', 'jabatan']
            ]);
        }

        // Filter by lokasi kerja
        if ($request->has('lokasi_kerja') && $request->lokasi_kerja != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('lokasi_kerja', $request->lokasi_kerja);
            });
        }

        // Filter by jabatan
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('jabatan', $request->jabatan);
            });
        }

        $slipGajiList = $query->orderBy('created_at', 'desc')
                             ->paginate(50);

        // Get unique lokasi kerja and jabatan for filters
        $lokasiKerjaList = Karyawan::select('lokasi_kerja')
                                  ->distinct()
                                  ->orderBy('lokasi_kerja')
                                  ->pluck('lokasi_kerja');
        
        $jabatanList = Karyawan::select('jabatan')
                              ->distinct()
                              ->orderBy('jabatan')
                              ->pluck('jabatan');

        return view('payroll.slip-gaji.periode', compact('slipGajiList', 'periode', 'lokasiKerjaList', 'jabatanList'));
    }

    // Get slip gaji data for modal
    public function getSlipData($hitungGajiId)
    {
        try {
            $hitungGaji = HitungGaji::with(['karyawan'])->findOrFail($hitungGajiId);
            
            // Get related data with keterangan
            $pengaturanGaji = \App\Models\PengaturanGaji::where('jenis_karyawan', $hitungGaji->karyawan->jenis_karyawan)
                                                         ->where('jabatan', $hitungGaji->karyawan->jabatan)
                                                         ->where('lokasi_kerja', $hitungGaji->karyawan->lokasi_kerja)
                                                         ->first();
            $nki = \App\Models\NKI::where('id_karyawan', $hitungGaji->karyawan_id)
                                  ->where('periode', $hitungGaji->periode)
                                  ->first();
            $absensi = \App\Models\Absensi::where('id_karyawan', $hitungGaji->karyawan_id)
                                          ->where('periode', $hitungGaji->periode)
                                          ->first();
            $kasbon = \App\Models\Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
                                        ->where('periode', $hitungGaji->periode)
                                        ->first();
            $acuanGaji = \App\Models\AcuanGaji::where('id_karyawan', $hitungGaji->karyawan_id)
                                              ->where('periode', $hitungGaji->periode)
                                              ->first();
            
            $data = [
                'hitung_gaji' => $hitungGaji,
                'karyawan' => $hitungGaji->karyawan,
                'periode' => $hitungGaji->periode,
                'periode_formatted' => \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y'),
                'pengaturan_gaji' => $pengaturanGaji,
                'nki' => $nki,
                'absensi' => $absensi,
                'kasbon' => $kasbon,
                'acuan_gaji' => $acuanGaji,
            ];

            return view('components.slip-gaji.modal-slip', compact('data'))->render();
        } catch (\Exception $e) {
            \Log::error('Error in getSlipData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal load slip gaji',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    // Download PDF
    public function downloadPDF($hitungGajiId)
    {
        try {
            $hitungGaji = HitungGaji::with(['karyawan'])->findOrFail($hitungGajiId);
            
            // Get related data with keterangan
            $pengaturanGaji = \App\Models\PengaturanGaji::where('jenis_karyawan', $hitungGaji->karyawan->jenis_karyawan)
                                                         ->where('jabatan', $hitungGaji->karyawan->jabatan)
                                                         ->where('lokasi_kerja', $hitungGaji->karyawan->lokasi_kerja)
                                                         ->first();
            $nki = \App\Models\NKI::where('id_karyawan', $hitungGaji->karyawan_id)
                                  ->where('periode', $hitungGaji->periode)
                                  ->first();
            $absensi = \App\Models\Absensi::where('id_karyawan', $hitungGaji->karyawan_id)
                                          ->where('periode', $hitungGaji->periode)
                                          ->first();
            $kasbon = \App\Models\Kasbon::where('id_karyawan', $hitungGaji->karyawan_id)
                                        ->where('periode', $hitungGaji->periode)
                                        ->first();
            $acuanGaji = \App\Models\AcuanGaji::where('id_karyawan', $hitungGaji->karyawan_id)
                                              ->where('periode', $hitungGaji->periode)
                                              ->first();
            
            $data = [
                'hitung_gaji' => $hitungGaji,
                'karyawan' => $hitungGaji->karyawan,
                'periode' => $hitungGaji->periode,
                'periode_formatted' => \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y'),
                'pengaturan_gaji' => $pengaturanGaji,
                'nki' => $nki,
                'absensi' => $absensi,
                'kasbon' => $kasbon,
                'acuan_gaji' => $acuanGaji,
            ];

            $pdf = Pdf::loadView('payroll.slip-gaji.pdf', compact('data'))
                      ->setPaper('a4', 'landscape');

            $filename = 'slip-gaji-' . str_replace(' ', '-', $hitungGaji->karyawan->nama_karyawan) . '-' . $hitungGaji->periode . '.pdf';
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal generate PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Export Excel (All periode)
    public function exportExcel($periode)
    {
        $filename = 'slip-gaji-periode-' . $periode . '.xlsx';
        
        return Excel::download(new SlipGajiExport(null, $periode), $filename);
    }
}
