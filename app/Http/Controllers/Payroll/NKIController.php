<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\NKI;
use App\Models\Karyawan;
use Illuminate\Http\Request;
<<<<<<< HEAD

class NKIController extends Controller
{
    public function index()
    {
        $nki = NKI::with('karyawan')->orderBy('periode', 'desc')->paginate(15);
        return view('payroll.nki.index', compact('nki'));
=======
use Illuminate\Support\Facades\Auth;

class NKIController extends Controller
{
    public function index(Request $request)
    {
        $query = NKI::with('karyawan');

        // Filter by periode
        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama_karyawan', 'like', "%{$search}%");
            });
        }

        $nkiList = $query->orderBy('periode', 'desc')
                        ->orderBy('id_nki', 'desc')
                        ->paginate(15);

        return view('payroll.nki.index', compact('nkiList'));
>>>>>>> fitur-baru
    }

    public function create()
    {
<<<<<<< HEAD
        $karyawan = Karyawan::where('status', 'Aktif')->get();
        return view('payroll.nki.create', compact('karyawan'));
=======
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.nki.create', compact('karyawanList'));
>>>>>>> fitur-baru
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|string',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'konstribusi' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'lainnya' => 'required|numeric|min:0|max:10',
            'catatan' => 'nullable|string'
        ]);

        $validated['nilai_nki'] = NKI::hitungNKI(
            $validated['kemampuan'],
            $validated['konstribusi'],
            $validated['kedisiplinan'],
            $validated['lainnya']
        );
        
        $validated['persentase_prestasi'] = NKI::hitungPersentasePrestasi($validated['nilai_nki']);
        $validated['dinilai_oleh'] = auth()->id();

        NKI::create($validated);

        return redirect()->route('payroll.nki.index')->with('success', 'NKI berhasil ditambahkan');
=======
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'kontribusi' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'lainnya' => 'required|numeric|min:0|max:10',
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

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil ditambahkan.');
>>>>>>> fitur-baru
    }

    public function show(NKI $nki)
    {
<<<<<<< HEAD
        $nki->load('karyawan', 'penilai');
=======
        $nki->load('karyawan');
>>>>>>> fitur-baru
        return view('payroll.nki.show', compact('nki'));
    }

    public function edit(NKI $nki)
    {
<<<<<<< HEAD
        $karyawan = Karyawan::where('status', 'Aktif')->get();
        return view('payroll.nki.edit', compact('nki', 'karyawan'));
=======
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.nki.edit', compact('nki', 'karyawanList'));
>>>>>>> fitur-baru
    }

    public function update(Request $request, NKI $nki)
    {
<<<<<<< HEAD
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|string',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'konstribusi' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'lainnya' => 'required|numeric|min:0|max:10',
            'catatan' => 'nullable|string'
        ]);

        $validated['nilai_nki'] = NKI::hitungNKI(
            $validated['kemampuan'],
            $validated['konstribusi'],
            $validated['kedisiplinan'],
            $validated['lainnya']
        );
        
        $validated['persentase_prestasi'] = NKI::hitungPersentasePrestasi($validated['nilai_nki']);

        $nki->update($validated);

        return redirect()->route('payroll.nki.index')->with('success', 'NKI berhasil diupdate');
=======
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'kemampuan' => 'required|numeric|min:0|max:10',
            'kontribusi' => 'required|numeric|min:0|max:10',
            'kedisiplinan' => 'required|numeric|min:0|max:10',
            'lainnya' => 'required|numeric|min:0|max:10',
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

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil diupdate.');
>>>>>>> fitur-baru
    }

    public function destroy(NKI $nki)
    {
        $nki->delete();
<<<<<<< HEAD
        return redirect()->route('payroll.nki.index')->with('success', 'NKI berhasil dihapus');
=======

        return redirect()->route('payroll.nki.index')
                        ->with('success', 'Data NKI berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'nki_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\NKIExport($periode),
            $filename
        );
    }

    public function import()
    {
        return view('payroll.nki.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\NKIImport,
                $request->file('file')
            );

            return redirect()->route('payroll.nki.index')
                            ->with('success', 'Data NKI berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
>>>>>>> fitur-baru
    }
}
