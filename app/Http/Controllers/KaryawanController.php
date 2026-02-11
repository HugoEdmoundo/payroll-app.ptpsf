<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::paginate(10);
        return view('karyawan.index', compact('karyawan'));
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
            'join_date' => 'required|date',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'jenis_karyawan' => 'required|string',
            'status_pegawai' => 'required|string',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string',
            'status_karyawan' => 'required|string',
        ]);

        // HAPUS semua logic join_date, biarkan boot method yang handle
        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan created successfully.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        if (!Auth::user()->hasPermission('karyawan.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'join_date' => 'required|date',
            'jabatan' => 'required|string',
            'lokasi_kerja' => 'required|string',
            'jenis_karyawan' => 'required|string',
            'status_pegawai' => 'required|string',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string',
            'status_karyawan' => 'required|string',
        ]);

        // HAPUS semua logic join_date, biarkan boot method yang handle
        $karyawan->update($request->all());

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

        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan deleted successfully.');
    }

    public function import()
    {
        if (!Auth::user()->hasPermission('karyawan.import')) {
            abort(403, 'Unauthorized action.');
        }

        return view('karyawan.import');
    }

    public function export()
    {
        if (!Auth::user()->hasPermission('karyawan.export')) {
            abort(403, 'Unauthorized action.');
        }

        return back()->with('success', 'Export functionality to be implemented.');
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