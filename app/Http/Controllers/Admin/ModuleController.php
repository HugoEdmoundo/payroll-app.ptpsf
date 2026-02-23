<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $modules = Module::withCount('dynamicFields')->orderBy('order')->paginate(15);
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('admin.modules.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:modules,name|alpha_dash',
            'display_name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        Module::create($request->all());

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module created successfully.');
    }

    public function show(Module $module)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $module->load('dynamicFields');
        return view('admin.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($module->is_system) {
            return redirect()->back()->with('error', 'Cannot edit system module.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|alpha_dash|unique:modules,name,' . $module->id,
            'display_name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $module->update($request->all());

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    public function destroy(Module $module)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        if ($module->is_system) {
            return redirect()->back()->with('error', 'Cannot delete system module.');
        }
        
        $module->delete();
        
        return redirect()->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }
}
