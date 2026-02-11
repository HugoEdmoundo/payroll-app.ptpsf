<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Cek user active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }
        
        // Superadmin dashboard
        if ($user->role && $user->role->is_superadmin) {
            $stats = [
                'total_users' => User::count(),
                'total_karyawan' => Karyawan::count(),
                'active_karyawan' => Karyawan::where('status_pegawai', 'Aktif')->count(),
                'total_roles' => Role::count(),
            ];
            return view('dashboard.superadmin', compact('stats'));
        }
        
        // Regular user dashboard
        $stats = [
            'total_karyawan' => Karyawan::count(),
            'active_karyawan' => Karyawan::where('status_pegawai', 'Aktif')->count(),
            'recent_karyawan' => Karyawan::latest()->take(5)->get(),
        ];
        return view('dashboard.user', compact('stats'));
    }
}