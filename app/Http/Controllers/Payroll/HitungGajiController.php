<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HitungGaji;
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\PengaturanGaji;
use App\Models\NKI;
use App\Models\Absensi;
use Illuminate\Http\Request;

class HitungGajiController extends Controller
{
    public function index(Request $request)
    {
        $query = HitungGaji::with(['karyawan', 'acuanGaji']);

        // Filter by periode
        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%");
            });
        }

        $hitungGajiList = $query->orderBy('periode', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(15);

        return view('payroll.hitung-gaji.index', compact('hitungGajiList'));
    }

    public function create(Request $request)
    {
        // Get periode from request or default to current month
        $periode = $request->get('periode', date('Y-m'));
        
        // Get acuan gaji for this periode that don't have hitung gaji yet
        $acuanGajiList = AcuanGaji::with('karyawan')
                                  ->where('periode', $periode)
                                  ->whereDoesntHave('hitungGaji')
                                  ->get();
        
        if ($acuanGajiList->isEmpty()) {
            return redirect()->route('payroll.hitung-gaji.index')
                           ->with('error', 'Tidak ada acuan gaji untuk periode ini atau semua sudah diproses.');
        }

        return view('payroll.hitung-gaji.create', compact('acuanGajiList', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'acuan_gaji_id' => 'required|exists:acuan_gaji,id_acuan',
            'keterangan' => 'nullable|string',
        ]);

        $acuanGaji = AcuanGaji::findOrFail($request->acuan_gaji_id);

        // Check if already exists
        $exists = HitungGaji::where('karyawan_id', $acuanGaji->id_karyawan)
                           ->where('periode', $acuanGaji->periode)
                           ->exists();
        
        if ($exists) {
            return back()->withErrors(['acuan_gaji_id' => 'Hitung gaji untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        // Get Pengaturan Gaji for calculations
        $karyawan = $acuanGaji->karyawan;
        $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                   ->where('jabatan', $karyawan->jabatan)
                                   ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                   ->first();

        if (!$pengaturan) {
            return back()->withErrors(['acuan_gaji_id' => 'Pengaturan gaji tidak ditemukan untuk karyawan ini.'])->withInput();
        }

        // Calculate NKI (Tunjangan Prestasi) - FINAL CALCULATION
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Calculate Absensi (Potongan Absensi) - FINAL CALCULATION
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = 0;
        if ($absensi) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Process adjustments from request
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

        // Create Hitung Gaji - Copy ALL fields from Acuan Gaji + Calculated values
        $hitungGaji = HitungGaji::create([
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan_id' => $acuanGaji->id_karyawan,
            'periode' => $acuanGaji->periode,
            // PENDAPATAN - Copy from Acuan Gaji + NKI
            'gaji_pokok' => $acuanGaji->gaji_pokok,
            'bpjs_kesehatan_pendapatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
            'bpjs_kecelakaan_kerja_pendapatan' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
            'bpjs_kematian_pendapatan' => $acuanGaji->bpjs_kematian_pendapatan,
            'bpjs_jht_pendapatan' => $acuanGaji->bpjs_jht_pendapatan,
            'bpjs_jp_pendapatan' => $acuanGaji->bpjs_jp_pendapatan,
            'tunjangan_prestasi' => $tunjanganPrestasi, // CALCULATED from NKI
            'tunjangan_konjungtur' => $acuanGaji->tunjangan_konjungtur,
            'benefit_ibadah' => $acuanGaji->benefit_ibadah,
            'benefit_komunikasi' => $acuanGaji->benefit_komunikasi,
            'benefit_operasional' => $acuanGaji->benefit_operasional,
            'reward' => $acuanGaji->reward,
            // PENGELUARAN - Copy from Acuan Gaji + Absensi
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
            'potongan_absensi' => $potonganAbsensi, // CALCULATED from Absensi
            'potongan_kehadiran' => $acuanGaji->potongan_kehadiran,
            // Adjustments
            'adjustments' => $adjustments,
            'status' => 'draft',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('payroll.hitung-gaji.show', $hitungGaji)
                        ->with('success', 'Hitung gaji berhasil dibuat dengan status Draft.');
    }

    public function show(HitungGaji $hitungGaji)
    {
        $hitungGaji->load(['karyawan', 'acuanGaji']);
        return view('payroll.hitung-gaji.show', compact('hitungGaji'));
    }

    public function edit(HitungGaji $hitungGaji)
    {
        // Only draft can be edited
        if ($hitungGaji->status !== 'draft') {
            return redirect()->route('payroll.hitung-gaji.show', $hitungGaji)
                           ->with('error', 'Hanya hitung gaji dengan status Draft yang bisa diedit.');
        }

        $hitungGaji->load(['karyawan', 'acuanGaji']);
        return view('payroll.hitung-gaji.edit', compact('hitungGaji'));
    }

    public function update(Request $request, HitungGaji $hitungGaji)
    {
        // Only draft can be updated
        if ($hitungGaji->status !== 'draft') {
            return back()->with('error', 'Hanya hitung gaji dengan status Draft yang bisa diupdate.');
        }

        $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        // Process adjustments from request
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

        return redirect()->route('payroll.hitung-gaji.show', $hitungGaji)
                        ->with('success', 'Hitung gaji berhasil diupdate.');
    }

    public function destroy(HitungGaji $hitungGaji)
    {
        // Only draft can be deleted
        if ($hitungGaji->status !== 'draft') {
            return back()->with('error', 'Hanya hitung gaji dengan status Draft yang bisa dihapus.');
        }

        $hitungGaji->delete();

        return redirect()->route('payroll.hitung-gaji.index')
                        ->with('success', 'Hitung gaji berhasil dihapus.');
    }

    public function preview(HitungGaji $hitungGaji)
    {
        if ($hitungGaji->status !== 'draft') {
            return back()->with('error', 'Hanya hitung gaji dengan status Draft yang bisa di-preview.');
        }

        $hitungGaji->update(['status' => 'preview']);

        return redirect()->route('payroll.hitung-gaji.show', $hitungGaji)
                        ->with('success', 'Status berubah menjadi Preview. Silakan review sebelum approve.');
    }

    public function approve(HitungGaji $hitungGaji)
    {
        if ($hitungGaji->status === 'approved') {
            return back()->with('error', 'Hitung gaji ini sudah di-approve.');
        }

        $hitungGaji->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('payroll.hitung-gaji.show', $hitungGaji)
                        ->with('success', 'Hitung gaji berhasil di-approve. Siap untuk generate slip gaji.');
    }

    public function backToDraft(HitungGaji $hitungGaji)
    {
        if ($hitungGaji->status === 'approved') {
            return back()->with('error', 'Hitung gaji yang sudah approved tidak bisa dikembalikan ke draft.');
        }

        $hitungGaji->update(['status' => 'draft']);

        return redirect()->route('payroll.hitung-gaji.edit', $hitungGaji)
                        ->with('success', 'Status dikembalikan ke Draft. Silakan edit kembali.');
    }
    
    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'hitung_gaji_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\HitungGajiExport($periode),
            $filename
        );
    }

    public function import()
    {
        return view('payroll.hitung-gaji.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\HitungGajiImport,
                $request->file('file')
            );

            return redirect()->route('payroll.hitung-gaji.index')
                           ->with('success', 'Data hitung gaji berhasil diimport.');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\HitungGajiTemplateExport,
            'template_hitung_gaji.xlsx'
        );
    }
}
