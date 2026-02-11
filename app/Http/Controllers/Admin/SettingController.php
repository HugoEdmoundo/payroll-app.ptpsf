<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        $settings = SystemSetting::all()->groupBy('group');
        
        $groups = [
            'jenis_karyawan' => 'Jenis Karyawan',
            'status_pegawai' => 'Status Pegawai',
            'status_perkawinan' => 'Status Perkawinan',
            'status_karyawan' => 'Status Karyawan',
            'lokasi_kerja' => 'Lokasi Kerja',
            'bank_options' => 'Bank Options',
            'jabatan_options' => 'Jabatan Options',
        ];

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request, $group)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        try {
            // UPDATE existing settings
            if ($request->has('settings')) {
                foreach ($request->settings as $index => $settingData) {
                    if (!empty($settingData['key']) && isset($settingData['value'])) {
                        SystemSetting::updateOrCreate(
                            [
                                'group' => $group,
                                'key' => $settingData['key']
                            ],
                            [
                                'value' => $settingData['value'],
                                'order' => $settingData['order'] ?? $index + 1,
                            ]
                        );
                    }
                }
            }
            
            // CREATE new setting
            if ($request->filled('new_key') && $request->filled('new_value')) {
                // Cek duplicate key
                $exists = SystemSetting::where('group', $group)
                    ->where('key', $request->new_key)
                    ->exists();
                
                if (!$exists) {
                    $maxOrder = SystemSetting::where('group', $group)->max('order') ?? 0;
                    
                    SystemSetting::create([
                        'group' => $group,
                        'key' => $request->new_key,
                        'value' => $request->new_value,
                        'order' => $maxOrder + 1,
                    ]);
                } else {
                    return redirect()->route('admin.settings.index')
                        ->with('error', 'Key "' . $request->new_key . '" already exists in ' . $group);
                }
            }

            return redirect()->route('admin.settings.index')
                ->with('success', ucfirst(str_replace('_', ' ', $group)) . ' settings updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
    
    public function destroy($group, $id)
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        try {
            $setting = SystemSetting::where('group', $group)->where('id', $id)->firstOrFail();
            $setting->delete();
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Setting deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Failed to delete setting.');
        }
    }
}