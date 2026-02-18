<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanGaji;
use App\Models\MasterWilayah;
use Illuminate\Http\Request;

class PengaturanGajiController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanGaji::with('wilayah')->paginate(15);
        return view('payroll.pengaturan.index', compact('pengaturan'));
    }

    public function create()
    {
        $wilayah = MasterWilayah::where('is_active', true)->get();
        $jenisKaryawan = ['Konsultan', 'Organik', 'Teknisi', 'Borongan'];
        return view('payroll.pengaturan.create', compact('wilayah', 'jenisKaryawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_karyawan' => 'required|string',
            'jabatan' => 'required|string',
            'wilayah_id' => 'required|exists:master_wilayah,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_operasional' => 'nullable|numeric|min:0',
            'tunjangan_prestasi' => 'nullable|numeric|min:0',
            'tunjangan_konjungtur' => 'nullable|numeric|min:0',
            'benefit_ibadah' => 'nullable|numeric|min:0',
            'benefit_komunikasi' => 'nullable|numeric|min:0',
            'benefit_operasional' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
            'bpjs_kematian' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        // Calculate net_gaji, total_bpjs, nett
        $netGaji = $validated['gaji_pokok'] + 
                   ($validated['tunjangan_operasional'] ?? 0) + 
                   ($validated['tunjangan_prestasi'] ?? 0) + 
                   ($validated['tunjangan_konjungtur'] ?? 0);
        
        $totalBpjs = ($validated['bpjs_kesehatan'] ?? 0) + 
                     ($validated['bpjs_kecelakaan_kerja'] ?? 0) + 
                     ($validated['bpjs_kematian'] ?? 0) + 
                     ($validated['bpjs_jht'] ?? 0) + 
                     ($validated['bpjs_jp'] ?? 0);
        
        $validated['net_gaji'] = $netGaji;
        $validated['total_bpjs'] = $totalBpjs;
        $validated['nett'] = $netGaji + $totalBpjs;
        $validated['is_active'] = true;

        PengaturanGaji::create($validated);

        return redirect()->route('payroll.pengaturan.index')
            ->with('success', 'Pengaturan gaji berhasil ditambahkan');
    }

    public function show(PengaturanGaji $pengaturan)
    {
        $pengaturan->load('wilayah');
        return view('payroll.pengaturan.show', compact('pengaturan'));
    }

    public function edit(PengaturanGaji $pengaturan)
    {
        $wilayah = MasterWilayah::where('is_active', true)->get();
        $jenisKaryawan = ['Konsultan', 'Organik', 'Teknisi', 'Borongan'];
        return view('payroll.pengaturan.edit', compact('pengaturan', 'wilayah', 'jenisKaryawan'));
    }

    public function update(Request $request, PengaturanGaji $pengaturan)
    {
        $validated = $request->validate([
            'jenis_karyawan' => 'required|string',
            'jabatan' => 'required|string',
            'wilayah_id' => 'required|exists:master_wilayah,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_operasional' => 'nullable|numeric|min:0',
            'tunjangan_prestasi' => 'nullable|numeric|min:0',
            'tunjangan_konjungtur' => 'nullable|numeric|min:0',
            'benefit_ibadah' => 'nullable|numeric|min:0',
            'benefit_komunikasi' => 'nullable|numeric|min:0',
            'benefit_operasional' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
            'bpjs_kematian' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string'
        ]);

        // Recalculate
        $netGaji = $validated['gaji_pokok'] + 
                   ($validated['tunjangan_operasional'] ?? 0) + 
                   ($validated['tunjangan_prestasi'] ?? 0) + 
                   ($validated['tunjangan_konjungtur'] ?? 0);
        
        $totalBpjs = ($validated['bpjs_kesehatan'] ?? 0) + 
                     ($validated['bpjs_kecelakaan_kerja'] ?? 0) + 
                     ($validated['bpjs_kematian'] ?? 0) + 
                     ($validated['bpjs_jht'] ?? 0) + 
                     ($validated['bpjs_jp'] ?? 0);
        
        $validated['net_gaji'] = $netGaji;
        $validated['total_bpjs'] = $totalBpjs;
        $validated['nett'] = $netGaji + $totalBpjs;

        $pengaturan->update($validated);

        return redirect()->route('payroll.pengaturan.index')
            ->with('success', 'Pengaturan gaji berhasil diupdate');
    }

    public function destroy(PengaturanGaji $pengaturan)
    {
        $pengaturan->delete();
        return redirect()->route('payroll.pengaturan.index')
            ->with('success', 'Pengaturan gaji berhasil dihapus');
    }
}
