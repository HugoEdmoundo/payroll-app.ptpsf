<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\HitungGaji;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function stats()
    {
        $user = auth()->user();
        $isSuperadmin = $user->role && $user->role->name === 'Superadmin';
        
        if ($isSuperadmin) {
            $stats = [
                'total_karyawan' => Karyawan::count(),
                'active_karyawan' => Karyawan::where('status_karyawan', 'Active')->count(),
                'total_users' => User::count(),
                'total_roles' => \App\Models\Role::count(),
            ];
        } else {
            $stats = [
                'total_karyawan' => Karyawan::where('status_karyawan', 'Active')->count(),
            ];
        }
        
        return response()->json(['stats' => $stats]);
    }
    
    public function managedUsers()
    {
        $users = User::with('role')
                    ->where('id', '!=', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role->name ?? 'No Role',
                            'avatar' => strtoupper(substr($user->name, 0, 1)),
                        ];
                    });
        
        return response()->json(['users' => $users]);
    }
    
    public function pengeluaran()
    {
        $periode = Carbon::now()->format('Y-m');
        
        $hitungGajiData = HitungGaji::with('karyawan')
                                   ->where('periode', $periode)
                                   ->get();
        
        $teknisiBorongan = $hitungGajiData->filter(function($item) {
            return in_array($item->karyawan->jenis_karyawan ?? '', ['Teknisi', 'Borongan']);
        })->sum('gaji_bersih');
        
        $konsultanOrganik = $hitungGajiData->filter(function($item) {
            return in_array($item->karyawan->jenis_karyawan ?? '', ['Konsultan', 'Organik']);
        })->sum('gaji_bersih');
        
        $totalBPJS = $hitungGajiData->sum(function($item) {
            return ($item->bpjs_kesehatan_pendapatan ?? 0) + 
                   ($item->bpjs_kecelakaan_kerja_pendapatan ?? 0) + 
                   ($item->bpjs_kematian_pendapatan ?? 0) + 
                   ($item->bpjs_jht_pendapatan ?? 0) + 
                   ($item->bpjs_jp_pendapatan ?? 0);
        });
        
        $totalKoperasi = $hitungGajiData->sum('koperasi');
        
        $pengeluaran = [
            'teknisi_borongan' => $teknisiBorongan,
            'konsultan_organik' => $konsultanOrganik,
            'bpjs' => $totalBPJS,
            'koperasi' => $totalKoperasi,
        ];
        
        $total = $teknisiBorongan + $konsultanOrganik + $totalBPJS + $totalKoperasi;
        
        return response()->json([
            'pengeluaran' => $pengeluaran,
            'total' => $total
        ]);
    }
}
