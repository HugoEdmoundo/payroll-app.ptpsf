<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\KasbonCicilan;
use App\Services\LoanService;
use App\Traits\GlobalSearchable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasbonController extends Controller
{
    use \App\Traits\LogsActivity, GlobalSearchable;

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

        // Global search
        if ($request->has('search') && $request->search != '') {
            $query = $this->applyGlobalSearch($query, $request->search, [
                'periode',
                'deskripsi',
                'metode_pembayaran',
                'status_pembayaran',
            ], [
                'karyawan' => ['nama_karyawan', 'jenis_karyawan', 'jabatan', 'lokasi_kerja'],
            ]);
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

        // Cek duplikat: satu karyawan hanya boleh punya 1 kasbon per periode
        $exists = Kasbon::where('id_karyawan', $request->id_karyawan)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'Kasbon untuk karyawan ini pada periode tersebut sudah ada.'])->withInput();
        }

        $kasbon = new Kasbon($request->all());
        app(LoanService::class)->calculateFields($kasbon);
        $kasbon->save();

        // If Cicilan method, create cicilan records
        if ($kasbon->metode_pembayaran === 'Cicilan' && $kasbon->jumlah_cicilan > 0) {
            $nominalPerCicilan = $kasbon->nominal / $kasbon->jumlah_cicilan;
            $startPeriode = Carbon::createFromFormat('Y-m', $kasbon->periode);

            for ($i = 1; $i <= $kasbon->jumlah_cicilan; $i++) {
                $cicilanPeriode = $startPeriode->copy()->addMonths($i - 1)->format('Y-m');

                KasbonCicilan::create([
                    'id_kasbon' => $kasbon->id_kasbon,
                    'cicilan_ke' => $i,
                    'periode' => $cicilanPeriode,
                    'nominal_cicilan' => $nominalPerCicilan,
                    'status' => 'Pending',
                ]);
            }
        }

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

        $kasbon->fill($request->all());
        app(LoanService::class)->calculateFields($kasbon);
        $kasbon->save();

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
            'jumlah_bayar' => 'required|integer|min:1|max:'.($kasbon->jumlah_cicilan - $kasbon->cicilan_terbayar),
        ]);

        $kasbon->cicilan_terbayar += $request->jumlah_bayar;
        app(LoanService::class)->calculateFields($kasbon);
        $kasbon->save();

        return back()->with('success', 'Pembayaran cicilan berhasil dicatat.');
    }

    public function export(Request $request)
    {
        $periode = $request->get('periode');
        $filename = 'kasbon_'.($periode ?? 'all').'_'.date('YmdHis').'.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\KasbonExport($periode),
            $filename
        );
    }
}
