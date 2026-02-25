<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\HitungGaji;
use Carbon\Carbon;

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
        
        // Check if superadmin
        $isSuperadmin = $user->role && $user->role->name === 'Superadmin';
        
        if ($isSuperadmin) {
            return $this->superadminDashboard();
        } else {
            return $this->userDashboard();
        }
    }
    
    private function superadminDashboard()
    {
        $periode = \Carbon\Carbon::now()->format('Y-m');
        
        $stats = [
            'total_karyawan' => Karyawan::count(),
            'active_karyawan' => Karyawan::where('status_karyawan', 'Active')->count(),
            'total_users' => User::count(),
            'total_roles' => \App\Models\Role::count(),
        ];
        
        // Recent activities (last 10) - with error handling
        try {
            $recentActivities = ActivityLog::with('user')
                                          ->where('user_id', '!=', auth()->id())
                                          ->latest()
                                          ->take(10)
                                          ->get();
        } catch (\Exception $e) {
            // If activity_logs table doesn't exist yet or any error, return empty collection
            \Log::error('Failed to fetch activity logs: ' . $e->getMessage());
            $recentActivities = collect();
        }
        
        // Managed users (users created by this superadmin or all users)
        try {
            $managedUsers = User::with('role')
                               ->where('id', '!=', auth()->id())
                               ->latest()
                               ->take(5)
                               ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to fetch managed users: ' . $e->getMessage());
            $managedUsers = collect();
        }
        
        return view('dashboard.superadmin', compact('stats', 'recentActivities', 'managedUsers', 'periode'));
    }
    
    private function userDashboard()
    {
        $periode = Carbon::now()->format('Y-m');
        
        // Get latest hitung gaji data for current periode
        $hitungGajiData = HitungGaji::with('karyawan')
                                   ->where('periode', $periode)
                                   ->get();
        
        // Calculate stats
        $stats = [
            'total_karyawan' => Karyawan::where('status_karyawan', 'Active')->count(),
        ];
        
        // Pengeluaran Gaji Bersih Teknisi dan Borongan
        $teknisiBorongan = $hitungGajiData->filter(function($item) {
            return in_array($item->karyawan->jenis_karyawan ?? '', ['Teknisi', 'Borongan']);
        })->sum('gaji_bersih');
        
        // Pengeluaran Konsultan dan Organik
        $konsultanOrganik = $hitungGajiData->filter(function($item) {
            return in_array($item->karyawan->jenis_karyawan ?? '', ['Konsultan', 'Organik']);
        })->sum('gaji_bersih');
        
        // Pengeluaran BPJS (semua total nett BPJS)
        $totalBPJS = $hitungGajiData->sum(function($item) {
            return ($item->bpjs_kesehatan_pendapatan ?? 0) + 
                   ($item->bpjs_kecelakaan_kerja_pendapatan ?? 0) + 
                   ($item->bpjs_kematian_pendapatan ?? 0) + 
                   ($item->bpjs_jht_pendapatan ?? 0) + 
                   ($item->bpjs_jp_pendapatan ?? 0);
        });
        
        // Tagihan Koperasi
        $totalKoperasi = $hitungGajiData->sum('koperasi');
        
        // Total Pengeluaran
        $stats['total_pengeluaran'] = $teknisiBorongan + $konsultanOrganik + $totalBPJS + $totalKoperasi;
        
        $pengeluaran = [
            'teknisi_borongan' => $teknisiBorongan,
            'konsultan_organik' => $konsultanOrganik,
            'bpjs' => $totalBPJS,
            'koperasi' => $totalKoperasi,
        ];
        
        return view('dashboard.user', compact('stats', 'pengeluaran', 'periode'));
    }
}
