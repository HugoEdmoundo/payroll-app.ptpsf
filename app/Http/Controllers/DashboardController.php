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
        
        // Recent activities (last 5)
        try {
            $activities = ActivityLog::with('user')
                                    ->latest()
                                    ->take(5)
                                    ->get();
            
            $recentActivities = $activities->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user->name ?? 'System',
                    'action' => $activity->action,
                    'module' => $activity->module,
                    'description' => $activity->description ?? ucfirst($activity->action) . ' ' . ($activity->module ?? ''),
                    'time' => $activity->created_at->diffForHumans(),
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Failed to fetch activity logs: ' . $e->getMessage());
            $recentActivities = collect();
        }
        
        // Managed users (latest 5 users)
        try {
            $users = User::with('role')
                        ->where('id', '!=', auth()->id())
                        ->latest()
                        ->take(5)
                        ->get();
            
            $managedUsers = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? 'No Role',
                    'avatar' => strtoupper(substr($user->name, 0, 1)),
                ];
            });
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
        
        // If no data for current month, set all to 0
        if ($hitungGajiData->isEmpty()) {
            $stats = [
                'total_karyawan' => Karyawan::where('status_karyawan', 'Active')->count(),
                'total_pengeluaran' => 0,
            ];
            
            $pengeluaran = [
                'teknisi_borongan' => 0,
                'konsultan_organik' => 0,
                'bpjs' => 0,
                'koperasi' => 0,
            ];
            
            return view('dashboard.user', compact('stats', 'pengeluaran', 'periode'));
        }
        
        // Calculate stats with adjustments using getFinalValue()
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
        
        // Pengeluaran BPJS (PENDAPATAN only - with adjustments)
        $totalBPJS = $hitungGajiData->sum(function($item) {
            return $item->getFinalValue('bpjs_kesehatan_pendapatan') + 
                   $item->getFinalValue('bpjs_kecelakaan_kerja_pendapatan') + 
                   $item->getFinalValue('bpjs_kematian_pendapatan') + 
                   $item->getFinalValue('bpjs_jht_pendapatan') + 
                   $item->getFinalValue('bpjs_jp_pendapatan');
        });
        
        // Tagihan Koperasi (with adjustments)
        $totalKoperasi = $hitungGajiData->sum(function($item) {
            return $item->getFinalValue('koperasi');
        });
        
        // Total Pengeluaran = Gaji Bersih + BPJS + Koperasi
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
