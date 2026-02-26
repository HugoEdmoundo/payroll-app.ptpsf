<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\SystemSetting;
use App\Imports\KaryawanImport;
use App\Exports\KaryawanExport;
use App\Traits\GlobalSearchable;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    use GlobalSearchable, LogsActivity;

    public function index(Request $request)
    {
        $query = Karyawan::query();

        // Global search
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'nama_karyawan',
                'email',
                'no_telp',
                'jabatan',
                'lokasi_kerja',
                'jenis_karyawan',
                'status_pegawai',
                'status_karyawan',
                'bank',
                'no_rekening',
                'npwp',
            ]);
        }

        $karyawan = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Stats
        $stats = [
            'total' => Karyawan::count(),
            'active' => Karyawan::where('status_karyawan', 'Active')->count(),
            'non_active' => Karyawan::where('status_karyawan', 'Non-Active')->count(),
            'resign' => Karyawan::where('status_karyawan', 'Resign')->count(),
        ];
        
        return view('karyawan.index', compact('karyawan', 'stats'));
    }

    public function create()
    {
        if (!Auth::user()->hasPermission('karyawan.create')) {
            abort(403, 'Unauthorized action.');
        }

        $settings = $this->getSettings();
        return view('karyawan.create', compact('settings'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('karyawan.create')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',  
            'join_date' => 'required|date',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'jenis_karyawan' => 'required|string',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string',
            'status_karyawan' => 'required|string',
        ]);

        // Check for duplicate by nama_karyawan
        $exists = Karyawan::where('nama_karyawan', $request->nama_karyawan)->first();
        
        if ($exists) {
            // Check if data is similar (only different in 1 field)
            $differences = 0;
            $diffFields = [];
            
            $fieldsToCheck = [
                'email', 'no_telp', 'jenis_karyawan', 'jabatan', 
                'lokasi_kerja', 'bank', 'no_rekening'
            ];
            
            foreach ($fieldsToCheck as $field) {
                if ($request->$field && $exists->$field != $request->$field) {
                    $differences++;
                    $diffFields[] = $field;
                }
            }
            
            // If only 1 difference, show warning
            if ($differences == 1) {
                return back()->withInput()->with('warning', 
                    "Data mirip dengan karyawan existing: {$exists->nama_karyawan}, berbeda di: " . implode(', ', $diffFields)
                );
            }
            
            // If exact duplicate or multiple differences
            return back()->withInput()->with('error', 
                "Karyawan dengan nama '{$request->nama_karyawan}' sudah ada dalam sistem."
            );
        }

        // HAPUS semua logic join_date, biarkan boot method yang handle
        $karyawan = Karyawan::create($request->all());
        
        $this->logCreate('Karyawan', $karyawan->nama_karyawan);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan created successfully.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        if (!Auth::user()->hasPermission('karyawan.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'join_date' => 'required|date',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'jenis_karyawan' => 'required|string',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string',
            'status_karyawan' => 'required|string',
        ]);

        $karyawan->update($request->all());
        
        $this->logUpdate('Karyawan', $karyawan->nama_karyawan);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully.');
    }

    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        if (!Auth::user()->hasPermission('karyawan.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $settings = $this->getSettings();
        return view('karyawan.edit', compact('karyawan', 'settings'));
    }

    public function destroy(Karyawan $karyawan)
    {
        if (!Auth::user()->hasPermission('karyawan.delete')) {
            abort(403, 'Unauthorized action.');
        }

        $namaKaryawan = $karyawan->nama_karyawan;
        $karyawan->delete();
        
        $this->logDelete('Karyawan', $namaKaryawan);
        
        return redirect()->route('karyawan.index')->with('success', 'Karyawan deleted successfully.');
    }

    public function import()
    {
        if (!Auth::user()->hasPermission('karyawan.import')) {
            abort(403, 'Unauthorized action.');
        }

        return view('karyawan.import');
    }

    public function downloadTemplate()
    {
        $filename = 'template_karyawan_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KaryawanTemplateExport(),
            $filename
        );
    }

    public function export()
    {
        if (!Auth::user()->hasPermission('karyawan.export')) {
            abort(403, 'Unauthorized action.');
        }

        $filename = 'karyawan_' . date('YmdHis') . '.xlsx';
        
        $this->logExport('Karyawan');
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KaryawanExport(),
            $filename
        );
    }

    public function importStore(Request $request)
    {
        if (!Auth::user()->hasPermission('karyawan.import')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\KaryawanImport,
                $request->file('file')
            );
            
            $this->logImport('Karyawan');

            return redirect()->route('karyawan.index')
                            ->with('success', 'Data karyawan berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    private function getSettings()
    {
        return [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'status_pegawai' => SystemSetting::getOptions('status_pegawai'),
            'status_karyawan' => SystemSetting::getOptions('status_karyawan'),
            'status_perkawinan' => SystemSetting::getOptions('status_perkawinan'),
            'lokasi_kerja' => SystemSetting::getOptions('lokasi_kerja'),
            'bank_options' => SystemSetting::getOptions('bank_options'),
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
        ];
    }
}