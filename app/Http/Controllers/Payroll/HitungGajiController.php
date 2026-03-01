<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HitungGaji;
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\PengaturanGaji;
use App\Models\NKI;
use App\Models\Absensi;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;

class HitungGajiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    public function index(Request $request)
    {
        // Get all unique periodes from Acuan Gaji (not Hitung Gaji)
        // This ensures Hitung Gaji only shows periodes that exist in Acuan Gaji
        $periodes = AcuanGaji::select('periode')
                            ->distinct()
                            ->orderBy('periode', 'desc')
                            ->get()
                            ->map(function($item) {
                                // Count from Hitung Gaji for this periode
                                $totalKaryawan = HitungGaji::where('periode', $item->periode)->count();
                                
                                return [
                                    'periode' => $item->periode,
                                    'total_karyawan' => $totalKaryawan,
                                ];
                            });

        return view('payroll.hitung-gaji.index', compact('periodes'));
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

        $hitungGajiList = $query->orderBy('created_at', 'desc')
                                ->paginate(50);

        // Calculate statistics for this periode
        $stats = HitungGaji::where('periode', $periode)
                          ->selectRaw('
                              COUNT(*) as total_karyawan,
                              SUM(bpjs_kesehatan_pendapatan + bpjs_kecelakaan_kerja_pendapatan + bpjs_kematian_pendapatan + bpjs_jht_pendapatan + bpjs_jp_pendapatan) as total_bpjs,
                              SUM(gaji_bersih) as total_gaji_bersih,
                              SUM(total_pengeluaran) as total_pengeluaran_perusahaan
                          ')
                          ->first();

        return view('payroll.hitung-gaji.periode', compact('hitungGajiList', 'periode', 'stats'));
    }

    public function create(Request $request)
    {
        // This method is not used anymore
        // We use modal from index page directly
        return redirect()->route('payroll.hitung-gaji.index');
    }

    // Get data for modal (existing or create new)
    public function getModalData($karyawanId, $periode)
    {
        $karyawan = Karyawan::findOrFail($karyawanId);
        
        // Check if hitung gaji already exists
        $hitungGaji = HitungGaji::where('karyawan_id', $karyawanId)
                               ->where('periode', $periode)
                               ->first();
        
        if ($hitungGaji) {
            // Return existing data for edit
            \Log::info('Loading existing hitung gaji for edit', [
                'id' => $hitungGaji->id,
                'adjustments_raw' => $hitungGaji->getAttributes()['adjustments'] ?? 'NULL',
                'adjustments_cast' => $hitungGaji->adjustments,
            ]);
            
            $data = [
                'mode' => 'edit',
                'hitung_gaji_id' => $hitungGaji->id,
                'karyawan' => [
                    'id' => $karyawan->id_karyawan,
                    'nama' => $karyawan->nama_karyawan,
                    'jabatan' => $karyawan->jabatan,
                    'jenis' => $karyawan->jenis_karyawan
                ],
                'periode' => $periode,
                'fields' => [
                    'gaji_pokok' => $hitungGaji->gaji_pokok,
                    'bpjs_kesehatan_pendapatan' => $hitungGaji->bpjs_kesehatan_pendapatan,
                    'bpjs_kecelakaan_kerja_pendapatan' => $hitungGaji->bpjs_kecelakaan_kerja_pendapatan,
                    'bpjs_kematian_pendapatan' => $hitungGaji->bpjs_kematian_pendapatan,
                    'bpjs_jht_pendapatan' => $hitungGaji->bpjs_jht_pendapatan,
                    'bpjs_jp_pendapatan' => $hitungGaji->bpjs_jp_pendapatan,
                    'tunjangan_prestasi' => $hitungGaji->tunjangan_prestasi,
                    'tunjangan_konjungtur' => $hitungGaji->tunjangan_konjungtur,
                    'benefit_ibadah' => $hitungGaji->benefit_ibadah,
                    'benefit_komunikasi' => $hitungGaji->benefit_komunikasi,
                    'benefit_operasional' => $hitungGaji->benefit_operasional,
                    'reward' => $hitungGaji->reward,
                    'koperasi' => $hitungGaji->koperasi,
                    'kasbon' => $hitungGaji->kasbon,
                    'umroh' => $hitungGaji->umroh,
                    'kurban' => $hitungGaji->kurban,
                    'mutabaah' => $hitungGaji->mutabaah,
                    'potongan_absensi' => $hitungGaji->potongan_absensi,
                    'potongan_kehadiran' => $hitungGaji->potongan_kehadiran,
                ],
                'adjustments' => $hitungGaji->adjustments ?? [],
                'keterangan' => $hitungGaji->keterangan,
                'nki_info' => null,
                'absensi_info' => null
            ];
            
            return view('components.hitung-gaji.modal-form', compact('data'))->render();
        }
        
        // Get acuan gaji for this karyawan and periode
        $acuanGaji = AcuanGaji::where('id_karyawan', $karyawanId)
                             ->where('periode', $periode)
                             ->first();
        
        if (!$acuanGaji) {
            return response()->json(['error' => 'Acuan gaji tidak ditemukan untuk karyawan ini pada periode tersebut.'], 404);
        }

        // Get Pengaturan Gaji for calculations (automatically handles status_pegawai)
        $pengaturan = $karyawan->getPengaturanGaji();

        if (!$pengaturan) {
            return response()->json(['error' => 'Pengaturan gaji tidak ditemukan untuk karyawan ini.'], 404);
        }
        
        // Check if this is status pegawai (Harian/OJT only)
        $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

        // Calculate NKI (Tunjangan Prestasi) - ONLY for Kontrak (normal employees)
        // Formula: Tunjangan Prestasi (from Acuan Gaji) × Persentase Tunjangan (from NKI)
        $nki = NKI::where('id_karyawan', $karyawanId)
                 ->where('periode', $periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        $nkiInfo = null;
        
        if (!$isStatusPegawai && $nki) {
            // Get tunjangan_prestasi from acuan gaji (base amount)
            $baseTunjanganPrestasi = $acuanGaji->tunjangan_prestasi ?? 0;
            if ($baseTunjanganPrestasi > 0) {
                $tunjanganPrestasi = $baseTunjanganPrestasi * ($nki->persentase_tunjangan / 100);
                $nkiInfo = [
                    'persentase' => $nki->persentase_tunjangan,
                    'nilai_nki' => $nki->nilai_nki,
                    'acuan' => $baseTunjanganPrestasi
                ];
            }
        }

        // Calculate Absensi (Potongan Absensi)
        // Formula: (Absence + Tanpa Keterangan) ÷ Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
        $absensi = Absensi::where('id_karyawan', $karyawanId)
                         ->where('periode', $periode)
                         ->first();
        
        $potonganAbsensi = 0;
        $absensiInfo = null;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $gajiPokok = $pengaturan->gaji_pokok ?? 0;
            $operasional = $isStatusPegawai ? 0 : ($pengaturan->tunjangan_operasional ?? 0);
            
            // Base amount = Gaji Pokok + Tunjangan Prestasi + Operasional
            $baseAmount = $gajiPokok + $tunjanganPrestasi + $operasional;
            
            if ($absensi->jumlah_hari_bulan > 0) {
                $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
            }
            
            $absensiInfo = [
                'absence' => $absensi->absence,
                'tanpa_keterangan' => $absensi->tanpa_keterangan,
                'jumlah_hari' => $absensi->jumlah_hari_bulan,
                'base_amount' => $baseAmount
            ];
        }

        // Prepare data for new hitung gaji
        $data = [
            'mode' => 'create',
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan' => [
                'id' => $karyawan->id_karyawan,
                'nama' => $karyawan->nama_karyawan,
                'jabatan' => $karyawan->jabatan,
                'jenis' => $karyawan->jenis_karyawan
            ],
            'periode' => $periode,
            'fields' => [
                'gaji_pokok' => $acuanGaji->gaji_pokok,
                'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
                'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
                'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
                'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
                'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
                'tunjangan_prestasi' => $tunjanganPrestasi,
                'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
                'benefit_ibadah' => $acuanGaji->benefit_ibadah,
                'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
                'benefit_operasional' => $acuanGaji->benefit_operasional,
                'reward' => $acuanGaji->reward,
                'koperasi' => $acuanGaji->koperasi,
                'kasbon' => $acuanGaji->kasbon,
                'umroh' => $acuanGaji->umroh,
                'kurban' => $acuanGaji->kurban,
                'mutabaah' => $acuanGaji->mutabaah,
                'potongan_absensi' => $potonganAbsensi,
                'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            ],
            'adjustments' => [],
            'keterangan' => null,
            'nki_info' => $nkiInfo,
            'absensi_info' => $absensiInfo
        ];

        return view('components.hitung-gaji.modal-form', compact('data'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        // Check if already exists
        $exists = HitungGaji::where('karyawan_id', $request->karyawan_id)
                           ->where('periode', $request->periode)
                           ->first();
        
        if ($exists) {
            // Update existing
            return $this->updateExisting($exists, $request);
        }

        // Get acuan gaji
        $acuanGaji = AcuanGaji::where('id_karyawan', $request->karyawan_id)
                             ->where('periode', $request->periode)
                             ->first();

        if (!$acuanGaji) {
            return back()->withErrors(['karyawan_id' => 'Acuan gaji tidak ditemukan.'])->withInput();
        }

        // Get Pengaturan Gaji for calculations (automatically handles status_pegawai)
        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        $pengaturan = $karyawan->getPengaturanGaji();

        if (!$pengaturan) {
            return back()->withErrors(['karyawan_id' => 'Pengaturan gaji tidak ditemukan.'])->withInput();
        }
        
        // Check if this is status pegawai (Harian/OJT only)
        $isStatusPegawai = in_array($karyawan->status_pegawai, ['Harian', 'OJT']);

        // Calculate NKI - ONLY for Kontrak (normal employees)
        // Formula: Tunjangan Prestasi (from Acuan Gaji) × Persentase Tunjangan (from NKI)
        $nki = NKI::where('id_karyawan', $request->karyawan_id)
                 ->where('periode', $request->periode)
                 ->first();
        
        $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi; // Default from acuan gaji
        if (!$isStatusPegawai && $nki && $acuanGaji->tunjangan_prestasi > 0) {
            // Apply NKI percentage to tunjangan prestasi
            $tunjanganPrestasi = $acuanGaji->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
        }

        // Calculate Absensi
        // Formula: (Absence + Tanpa Keterangan) ÷ Jumlah Hari × (Gaji Pokok + Tunjangan Prestasi + Operasional)
        $absensi = Absensi::where('id_karyawan', $request->karyawan_id)
                         ->where('periode', $request->periode)
                         ->first();
        
        $potonganAbsensi = $acuanGaji->potongan_absensi; // Default from acuan gaji
        if ($absensi && $absensi->jumlah_hari_bulan > 0) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $acuanGaji->gaji_pokok + $tunjanganPrestasi + $acuanGaji->benefit_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Process adjustments - ALWAYS save even if empty
        $adjustments = $request->input('adjustments', []);
        $processedAdjustments = [];
        
        \Log::info('Raw adjustments received in store:', ['adjustments' => $adjustments]);
        
        foreach ($adjustments as $field => $adj) {
            // Check if adjustment has data
            if (isset($adj['nominal']) && $adj['nominal'] != '' && $adj['nominal'] != 0) {
                $processedAdjustments[$field] = [
                    'nominal' => (float) $adj['nominal'],
                    'type' => $adj['type'] ?? '+',
                    'description' => $adj['description'] ?? 'Adjustment'
                ];
            }
        }
        
        \Log::info('Processed adjustments in store:', ['count' => count($processedAdjustments), 'data' => $processedAdjustments]);

        // Create Hitung Gaji
        HitungGaji::create([
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan_id' => $request->karyawan_id,
            'periode' => $request->periode,
            'gaji_pokok' => $acuanGaji->gaji_pokok,
            'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
            'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
            'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
            'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
            'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
            'tunjangan_prestasi' => $tunjanganPrestasi,
            'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
            'benefit_ibadah' => $acuanGaji->benefit_ibadah,
            'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
            'benefit_operasional' => $acuanGaji->benefit_operasional,
            'reward' => $acuanGaji->reward,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi,
            'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            'adjustments' => $processedAdjustments,
            'status' => 'approved', // Langsung approved, tidak perlu workflow
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'message' => 'Hitung gaji berhasil disimpan.']);
    }

    private function updateExisting($hitungGaji, $request)
    {
        // Process adjustments - ALWAYS save even if empty
        $adjustments = $request->input('adjustments', []);
        $processedAdjustments = [];
        
        \Log::info('Raw adjustments received:', ['adjustments' => $adjustments]);
        
        foreach ($adjustments as $field => $adj) {
            // Check if adjustment has data
            if (isset($adj['nominal']) && $adj['nominal'] != '' && $adj['nominal'] != 0) {
                $processedAdjustments[$field] = [
                    'nominal' => (float) $adj['nominal'],
                    'type' => $adj['type'] ?? '+',
                    'description' => $adj['description'] ?? 'Adjustment'
                ];
            }
        }
        
        \Log::info('Processed adjustments:', ['count' => count($processedAdjustments), 'data' => $processedAdjustments]);

        $hitungGaji->update([
            'adjustments' => $processedAdjustments,
            'keterangan' => $request->keterangan,
        ]);
        
        \Log::info('After save - adjustments in DB:', ['adjustments' => $hitungGaji->fresh()->adjustments]);

        return response()->json(['success' => true, 'message' => 'Hitung gaji berhasil diupdate.']);
    }

    public function show(HitungGaji $hitungGaji)
    {
        $hitungGaji->load(['karyawan', 'acuanGaji']);
        return view('payroll.hitung-gaji.show', compact('hitungGaji'));
    }

    public function destroy(HitungGaji $hitungGaji)
    {
        $hitungGaji->delete();
        return redirect()->route('payroll.hitung-gaji.index')
                        ->with('success', 'Hitung gaji berhasil dihapus.');
    }
    
    // AJAX endpoint for loading form data
    public function getFormData($acuanGajiId)
    {
        // This is not used anymore, kept for backward compatibility
        return response()->json(['error' => 'Method not used'], 404);
    }

    public function deletePeriode($periode)
    {
        // Delete all hitung gaji for this periode
        $deleted = HitungGaji::where('periode', $periode)->delete();

        return redirect()->route('payroll.hitung-gaji.index')
                        ->with('success', "Berhasil menghapus periode {$periode} dengan {$deleted} data hitung gaji.");
    }

}
