<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\NKI;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class NKIController extends Controller
{
    public function index()
    {
        $nki = NKI::with('karyawan')->orderBy('periode', 'desc')->paginate(15);
        return view('payroll.nki.index', compact('nki'));
    }

    public function create()
    {
        $karyawan = Karyawan::where('status', 'Aktif')->get();
        return view('payroll.nki.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
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
    }

    public function show(NKI $nki)
    {
        $nki->load('karyawan', 'penilai');
        return view('payroll.nki.show', compact('nki'));
    }

    public function edit(NKI $nki)
    {
        $karyawan = Karyawan::where('status', 'Aktif')->get();
        return view('payroll.nki.edit', compact('nki', 'karyawan'));
    }

    public function update(Request $request, NKI $nki)
    {
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
    }

    public function destroy(NKI $nki)
    {
        $nki->delete();
        return redirect()->route('payroll.nki.index')->with('success', 'NKI berhasil dihapus');
    }
}
