<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('karyawan');

        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%");
            });
        }

        $absensiList = $query->orderBy('periode', 'desc')
                            ->orderBy('id_absensi', 'desc')
                            ->paginate(15);

        return view('payroll.absensi.index', compact('absensiList'));
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.absensi.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'hadir' => 'required|integer|min:0',
            'on_site' => 'nullable|integer|min:0',
            'absence' => 'nullable|integer|min:0',
            'idle_rest' => 'nullable|integer|min:0',
            'izin_sakit_cuti' => 'nullable|integer|min:0',
            'tanpa_keterangan' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $exists = Absensi::where('id_karyawan', $request->id_karyawan)
                        ->where('periode', $request->periode)
                        ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'Absensi untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        Absensi::create($request->all());

        return redirect()->route('payroll.absensi.index')
                        ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function show(Absensi $absensi)
    {
        $absensi->load('karyawan');
        return view('payroll.absensi.show', compact('absensi'));
    }

    public function edit(Absensi $absensi)
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.absensi.edit', compact('absensi', 'karyawanList'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'hadir' => 'required|integer|min:0',
            'on_site' => 'nullable|integer|min:0',
            'absence' => 'nullable|integer|min:0',
            'idle_rest' => 'nullable|integer|min:0',
            'izin_sakit_cuti' => 'nullable|integer|min:0',
            'tanpa_keterangan' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $exists = Absensi::where('id_karyawan', $request->id_karyawan)
                        ->where('periode', $request->periode)
                        ->where('id_absensi', '!=', $absensi->id_absensi)
                        ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'Absensi untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        $absensi->update($request->all());

        return redirect()->route('payroll.absensi.index')
                        ->with('success', 'Data absensi berhasil diupdate.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('payroll.absensi.index')
                        ->with('success', 'Data absensi berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'absensi_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiExport($periode),
            $filename
        );
    }

    public function import()
    {
        return view('payroll.absensi.import');
    }

    public function downloadTemplate()
    {
        $filename = 'template_absensi_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AbsensiTemplateExport(),
            $filename
        );
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\AbsensiImport,
                $request->file('file')
            );

            return redirect()->route('payroll.absensi.index')
                            ->with('success', 'Data absensi berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
