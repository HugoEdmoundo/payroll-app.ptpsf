<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::paginate(10);
        return view('superadmin.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('superadmin.karyawan.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'join_date' => 'required|date',
            'jabatan' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'jenis_karyawan' => 'required|string|max:50',
            'status_pegawai' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string|max:50',
            'status_karyawan' => 'required|string|max:50',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('superadmin.karyawan.index')->with('success', 'Karyawan created successfully.');
    }

    public function show(Karyawan $karyawan)
    {
        return view('superadmin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $settings = $this->getSettings();
        return view('superadmin.karyawan.edit', compact('karyawan', 'settings'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'join_date' => 'required|date',
            'jabatan' => 'required|string|max:100',
            'lokasi_kerja' => 'required|string|max:100',
            'jenis_karyawan' => 'required|string|max:50',
            'status_pegawai' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:20',
            'bank' => 'required|string|max:50',
            'status_karyawan' => 'required|string|max:50',
        ]);

        $karyawan->update($request->all());

        return redirect()->route('superadmin.karyawan.index')->with('success', 'Karyawan updated successfully.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('superadmin.karyawan.index')->with('success', 'Karyawan deleted successfully.');
    }

    public function import()
    {
        return view('superadmin.karyawan.import');
    }

    public function export()
    {
        // Export logic here
        return back()->with('success', 'Export functionality to be implemented.');
    }

    private function getSettings()
    {
        return [
            'jenis_karyawan' => SystemSetting::getOptions('jenis_karyawan'),
            'status_pegawai' => SystemSetting::getOptions('status_pegawai'),
            'status_karyawan' => SystemSetting::getOptions('status_karyawan'),
            'bank_options' => SystemSetting::getOptions('bank_options'),
            'jabatan_options' => SystemSetting::getOptions('jabatan_options'),
        ];
    }
}