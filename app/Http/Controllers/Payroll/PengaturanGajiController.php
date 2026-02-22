<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanGaji;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class PengaturanGajiController extends Controller
{
    public function index(Request $request)
    {
        $query = PengaturanGaji::query();
        
        // Filter by jenis karyawan
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan) {
            $query->where('jenis_karyawan', $request->jenis_karyawan);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('jabatan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi_kerja', 'like', '%' . $request->search . '%');
            });
        }
        
        $pengaturanGaji = $query->orderBy('jenis_karyawan')
                                ->orderBy('jabatan')
                                ->paginate(15);
        
        return view('payroll.pengaturan-gaji.index', compact('pengaturanGaji'));
    }

    public function create(Request $request)
    {
        $settings = [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
            'lokasi_kerja' => SystemSetting::getOptions('lokasi_kerja'),
        ];
        
        return view('payroll.pengaturan-gaji.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_karyawan' => 'required|string',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_operasional' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique
        $exists = PengaturanGaji::where('jenis_karyawan', $request->jenis_karyawan)
            ->where('jabatan', $request->jabatan)
            ->where('lokasi_kerja', $request->lokasi_kerja)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan gaji untuk kombinasi ini sudah ada.');
        }
        
        PengaturanGaji::create($request->all());
        
        return redirect()->route('payroll.pengaturan-gaji.index', ['jenis_karyawan' => $request->jenis_karyawan])
            ->with('success', 'Pengaturan gaji berhasil ditambahkan.');
    }

    public function show(PengaturanGaji $pengaturanGaji)
    {
        return view('payroll.pengaturan-gaji.show', compact('pengaturanGaji'));
    }

    public function edit(PengaturanGaji $pengaturanGaji)
    {
        $settings = [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
            'lokasi_kerja' => SystemSetting::getOptions('lokasi_kerja'),
        ];
        
        return view('payroll.pengaturan-gaji.edit', compact('pengaturanGaji', 'settings'));
    }

    public function update(Request $request, PengaturanGaji $pengaturanGaji)
    {
        $request->validate([
            'jenis_karyawan' => 'required|string',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_operasional' => 'nullable|numeric|min:0',
            'potongan_koperasi' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_ketenagakerjaan' => 'nullable|numeric|min:0',
            'bpjs_kecelakaan_kerja' => 'nullable|numeric|min:0',
        ]);
        
        // Check unique (except current)
        $exists = PengaturanGaji::where('jenis_karyawan', $request->jenis_karyawan)
            ->where('jabatan', $request->jabatan)
            ->where('lokasi_kerja', $request->lokasi_kerja)
            ->where('id_pengaturan', '!=', $pengaturanGaji->id_pengaturan)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan gaji untuk kombinasi ini sudah ada.');
        }
        
        $pengaturanGaji->update($request->all());
        
        return redirect()->route('payroll.pengaturan-gaji.index', ['jenis_karyawan' => $request->jenis_karyawan])
            ->with('success', 'Pengaturan gaji berhasil diupdate.');
    }

    public function destroy(PengaturanGaji $pengaturanGaji)
    {
        $jenisKaryawan = $pengaturanGaji->jenis_karyawan;
        $pengaturanGaji->delete();
        
        return redirect()->route('payroll.pengaturan-gaji.index', ['jenis_karyawan' => $jenisKaryawan])
            ->with('success', 'Pengaturan gaji berhasil dihapus.');
    }
}
