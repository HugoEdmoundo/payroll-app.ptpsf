<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Kasbon;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KasbonController extends Controller
{
    public function index(Request $request)
    {
        $query = Kasbon::with('karyawan');

        if ($request->has('periode') && $request->periode != '') {
            $query->where('periode', $request->periode);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status_pembayaran', $request->status);
        }

        if ($request->has('metode') && $request->metode != '') {
            $query->where('metode_pembayaran', $request->metode);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('karyawan', function($q2) use ($search) {
                      $q2->where('nama_karyawan', 'like', "%{$search}%");
                  });
            });
        }

        $kasbonList = $query->orderBy('tanggal_pengajuan', 'desc')
                           ->paginate(15);

        return view('payroll.kasbon.index', compact('kasbonList'));
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.kasbon.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:Langsung,Cicilan',
            'jumlah_cicilan' => 'required_if:metode_pembayaran,Cicilan|nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Kasbon::create($request->all());

        return redirect()->route('payroll.kasbon.index')
                        ->with('success', 'Data kasbon berhasil ditambahkan.');
    }

    public function show(Kasbon $kasbon)
    {
        $kasbon->load('karyawan');
        return view('payroll.kasbon.show', compact('kasbon'));
    }

    public function edit(Kasbon $kasbon)
    {
        $karyawanList = Karyawan::where('status_karyawan', 'Active')
                               ->orderBy('nama_karyawan')
                               ->get();
        
        return view('payroll.kasbon.edit', compact('kasbon', 'karyawanList'));
    }

    public function update(Request $request, Kasbon $kasbon)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:Langsung,Cicilan',
            'status_pembayaran' => 'required|in:Pending,Lunas',
            'jumlah_cicilan' => 'required_if:metode_pembayaran,Cicilan|nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $kasbon->update($request->all());

        return redirect()->route('payroll.kasbon.index')
                        ->with('success', 'Data kasbon berhasil diupdate.');
    }

    public function destroy(Kasbon $kasbon)
    {
        $kasbon->delete();

        return redirect()->route('payroll.kasbon.index')
                        ->with('success', 'Data kasbon berhasil dihapus.');
    }

    public function bayarCicilan(Request $request, Kasbon $kasbon)
    {
        if ($kasbon->metode_pembayaran !== 'Cicilan') {
            return back()->with('error', 'Kasbon ini bukan metode cicilan.');
        }

        if ($kasbon->status_pembayaran === 'Lunas') {
            return back()->with('error', 'Kasbon ini sudah lunas.');
        }

        $request->validate([
            'jumlah_bayar' => 'required|integer|min:1|max:' . ($kasbon->jumlah_cicilan - $kasbon->cicilan_terbayar),
        ]);

        $kasbon->cicilan_terbayar += $request->jumlah_bayar;
        $kasbon->save(); // Will trigger auto-calculation in model

        return back()->with('success', 'Pembayaran cicilan berhasil dicatat.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'kasbon_' . ($periode ?? 'all') . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KasbonExport($periode),
            $filename
        );
    }
}
