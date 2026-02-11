<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::count();
        $activeKaryawan = Karyawan::where('status_pegawai', 'Aktif')->count();
        
        return view('user.dashboard.index', compact('totalKaryawan', 'activeKaryawan'));
    }
}