<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanBpjsKoperasi;
use App\Models\SystemSetting;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PengaturanBpjsKoperasiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    
    public function index(Request $request)
    {
        $query = PengaturanBpjsKoperasi::query();
        
        // Filter by jenis karyawan
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan) {
            $query->where('jenis_karyawan', $request->jenis_karyawan);
        }
        
        // Filter by status pegawai
        if ($request->has('status_pegawai') && $request->status_pegawai) {
            $query->where('status_pegawai', $request->status_pegawai);
        }
        
        // Global search
        if ($request->has('search') && $request->search) {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'jenis_karyawan', 'status_pegawai'
            ]);
        }
        
        $pengaturanBpjsKoperasi = $query->orderBy('jenis_karyawan')
                                        ->orderBy('status_pegawai')
                                        ->paginate(15);
        
        return view('payroll.pengaturan-bpjs-koperasi.index', compact('pengaturanBpjsKoperasi'));
    }

    public function create()
    {
        $settings = [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'status_pegawai' => ['Kontrak', 'OJT', 'Harian'],
        ];
        
        return view('payroll.pengaturan-bpjs-koperasi.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_karyawan' => 'required|string',
            'status_pegawai' => 'required|string|in:Kontrak,OJT,Harian',
            'bpjs_kesehatan_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kesehatan_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_kematian_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kematian_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_jht_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jht_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_jp_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jp_pengeluaran' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique
        $exists = PengaturanBpjsKoperasi::where('jenis_karyawan', $request->jenis_karyawan)
            ->where('status_pegawai', $request->status_pegawai)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan BPJS & Koperasi untuk kombinasi ini sudah ada.');
        }
        
        PengaturanBpjsKoperasi::create($request->all());
        
        return redirect()->route('payroll.pengaturan-bpjs-koperasi.index')
            ->with('success', 'Pengaturan BPJS & Koperasi berhasil ditambahkan.');
    }

    public function show(PengaturanBpjsKoperasi $pengaturanBpjsKoperasi)
    {
        return view('payroll.pengaturan-bpjs-koperasi.show', compact('pengaturanBpjsKoperasi'));
    }

    public function edit(PengaturanBpjsKoperasi $pengaturanBpjsKoperasi)
    {
        $settings = [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'status_pegawai' => ['Kontrak', 'OJT', 'Harian'],
        ];
        
        return view('payroll.pengaturan-bpjs-koperasi.edit', compact('pengaturanBpjsKoperasi', 'settings'));
    }

    public function update(Request $request, PengaturanBpjsKoperasi $pengaturanBpjsKoperasi)
    {
        $request->validate([
            'jenis_karyawan' => 'required|string',
            'status_pegawai' => 'required|string|in:Kontrak,OJT,Harian',
            'bpjs_kesehatan_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kesehatan_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_kematian_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kematian_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_jht_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jht_pengeluaran' => 'nullable|numeric|min:0',
            'bpjs_jp_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jp_pengeluaran' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique (except current)
        $exists = PengaturanBpjsKoperasi::where('jenis_karyawan', $request->jenis_karyawan)
            ->where('status_pegawai', $request->status_pegawai)
            ->where('id', '!=', $pengaturanBpjsKoperasi->id)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan BPJS & Koperasi untuk kombinasi ini sudah ada.');
        }
        
        $pengaturanBpjsKoperasi->update($request->all());
        
        return redirect()->route('payroll.pengaturan-bpjs-koperasi.index')
            ->with('success', 'Pengaturan BPJS & Koperasi berhasil diupdate.');
    }

    public function destroy(PengaturanBpjsKoperasi $pengaturanBpjsKoperasi)
    {
        $pengaturanBpjsKoperasi->delete();
        
        return redirect()->route('payroll.pengaturan-bpjs-koperasi.index')
            ->with('success', 'Pengaturan BPJS & Koperasi berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $jenisKaryawan = $request->get('jenis_karyawan');
        $statusPegawai = $request->get('status_pegawai');
        
        $filename = 'pengaturan_bpjs_koperasi';
        if ($jenisKaryawan) {
            $filename .= '_' . strtolower($jenisKaryawan);
        }
        if ($statusPegawai) {
            $filename .= '_' . strtolower($statusPegawai);
        }
        $filename .= '_' . date('YmdHis') . '.xlsx';
        
        return Excel::download(
            new \App\Exports\PengaturanBpjsKoperasiExport($jenisKaryawan, $statusPegawai),
            $filename
        );
    }
}
