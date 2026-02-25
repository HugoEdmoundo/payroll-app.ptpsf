<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\NKI;
use App\Models\Karyawan;
use App\Traits\GlobalSearchable;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NKIController extends Controller
{
    use GlobalSearchable, LogsActivity;

    public function index(Request $request)
    {
        $query = NKI::with('karyawan');

        // Filter by periode
        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        // Global search
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'periode',
                'keterangan',
            ], [
                'karyawan' => ['nama_karyawan', 'jenis_karyawan', 'jabatan', 'lokasi_kerja']
            ]);
        }

        $nkiList = $query->orderBy('periode', 'desc')
                        ->orderBy('id_nki', 'desc')
                        ->paginate(15);

        return view('payroll.nki.index', compact('nkiList'));
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.nki.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'kontribusi_1' => 'required|numeric|min:0|max:10',
            'kontribusi_2' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'keterangan' => 'nullable|string',
        ]);

        // Check if NKI already exists for this employee and period
        $exists = NKI::where('id_karyawan', $request->id_karyawan)
                    ->where('periode', $request->periode)
                    ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'NKI untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        NKI::create($request->all());
        
        $this->logCreate('NKI', "Periode {$request->periode}");

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil ditambahkan.');
    }

    public function show(NKI $nki)
    {
        $nki->load('karyawan');
        return view('payroll.nki.show', compact('nki'));
    }

    public function edit(NKI $nki)
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.nki.edit', compact('nki', 'karyawanList'));
    }

    public function update(Request $request, NKI $nki)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'kontribusi_1' => 'required|numeric|min:0|max:10',
            'kontribusi_2' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'keterangan' => 'nullable|string',
        ]);

        // Check if NKI already exists for this employee and period (excluding current record)
        $exists = NKI::where('id_karyawan', $request->id_karyawan)
                    ->where('periode', $request->periode)
                    ->where('id_nki', '!=', $nki->id_nki)
                    ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'NKI untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        $nki->update($request->all());
        
        $this->logUpdate('NKI', "Periode {$request->periode}");

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil diupdate.');
    }

    public function destroy(NKI $nki)
    {
        $periode = $nki->periode;
        $nki->delete();
        
        $this->logDelete('NKI', "Periode {$periode}");

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'nki_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        $this->logExport('NKI');
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\NKIExport($periode),
            $filename
        );
    }

    public function import()
    {
        return view('payroll.nki.import');
    }

    public function downloadTemplate()
    {
        $filename = 'template_nki_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\NKITemplateExport(),
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
                new \App\Imports\NKIImport,
                $request->file('file')
            );
            
            $this->logImport('NKI');

            return redirect()->route('payroll.nki.index')
                            ->with('success', 'Data NKI berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
