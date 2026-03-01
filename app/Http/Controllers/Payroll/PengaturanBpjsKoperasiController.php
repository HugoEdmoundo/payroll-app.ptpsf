<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanBpjsKoperasi;
use Illuminate\Http\Request;

class PengaturanBpjsKoperasiController extends Controller
{
    /**
     * Show the form for editing the global BPJS & Koperasi configuration.
     * Since this is global data, we go directly to edit (no index/create needed)
     */
    public function edit()
    {
        // Get or create the global configuration
        $pengaturanBpjsKoperasi = PengaturanBpjsKoperasi::firstOrCreate(
            [],
            [
                'bpjs_kesehatan_pendapatan' => 0,
                'bpjs_kecelakaan_kerja_pendapatan' => 0,
                'bpjs_kematian_pendapatan' => 0,
                'bpjs_jht_pendapatan' => 0,
                'bpjs_jp_pendapatan' => 0,
                'koperasi' => 0,
            ]
        );
        
        return view('payroll.pengaturan-bpjs-koperasi.edit', compact('pengaturanBpjsKoperasi'));
    }

    /**
     * Update the global BPJS & Koperasi configuration.
     */
    public function update(Request $request)
    {
        $request->validate([
            'bpjs_kesehatan_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_kematian_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jht_pendapatan' => 'nullable|numeric|min:0',
            'bpjs_jp_pendapatan' => 'nullable|numeric|min:0',
            'koperasi' => 'nullable|numeric|min:0',
        ]);
        
        $pengaturanBpjsKoperasi = PengaturanBpjsKoperasi::first();
        
        if (!$pengaturanBpjsKoperasi) {
            $pengaturanBpjsKoperasi = PengaturanBpjsKoperasi::create($request->all());
        } else {
            $pengaturanBpjsKoperasi->update($request->all());
        }
        
        return redirect()->route('payroll.pengaturan-gaji.index')
            ->with('success', 'Pengaturan BPJS & Koperasi berhasil diupdate.');
    }
}
