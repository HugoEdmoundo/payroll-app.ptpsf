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
        
        // Get acuan gaji for this periode
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
            'penyesuaian_pendapatan' => 'nullable|array',
            'penyesuaian_pendapatan.*.komponen' => 'required|string',
            'penyesuaian_pendapatan.*.nominal' => 'required|numeric|min:0',
            'penyesuaian_pendapatan.*.tipe' => 'required|in:+,-',
            'penyesuaian_pendapatan.*.deskripsi' => 'required|string',
            'penyesuaian_pengeluaran' => 'nullable|array',
            'penyesuaian_pengeluaran.*.komponen' => 'required|string',
            'penyesuaian_pengeluaran.*.nominal' => 'required|numeric|min:0',
            'penyesuaian_pengeluaran.*.tipe' => 'required|in:+,-',
            'penyesuaian_pengeluaran.*.deskripsi' => 'required|string',
            'catatan_umum' => 'nullable|string',
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

        // Get NKI and calculate Tunjangan Prestasi
        $nki = NKI::where('id_karyawan', $acuanGaji->id_karyawan)
                 ->where('periode', $acuanGaji->periode)
                 ->first();
        
        $tunjanganPrestasi = 0;
        if ($nki && $pengaturan && $pengaturan->tunjangan_operasional > 0) {
            $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
        }

        // Get Absensi and calculate Potongan Absensi
        $absensi = Absensi::where('id_karyawan', $acuanGaji->id_karyawan)
                         ->where('periode', $acuanGaji->periode)
                         ->first();
        
        $potonganAbsensi = 0;
        if ($absensi && $pengaturan) {
            $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
            $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
            $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
        }

        // Prepare pendapatan acuan (from Acuan Gaji + NKI)
        $pendapatanAcuan = [
            'gaji_pokok' => $acuanGaji->gaji_pokok,
            'tunjangan_prestasi' => $tunjanganPrestasi, // From NKI
            'benefit_operasional' => $acuanGaji->benefit_operasional,
            'bpjs_kesehatan' => $acuanGaji->bpjs_kesehatan_pendapatan,
            'bpjs_ketenagakerjaan' => $acuanGaji->bpjs_kematian_pendapatan,
            'bpjs_kecelakaan_kerja' => $acuanGaji->bpjs_kecelakaan_kerja_pendapatan,
        ];

        // Prepare pengeluaran acuan (from Acuan Gaji + Absensi)
        $pengeluaranAcuan = [
            'potongan_koperasi' => $acuanGaji->koperasi,
            'potongan_absensi' => $potonganAbsensi, // From Absensi
            'potongan_kasbon' => $acuanGaji->kasbon,
        ];

        // Calculate totals
        $totalPendapatanAcuan = array_sum($pendapatanAcuan);
        $totalPengeluaranAcuan = array_sum($pengeluaranAcuan);

        // Calculate penyesuaian
        $penyesuaianPendapatan = $request->penyesuaian_pendapatan ?? [];
        $penyesuaianPengeluaran = $request->penyesuaian_pengeluaran ?? [];

        $totalPenyesuaianPendapatan = 0;
        foreach ($penyesuaianPendapatan as $item) {
            $totalPenyesuaianPendapatan += $item['tipe'] === '+' ? $item['nominal'] : -$item['nominal'];
        }

        $totalPenyesuaianPengeluaran = 0;
        foreach ($penyesuaianPengeluaran as $item) {
            $totalPenyesuaianPengeluaran += $item['tipe'] === '+' ? $item['nominal'] : -$item['nominal'];
        }

        $totalPendapatanAkhir = $totalPendapatanAcuan + $totalPenyesuaianPendapatan;
        $totalPengeluaranAkhir = $totalPengeluaranAcuan + $totalPenyesuaianPengeluaran;
        $takeHomePay = $totalPendapatanAkhir - $totalPengeluaranAkhir;

        HitungGaji::create([
            'acuan_gaji_id' => $acuanGaji->id_acuan,
            'karyawan_id' => $acuanGaji->id_karyawan,
            'periode' => $acuanGaji->periode,
            'pendapatan_acuan' => json_encode($pendapatanAcuan),
            'pengeluaran_acuan' => json_encode($pengeluaranAcuan),
            'penyesuaian_pendapatan' => !empty($penyesuaianPendapatan) ? json_encode($penyesuaianPendapatan) : null,
            'penyesuaian_pengeluaran' => !empty($penyesuaianPengeluaran) ? json_encode($penyesuaianPengeluaran) : null,
            'total_pendapatan_acuan' => $totalPendapatanAcuan,
            'total_penyesuaian_pendapatan' => $totalPenyesuaianPendapatan,
            'total_pendapatan_akhir' => $totalPendapatanAkhir,
            'total_pengeluaran_acuan' => $totalPengeluaranAcuan,
            'total_penyesuaian_pengeluaran' => $totalPenyesuaianPengeluaran,
            'total_pengeluaran_akhir' => $totalPengeluaranAkhir,
            'take_home_pay' => $takeHomePay,
            'status' => 'draft',
            'catatan_umum' => $request->catatan_umum,
        ]);

        return redirect()->route('payroll.hitung-gaji.index')
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
            'penyesuaian_pendapatan' => 'nullable|array',
            'penyesuaian_pendapatan.*.komponen' => 'required|string',
            'penyesuaian_pendapatan.*.nominal' => 'required|numeric|min:0',
            'penyesuaian_pendapatan.*.tipe' => 'required|in:+,-',
            'penyesuaian_pendapatan.*.deskripsi' => 'required|string',
            'penyesuaian_pengeluaran' => 'nullable|array',
            'penyesuaian_pengeluaran.*.komponen' => 'required|string',
            'penyesuaian_pengeluaran.*.nominal' => 'required|numeric|min:0',
            'penyesuaian_pengeluaran.*.tipe' => 'required|in:+,-',
            'penyesuaian_pengeluaran.*.deskripsi' => 'required|string',
            'catatan_umum' => 'nullable|string',
        ]);

        // Recalculate penyesuaian
        $penyesuaianPendapatan = $request->penyesuaian_pendapatan ?? [];
        $penyesuaianPengeluaran = $request->penyesuaian_pengeluaran ?? [];

        $totalPenyesuaianPendapatan = 0;
        foreach ($penyesuaianPendapatan as $item) {
            $totalPenyesuaianPendapatan += $item['tipe'] === '+' ? $item['nominal'] : -$item['nominal'];
        }

        $totalPenyesuaianPengeluaran = 0;
        foreach ($penyesuaianPengeluaran as $item) {
            $totalPenyesuaianPengeluaran += $item['tipe'] === '+' ? $item['nominal'] : -$item['nominal'];
        }

        $totalPendapatanAkhir = $hitungGaji->total_pendapatan_acuan + $totalPenyesuaianPendapatan;
        $totalPengeluaranAkhir = $hitungGaji->total_pengeluaran_acuan + $totalPenyesuaianPengeluaran;
        $takeHomePay = $totalPendapatanAkhir - $totalPengeluaranAkhir;

        $hitungGaji->update([
            'penyesuaian_pendapatan' => !empty($penyesuaianPendapatan) ? json_encode($penyesuaianPendapatan) : null,
            'penyesuaian_pengeluaran' => !empty($penyesuaianPengeluaran) ? json_encode($penyesuaianPengeluaran) : null,
            'total_penyesuaian_pendapatan' => $totalPenyesuaianPendapatan,
            'total_pendapatan_akhir' => $totalPendapatanAkhir,
            'total_penyesuaian_pengeluaran' => $totalPenyesuaianPengeluaran,
            'total_pengeluaran_akhir' => $totalPengeluaranAkhir,
            'take_home_pay' => $takeHomePay,
            'catatan_umum' => $request->catatan_umum,
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
}
