<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\AcuanGaji;
use App\Models\Karyawan;
use App\Models\PengaturanGaji;
use App\Models\NKI;
use App\Models\Absensi;
use App\Models\Kasbon;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;

class AcuanGajiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    public function index(Request $request)
    {
        // Get all unique periodes from Acuan Gaji
        $periodes = AcuanGaji::select('periode')
                            ->distinct()
                            ->orderBy('periode', 'desc')
                            ->get()
                            ->map(function($item) {
                                $totalKaryawan = AcuanGaji::where('periode', $item->periode)->count();
                                
                                return [
                                    'periode' => $item->periode,
                                    'total_karyawan' => $totalKaryawan,
                                ];
                            });

        return view('payroll.acuan-gaji.index', compact('periodes'));
    }

    public function managePeriode()
    {
        // Get all unique periodes with stats
        $periodes = AcuanGaji::select('periode')
                            ->distinct()
                            ->orderBy('periode', 'desc')
                            ->get()
                            ->map(function($item) {
                                $stats = AcuanGaji::where('periode', $item->periode)
                                                 ->selectRaw('
                                                     COUNT(*) as total_karyawan, 
                                                     SUM(gaji_bersih) as total_gaji_bersih,
                                                     SUM(bpjs_kesehatan_pendapatan + bpjs_kecelakaan_kerja_pendapatan + bpjs_kematian_pendapatan + bpjs_jht_pendapatan + bpjs_jp_pendapatan) as total_bpjs
                                                 ')
                                                 ->first();

                                // Pengeluaran Perusahaan = Total Gaji Bersih + Total BPJS
                                $pengeluaranPerusahaan = ($stats->total_gaji_bersih ?? 0) + ($stats->total_bpjs ?? 0);

                                return [
                                    'periode' => $item->periode,
                                    'total_karyawan' => $stats->total_karyawan,
                                    'total_gaji_bersih' => $stats->total_gaji_bersih ?? 0,
                                    'total_bpjs' => $stats->total_bpjs ?? 0,
                                    'total_pengeluaran_perusahaan' => $pengeluaranPerusahaan,
                                ];
                            });

        return view('payroll.acuan-gaji.manage-periode', compact('periodes'));
    }


    public function showPeriode(Request $request, $periode)
    {
        $query = AcuanGaji::with('karyawan')
                         ->where('periode', $periode);

        // Global search
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'periode',
            ], [
                'karyawan' => ['nama_karyawan', 'jenis_karyawan', 'jabatan', 'lokasi_kerja', 'email', 'no_telp']
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

        $acuanGajiList = $query->orderBy('id_acuan', 'desc')
                              ->paginate(50);

        // Calculate statistics for this periode
        $stats = AcuanGaji::where('periode', $periode)
                         ->selectRaw('
                             COUNT(*) as total_karyawan,
                             SUM(bpjs_kesehatan_pendapatan + bpjs_kecelakaan_kerja_pendapatan + bpjs_kematian_pendapatan + bpjs_jht_pendapatan + bpjs_jp_pendapatan) as total_bpjs,
                             SUM(gaji_bersih) as total_gaji_bersih
                         ')
                         ->first();
        
        // Calculate Pengeluaran Perusahaan = Total Gaji Bersih + Total BPJS
        $stats->total_pengeluaran_perusahaan = ($stats->total_gaji_bersih ?? 0) + ($stats->total_bpjs ?? 0);

        // Get unique lokasi kerja and jabatan for filters
        $lokasiKerjaList = Karyawan::select('lokasi_kerja')
                                  ->distinct()
                                  ->orderBy('lokasi_kerja')
                                  ->pluck('lokasi_kerja');
        
        $jabatanList = Karyawan::select('jabatan')
                              ->distinct()
                              ->orderBy('jabatan')
                              ->pluck('jabatan');

        return view('payroll.acuan-gaji.periode', compact('acuanGajiList', 'periode', 'lokasiKerjaList', 'jabatanList', 'stats'));
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

            // Get Pengaturan Gaji (automatically handles status_pegawai)
            $pengaturan = $karyawan->getPengaturanGaji();
            
            if (!$pengaturan) {
                $skipped++;
                continue; // Skip if no salary configuration
            }

            // Get BPJS & Koperasi from PengaturanBpjsKoperasi module (per status_pegawai only)
            $bpjsKoperasi = \App\Models\PengaturanBpjsKoperasi::where('status_pegawai', $karyawan->status_pegawai)->first();

            // Get NKI for tunjangan prestasi calculation
            $nki = \App\Models\NKI::where('id_karyawan', $karyawan->id_karyawan)
                                  ->where('periode', $periode)
                                  ->first();
            
            // Calculate tunjangan prestasi = tunjangan_prestasi × NKI%
            $tunjanganPrestasi = 0;
            if ($nki && $pengaturan->tunjangan_prestasi > 0) {
                $tunjanganPrestasi = $pengaturan->tunjangan_prestasi * ($nki->persentase_tunjangan / 100);
            }

            // Get Kasbon - handle both Langsung and Cicilan
            $kasbonTotal = 0;
            
            // Get all active kasbon for this employee (including Lunas to allow updates)
            $kasbonList = Kasbon::where('id_karyawan', $karyawan->id_karyawan)
                               ->whereIn('status_pembayaran', ['Pending', 'Cicilan', 'Lunas'])
                               ->orderBy('tanggal_pengajuan', 'asc')
                               ->get();
            
            foreach ($kasbonList as $kasbon) {
                $potongan = $kasbon->getPotonganForPeriode($periode);
                if ($potongan > 0) {
                    $kasbonTotal += $potongan;
                    break; // Only process first active kasbon
                }
            }

            // Determine BPJS & Koperasi based on status_pegawai
            $bpjsPendapatan = 0;
            $koperasiPengeluaran = 0;
            
            if ($bpjsKoperasi) {
                // BPJS Pendapatan: Only for Kontrak
                if ($karyawan->status_pegawai === 'Kontrak') {
                    $bpjsPendapatan = $bpjsKoperasi->total_bpjs; // Uses accessor
                }
                
                // Koperasi: For Kontrak and OJT (not Harian)
                if (in_array($karyawan->status_pegawai, ['Kontrak', 'OJT'])) {
                    $koperasiPengeluaran = $bpjsKoperasi->koperasi;
                }
            }
            
            // Create Acuan Gaji
            AcuanGaji::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'periode' => $periode,
                // Pendapatan
                'gaji_pokok' => $pengaturan->gaji_pokok,
                'bpjs_kesehatan' => $bpjsKoperasi && $karyawan->status_pegawai === 'Kontrak' ? $bpjsKoperasi->bpjs_kesehatan : 0,
                'bpjs_kecelakaan_kerja' => $bpjsKoperasi && $karyawan->status_pegawai === 'Kontrak' ? $bpjsKoperasi->bpjs_kecelakaan_kerja : 0,
                'bpjs_kematian' => $bpjsKoperasi && $karyawan->status_pegawai === 'Kontrak' ? $bpjsKoperasi->bpjs_kematian : 0,
                'bpjs_jht' => $bpjsKoperasi && $karyawan->status_pegawai === 'Kontrak' ? $bpjsKoperasi->bpjs_jht : 0,
                'bpjs_jp' => $bpjsKoperasi && $karyawan->status_pegawai === 'Kontrak' ? $bpjsKoperasi->bpjs_jp : 0,
                'tunjangan_prestasi' => $tunjanganPrestasi,
                'tunjangan_konjungtur' => 0,
                'benefit_ibadah' => 0,
                'benefit_komunikasi' => 0,
                'benefit_operasional' => 0,
                'reward' => 0,
                // Pengeluaran
                'koperasi' => $koperasiPengeluaran,
                'kasbon' => $kasbonTotal,
                'umroh' => 0,
                'kurban' => 0,
                'mutabaah' => 0,
                'potongan_absensi' => 0,
                'potongan_kehadiran' => 0,
            ]);

            $generated++;
        }

        return redirect()->route('payroll.acuan-gaji.index', ['periode' => $periode])
                        ->with('success', "Berhasil generate {$generated} acuan gaji. {$skipped} dilewati (sudah ada atau tidak ada pengaturan gaji).");
    }
                if ($karyawan->status_karyawan === 'Active' && $karyawan->status_pegawai !== 'Harian') {
                    $koperasiPengeluaran = $bpjsKoperasi->koperasi;
                }
            }
            
            // Create Acuan Gaji
            if ($isStatusPegawai) {
                // For Harian/OJT: only gaji_pokok, no BPJS, koperasi based on eligibility
                AcuanGaji::create([
                    'id_karyawan' => $karyawan->id_karyawan,
                    'periode' => $periode,
                    // Pendapatan (only gaji_pokok for status pegawai)
                    'gaji_pokok' => $pengaturan->gaji_pokok,
                    'bpjs_kesehatan_pendapatan' => 0,
                    'bpjs_kecelakaan_kerja_pendapatan' => 0,
                    'bpjs_kematian_pendapatan' => 0,
                    'bpjs_jht_pendapatan' => 0,
                    'bpjs_jp_pendapatan' => 0,
                    'tunjangan_prestasi' => $tunjanganPrestasi,
                    'tunjangan_konjungtur' => 0,
                    'benefit_ibadah' => 0,
                    'benefit_komunikasi' => 0,
                    'benefit_operasional' => 0,
                    'reward' => 0,
                    // Pengeluaran (Koperasi if eligible + Kasbon)
                    'bpjs_kesehatan_pengeluaran' => 0,
                    'bpjs_kecelakaan_kerja_pengeluaran' => 0,
                    'bpjs_kematian_pengeluaran' => 0,
                    'bpjs_jht_pengeluaran' => 0,
                    'bpjs_jp_pengeluaran' => 0,
                    'koperasi' => $koperasiPengeluaran,
                    'kasbon' => $kasbonTotal,
                    'umroh' => 0,
                    'kurban' => 0,
                    'mutabaah' => 0,
                    'potongan_absensi' => 0,
                    'potongan_kehadiran' => 0,
                ]);
            } else {
                // For Kontrak (normal employees): full benefits from PengaturanGaji + BPJS & Koperasi from module
                AcuanGaji::create([
                    'id_karyawan' => $karyawan->id_karyawan,
                    'periode' => $periode,
                    // Pendapatan (from Pengaturan Gaji + BPJS from module)
                    'gaji_pokok' => $pengaturan->gaji_pokok,
                    'bpjs_kesehatan_pendapatan' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kesehatan_pendapatan : 0,
                    'bpjs_kecelakaan_kerja_pendapatan' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kecelakaan_kerja_pendapatan : 0,
                    'bpjs_kematian_pendapatan' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kematian_pendapatan : 0,
                    'bpjs_jht_pendapatan' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_jht_pendapatan : 0,
                    'bpjs_jp_pendapatan' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_jp_pendapatan : 0,
                    'tunjangan_prestasi' => $tunjanganPrestasi,
                    'tunjangan_konjungtur' => 0,
                    'benefit_ibadah' => 0,
                    'benefit_komunikasi' => 0,
                    'benefit_operasional' => 0,
                    'reward' => 0,
                    // Pengeluaran (BPJS + Koperasi from module + Kasbon)
                    'bpjs_kesehatan_pengeluaran' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kesehatan_pengeluaran : 0,
                    'bpjs_kecelakaan_kerja_pengeluaran' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kecelakaan_kerja_pengeluaran : 0,
                    'bpjs_kematian_pengeluaran' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_kematian_pengeluaran : 0,
                    'bpjs_jht_pengeluaran' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_jht_pengeluaran : 0,
                    'bpjs_jp_pengeluaran' => $bpjsKoperasi ? $bpjsKoperasi->bpjs_jp_pengeluaran : 0,
                    'koperasi' => $koperasiPengeluaran,
                    'kasbon' => $kasbonTotal,
                    'umroh' => 0,
                    'kurban' => 0,
                    'mutabaah' => 0,
                    'potongan_absensi' => 0,
                    'potongan_kehadiran' => 0,
                ]);
            }

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
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AcuanGajiTemplateExport,
            'template_acuan_gaji.xlsx'
        );
    }

    public function deletePeriode($periode)
    {
        // Delete all acuan gaji for this periode
        $deleted = AcuanGaji::where('periode', $periode)->delete();

        return redirect()->route('payroll.acuan-gaji.index')
                        ->with('success', "Berhasil menghapus periode {$periode} dengan {$deleted} data acuan gaji.");
    }


}
