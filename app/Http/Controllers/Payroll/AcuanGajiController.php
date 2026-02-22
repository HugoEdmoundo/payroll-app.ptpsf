<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
=======
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\PengaturanGaji;
use App\Models\NKI;
use App\Models\Absensi;
use App\Models\Kasbon;
>>>>>>> fitur-baru
use Illuminate\Http\Request;

class AcuanGajiController extends Controller
{
<<<<<<< HEAD
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
=======
    public function index(Request $request)
    {
        $query = AcuanGaji::with('karyawan');

        // Filter by periode
        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        // Filter by jenis karyawan
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('jenis_karyawan', $request->jenis_karyawan);
            });
        }

        // Filter by jabatan
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('jabatan', $request->jabatan);
            });
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%");
            });
        }

        $acuanGajiList = $query->orderBy('periode', 'desc')
                              ->orderBy('id_acuan', 'desc')
                              ->paginate(15);

        // Get jabatan list for filter (if jenis_karyawan selected)
        $jabatanList = [];
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan != '') {
            $jabatanList = Karyawan::where('jenis_karyawan', $request->jenis_karyawan)
                                  ->where('status_karyawan', 'Active')
                                  ->distinct()
                                  ->pluck('jabatan')
                                  ->toArray();
        }

        return view('payroll.acuan-gaji.index', compact('acuanGajiList', 'jabatanList'));
    }

    public function history(Request $request)
    {
        $query = AcuanGaji::with('karyawan');

        // Filter by periode
        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        // Filter by jenis karyawan
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('jenis_karyawan', $request->jenis_karyawan);
            });
        }

        // Filter by jabatan
        if ($request->has('jabatan') && $request->jabatan != '') {
            $query->whereHas('karyawan', function($q) use ($request) {
                $q->where('jabatan', $request->jabatan);
            });
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%");
            });
        }

        $acuanGajiList = $query->orderBy('periode', 'desc')
                              ->orderBy('id_acuan', 'desc')
                              ->paginate(15);

        // Get jabatan list for filter (if jenis_karyawan selected)
        $jabatanList = [];
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan != '') {
            $jabatanList = Karyawan::where('jenis_karyawan', $request->jenis_karyawan)
                                  ->where('status_karyawan', 'Active')
                                  ->distinct()
                                  ->pluck('jabatan')
                                  ->toArray();
        }

        // Get all unique periodes for filter
        $periodeList = AcuanGaji::distinct()
                                ->orderBy('periode', 'desc')
                                ->pluck('periode')
                                ->toArray();

        return view('payroll.acuan-gaji.history', compact('acuanGajiList', 'jabatanList', 'periodeList'));
    }

    // Generate Acuan Gaji for all employees in a periode
    public function generate(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'jenis_karyawan' => 'nullable|string',
        ]);

        $periode = $request->periode;
        $jenisKaryawan = $request->jenis_karyawan;

        // Get active employees
        $query = Karyawan::where('status_karyawan', 'Active');
        
        if ($jenisKaryawan) {
            $query->where('jenis_karyawan', $jenisKaryawan);
        }
        
        $karyawanList = $query->get();
        $generated = 0;
        $skipped = 0;

        foreach ($karyawanList as $karyawan) {
            // Check if already exists
            $exists = AcuanGaji::where('id_karyawan', $karyawan->id_karyawan)
                              ->where('periode', $periode)
                              ->exists();
            
            if ($exists) {
                $skipped++;
                continue;
            }

            // Get Pengaturan Gaji
            $pengaturan = PengaturanGaji::where('jenis_karyawan', $karyawan->jenis_karyawan)
                                       ->where('jabatan', $karyawan->jabatan)
                                       ->where('lokasi_kerja', $karyawan->lokasi_kerja)
                                       ->first();
            
            if (!$pengaturan) {
                $skipped++;
                continue; // Skip if no salary configuration
            }

            // Get NKI
            $nki = NKI::where('id_karyawan', $karyawan->id_karyawan)
                     ->where('periode', $periode)
                     ->first();
            
            // Calculate Tunjangan Prestasi
            $tunjanganPrestasi = 0;
            if ($nki && $pengaturan->tunjangan_operasional > 0) {
                $tunjanganPrestasi = $pengaturan->tunjangan_operasional * ($nki->persentase_tunjangan / 100);
            }

            // Get Absensi
            $absensi = Absensi::where('id_karyawan', $karyawan->id_karyawan)
                             ->where('periode', $periode)
                             ->first();
            
            // Calculate Potongan Absensi
            $potonganAbsensi = 0;
            if ($absensi) {
                $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
                $baseAmount = $pengaturan->gaji_pokok + $tunjanganPrestasi + $pengaturan->tunjangan_operasional;
                $potonganAbsensi = ($totalAbsence / $absensi->jumlah_hari_bulan) * $baseAmount;
            }

            // Get Kasbon
            $kasbonTotal = Kasbon::where('id_karyawan', $karyawan->id_karyawan)
                                ->where('periode', $periode)
                                ->where('status_pembayaran', 'Pending')
                                ->sum('nominal');

            // Create Acuan Gaji - Sync all data from Pengaturan Gaji and Komponen
            AcuanGaji::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                // Pendapatan (from Pengaturan Gaji - READ ONLY)
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'bpjs_kesehatan_pendapatan' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pendapatan' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pendapatan' => $pengaturan->bpjs_ketenagakerjaan,
                'tunjangan_prestasi' => $tunjanganPrestasi, // From NKI
                'benefit_operasional' => $pengaturan->tunjangan_operasional,
                // Pengeluaran (from Komponen + Manual)
                'bpjs_kesehatan_pengeluaran' => $pengaturan->bpjs_kesehatan,
                'bpjs_kecelakaan_kerja_pengeluaran' => $pengaturan->bpjs_kecelakaan_kerja,
                'bpjs_kematian_pengeluaran' => $pengaturan->bpjs_ketenagakerjaan,
                'koperasi' => $pengaturan->potongan_koperasi,
                'kasbon' => $kasbonTotal, // From Kasbon
                'potongan_absensi' => $potonganAbsensi, // From Absensi
                // Empty fields will be filled manually by user
                'bpjs_jht_pendapatan' => 0,
                'bpjs_jp_pendapatan' => 0,
                'tunjangan_konjungtur' => 0,
                'benefit_ibadah' => 0,
                'benefit_komunikasi' => 0,
                'reward' => 0,
                'bpjs_jht_pengeluaran' => 0,
                'bpjs_jp_pengeluaran' => 0,
                'tabungan_koperasi' => 0,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_kehadiran' => 0,
            ]);

            $generated++;
        }

        return redirect()->route('payroll.acuan-gaji.index', ['periode' => $periode])
                        ->with('success', "Berhasil generate {$generated} acuan gaji. {$skipped} dilewati (sudah ada atau tidak ada pengaturan gaji).");
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.acuan-gaji.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        // Check if already exists
        $exists = AcuanGaji::where('id_karyawan', $request->id_karyawan)
                          ->where('periode', $request->periode)
                          ->exists();
        
        if ($exists) {
            return back()->withErrors(['periode' => 'Acuan gaji untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        AcuanGaji::create($request->all());

        return redirect()->route('payroll.acuan-gaji.index')
                        ->with('success', 'Acuan gaji berhasil ditambahkan.');
    }

    public function show(AcuanGaji $acuanGaji)
    {
        $acuanGaji->load('karyawan');
        return view('payroll.acuan-gaji.show', compact('acuanGaji'));
    }

    public function edit(AcuanGaji $acuanGaji)
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.acuan-gaji.edit', compact('acuanGaji', 'karyawanList'));
    }

    public function update(Request $request, AcuanGaji $acuanGaji)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        // Check if already exists (excluding current)
        $exists = AcuanGaji::where('id_karyawan', $request->id_karyawan)
                          ->where('periode', $request->periode)
                          ->where('id_acuan', '!=', $acuanGaji->id_acuan)
                          ->exists();
        
        if ($exists) {
            return back()->withErrors(['periode' => 'Acuan gaji untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        $acuanGaji->update($request->all());

        return redirect()->route('payroll.acuan-gaji.index')
                        ->with('success', 'Acuan gaji berhasil diupdate.');
    }

    public function destroy(AcuanGaji $acuanGaji)
    {
        $acuanGaji->delete();

        return redirect()->route('payroll.acuan-gaji.index')
                        ->with('success', 'Acuan gaji berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'acuan_gaji_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AcuanGajiExport($periode),
            $filename
        );
    }

    public function import()
    {
        return view('payroll.acuan-gaji.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\AcuanGajiImport,
                $request->file('file')
            );

            return redirect()->route('payroll.acuan-gaji.index')
                           ->with('success', 'Data acuan gaji berhasil diimport.');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
>>>>>>> fitur-baru
    }
}
