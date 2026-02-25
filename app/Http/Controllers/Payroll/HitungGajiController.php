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
                    'bpjs_kesehatan_pengeluaran' => $hitungGaji->bpjs_kesehatan_pengeluaran,
                    'bpjs_kecelakaan_kerja_pengeluaran' => $hitungGaji->bpjs_kecelakaan_kerja_pengeluaran,
                    'bpjs_kematian_pengeluaran' => $hitungGaji->bpjs_kematian_pengeluaran,
                    'bpjs_jht_pengeluaran' => $hitungGaji->bpjs_jht_pengeluaran,
                    'bpjs_jp_pengeluaran' => $hitungGaji->bpjs_jp_pengeluaran,
                    'tabungan_koperasi' => $hitungGaji->tabungan_koperasi,
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

        // Get Pengaturan Gaji for calculations
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return response()->json(['error' => 'Pengaturan gaji tidak ditemukan untuk karyawan ini.'], 404);
        }

        // Calculate NKI (Tunjangan Prestasi)
        $nki = NKI::where('id_karyawan', $karyawanId)
                 ->where('periode', $periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        $nkiInfo = null;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
            $nkiInfo = [
                'nilai' => $nki->nilai_nki,
                'persentase' => $nki->persentase_tunjangan,
                'acuan' => $pengaturan->tunjangan_operasional
            ];
        }

        // Calculate Absensi (Potongan Absensi)
        $absensi = Absensi::where('id_karyawan', $karyawanId)
                         ->where('periode', $periode)
                         ->first();
        
        $potonganAbsensi = 0;
        $absensiInfo = null;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
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
                'bpjs_kesehatan_pengeluaran' => $acuanGaji->bpjs_kesehatan_pengeluaran,
                'bpjs_kecelakaan_kerja_pengeluaran' => $acuanGaji->bpjs_kecelakaan_kerja_pengeluaran,
                'bpjs_kematian_pengeluaran' => $acuanGaji->bpjs_kematian_pengeluaran,
                'bpjs_jht_pengeluaran' => $acuanGaji->bpjs_jht_pengeluaran,
                'bpjs_jp_pengeluaran' => $acuanGaji->bpjs_jp_pengeluaran,
                'tabungan_koperasi' => $acuanGaji->tabungan_koperasi,
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

        // Get Pengaturan Gaji for calculations
        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return back()->withErrors(['karyawan_id' => 'Pengaturan gaji tidak ditemukan.'])->withInput();
        }

        // Calculate NKI
        $nki = NKI::where('id_karyawan', $request->karyawan_id)
                 ->where('periode', $request->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Calculate Absensi
        $absensi = Absensi::where('id_karyawan', $request->karyawan_id)
                         ->where('periode', $request->periode)
                         ->first();
        
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Process adjustments
        $adjustments = [];
        if ($request->has('adjustments')) {
            foreach ($request->adjustments as $field => $adj) {
                if (!empty($adj['nominal']) && !empty($adj['description'])) {
                    $adjustments[$field] = [
                        'nominal' => (float) $adj['nominal'],
                        'type' => $adj['type'],
                        'description' => $adj['description']
                    ];
                }
            }
        }

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
            'bpjs_kesehatan_pengeluaran' => $acuanGaji->bpjs_kesehatan_pengeluaran,
            'bpjs_kecelakaan_kerja_pengeluaran' => $acuanGaji->bpjs_kecelakaan_kerja_pengeluaran,
            'bpjs_kematian_pengeluaran' => $acuanGaji->bpjs_kematian_pengeluaran,
            'bpjs_jht_pengeluaran' => $acuanGaji->bpjs_jht_pengeluaran,
            'bpjs_jp_pengeluaran' => $acuanGaji->bpjs_jp_pengeluaran,
            'tabungan_koperasi' => $acuanGaji->tabungan_koperasi,
            'koperasi' => $acuanGaji->koperasi,
            'kasbon' => $acuanGaji->kasbon,
            'umroh' => $acuanGaji->umroh,
            'kurban' => $acuanGaji->kurban,
            'mutabaah' => $acuanGaji->mutabaah,
            'potongan_absensi' => $potonganAbsensi,
            'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            'adjustments' => $adjustments,
            'status' => 'approved', // Langsung approved, tidak perlu workflow
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'message' => 'Hitung gaji berhasil disimpan.']);
    }

    private function updateExisting($hitungGaji, $request)
    {
        // Process adjustments
        $adjustments = [];
        if ($request->has('adjustments')) {
            foreach ($request->adjustments as $field => $adj) {
                if (!empty($adj['nominal']) && !empty($adj['description'])) {
                    $adjustments[$field] = [
                        'nominal' => (float) $adj['nominal'],
                        'type' => $adj['type'],
                        'description' => $adj['description']
                    ];
                }
            }
        }

        $hitungGaji->update([
            'adjustments' => $adjustments,
            'keterangan' => $request->keterangan,
        ]);

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
