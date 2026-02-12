<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_karyawan' => Karyawan::count(),
            'total_roles' => Role::count(),
            'active_karyawan' => Karyawan::where('status_pegawai', 'Aktif')->count(),
        ];

        return view('superadmin.dashboard.index', compact('stats'));
    }
}