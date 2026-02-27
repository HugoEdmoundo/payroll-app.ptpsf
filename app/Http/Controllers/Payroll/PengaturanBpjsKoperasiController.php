<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanBpjsKoperasi;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PengaturanBpjsKoperasiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    
    public function index(Request $request)
    {
        $query = PengaturanBpjsKoperasi::query();
        
        // Filter by status pegawai
        if ($request->has('status_pegawai') && $request->status_pegawai) {
            $query->where('status_pegawai', $request->status_pegawai);
        }
        
        $pengaturanBpjsKoperasi = $query->orderBy('status_pegawai')->paginate(15);
        
        return view('payroll.pengaturan-bpjs-koperasi.index', compact('pengaturanBpjsKoperasi'));
    }

    public function create()
    {
        return view('payroll.pengaturan-bpjs-koperasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_pegawai' => 'required|string|in:Kontrak,OJT',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
            'bpjs_kematian' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique
        $exists = PengaturanBpjsKoperasi::where('status_pegawai', $request->status_pegawai)->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan BPJS & Koperasi untuk status pegawai ini sudah ada.');
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
        return view('payroll.pengaturan-bpjs-koperasi.edit', compact('pengaturanBpjsKoperasi'));
    }

    public function update(Request $request, PengaturanBpjsKoperasi $pengaturanBpjsKoperasi)
    {
        $request->validate([
            'status_pegawai' => 'required|string|in:Kontrak,OJT',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
            'bpjs_kematian' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique (except current)
        $exists = PengaturanBpjsKoperasi::where('status_pegawai', $request->status_pegawai)
            ->where('id', '!=', $pengaturanBpjsKoperasi->id)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan BPJS & Koperasi untuk status pegawai ini sudah ada.');
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
        $statusPegawai = $request->get('status_pegawai');
        
        $filename = 'pengaturan_bpjs_koperasi';
        if ($statusPegawai) {
            $filename .= '_' . strtolower($statusPegawai);
        }
        $filename .= '_' . date('YmdHis') . '.xlsx';
        
        return Excel::download(
            new \App\Exports\PengaturanBpjsKoperasiExport($statusPegawai),
            $filename
        );
    }
}
