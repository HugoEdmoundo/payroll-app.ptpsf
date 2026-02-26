<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\PengaturanGaji;
use App\Models\PengaturanGajiStatusPegawai;
use App\Models\SystemSetting;
use App\Traits\GlobalSearchable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PengaturanGajiController extends Controller
{
    use GlobalSearchable, \App\Traits\LogsActivity;
    public function index(Request $request)
    {
        $query = PengaturanGaji::query();
        
        // Filter by jenis karyawan
        if ($request->has('jenis_karyawan') && $request->jenis_karyawan) {
            $query->where('jenis_karyawan', $request->jenis_karyawan);
        }
        
        // Global search using trait
        if ($request->has('search') && $request->search) {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'jenis_karyawan', 'jabatan', 'lokasi_kerja'
            ]);
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

    public function export(Request $request)
    {
        $jenisKaryawan = $request->get('jenis_karyawan');
        $filename = 'pengaturan_gaji_' . ($jenisKaryawan ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PengaturanGajiExport($jenisKaryawan),
            $filename
        );
    }
    
    // ========== STATUS PEGAWAI METHODS ==========
    
    public function indexStatusPegawai(Request $request)
    {
        \Log::info('indexStatusPegawai called', ['user' => auth()->id(), 'request' => $request->all()]);
        
        $query = PengaturanGajiStatusPegawai::query();
        
        // Filter by status pegawai
        if ($request->has('status_pegawai') && $request->status_pegawai) {
            $query->where('status_pegawai', $request->status_pegawai);
        }
        
        // Global search
        if ($request->has('search') && $request->search) {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'status_pegawai', 'jabatan', 'lokasi_kerja'
            ]);
        }
        
        $pengaturanGaji = $query->orderBy('status_pegawai')
                                ->orderBy('jabatan')
                                ->paginate(15);
        
        return view('payroll.pengaturan-gaji.status-pegawai.index', compact('pengaturanGaji'));
    }
    
    public function createStatusPegawai()
    {
        $settings = [
            'status_pegawai' => ['Harian', 'OJT'], // Only Harian and OJT, Kontrak = Normal
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
            'lokasi_kerja' => SystemSetting::getOptions('lokasi_kerja'),
        ];
        
        return view('payroll.pengaturan-gaji.status-pegawai.create', compact('settings'));
    }
    
    public function storeStatusPegawai(Request $request)
    {
        $request->validate([
            'status_pegawai' => 'required|string|in:Harian,OJT', // Only Harian and OJT
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);
        
        // Check unique
        $exists = PengaturanGajiStatusPegawai::where('status_pegawai', $request->status_pegawai)
            ->where('jabatan', $request->jabatan)
            ->where('lokasi_kerja', $request->lokasi_kerja)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan gaji untuk kombinasi ini sudah ada.');
        }
        
        PengaturanGajiStatusPegawai::create($request->all());
        
        return redirect()->route('payroll.pengaturan-gaji.status-pegawai.index', ['status_pegawai' => $request->status_pegawai])
            ->with('success', 'Pengaturan gaji status pegawai berhasil ditambahkan.');
    }
    
    public function showStatusPegawai($id)
    {
        $pengaturanGaji = PengaturanGajiStatusPegawai::findOrFail($id);
        return view('payroll.pengaturan-gaji.status-pegawai.show', compact('pengaturanGaji'));
    }
    
    public function editStatusPegawai($id)
    {
        $pengaturanGaji = PengaturanGajiStatusPegawai::findOrFail($id);
        $settings = [
            'status_pegawai' => ['Harian', 'OJT'], // Only Harian and OJT
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
            'lokasi_kerja' => SystemSetting::getOptions('lokasi_kerja'),
        ];
        
        return view('payroll.pengaturan-gaji.status-pegawai.edit', compact('pengaturanGaji', 'settings'));
    }
    
    public function updateStatusPegawai(Request $request, $id)
    {
        $pengaturanGaji = PengaturanGajiStatusPegawai::findOrFail($id);
        
        $request->validate([
            'status_pegawai' => 'required|string|in:Harian,OJT', // Only Harian and OJT
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);
        
        // Check unique (except current)
        $exists = PengaturanGajiStatusPegawai::where('status_pegawai', $request->status_pegawai)
            ->where('jabatan', $request->jabatan)
            ->where('lokasi_kerja', $request->lokasi_kerja)
            ->where('id_pengaturan', '!=', $id)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pengaturan gaji untuk kombinasi ini sudah ada.');
        }
        
        $pengaturanGaji->update($request->all());
        
        return redirect()->route('payroll.pengaturan-gaji.status-pegawai.index', ['status_pegawai' => $request->status_pegawai])
            ->with('success', 'Pengaturan gaji status pegawai berhasil diupdate.');
    }
    
    public function destroyStatusPegawai($id)
    {
        $pengaturanGaji = PengaturanGajiStatusPegawai::findOrFail($id);
        $statusPegawai = $pengaturanGaji->status_pegawai;
        $pengaturanGaji->delete();
        
        return redirect()->route('payroll.pengaturan-gaji.status-pegawai.index', ['status_pegawai' => $statusPegawai])
            ->with('success', 'Pengaturan gaji status pegawai berhasil dihapus.');
    }
    
    public function exportStatusPegawai(Request $request)
    {
        $statusPegawai = $request->status_pegawai;
        $filename = 'pengaturan-gaji-status-pegawai-' . ($statusPegawai ?? 'all') . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new \App\Exports\PengaturanGajiStatusPegawaiExport($statusPegawai), $filename);
    }
}
