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
        
        // Stats untuk semua user (permission-based, bukan role-based)
        $stats = [
            'total_karyawan' => Karyawan::count(),
            'active_karyawan' => Karyawan::where('status_karyawan', 'Aktif')->count(),
            'recent_karyawan' => Karyawan::latest()->take(5)->get(),
        ];
        
        // Tambahan stats untuk user dengan permission admin
        if ($user->hasPermission('users.view')) {
            $stats['total_users'] = User::count();
            $stats['total_roles'] = Role::count();
        }
        
        return view('dashboard.index', compact('stats'));
    }
}