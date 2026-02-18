<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\DynamicField;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        if (!auth()->user()->role->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }
        
        $modules = Module::withCount('dynamicFields')->orderBy('order')->get();
        $systemSettings = SystemSetting::all()->groupBy('group');
        
        $stats = [
            'total_modules' => Module::count(),
            'active_modules' => Module::where('is_active', true)->count(),
            'total_fields' => DynamicField::count(),
            'active_fields' => DynamicField::where('is_active', true)->count(),
            'total_settings' => SystemSetting::count(),
        ];
        
        return view('admin.cms.index', compact('modules', 'systemSettings', 'stats'));
    }
}
