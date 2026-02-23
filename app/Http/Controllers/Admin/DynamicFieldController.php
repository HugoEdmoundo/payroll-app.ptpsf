<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicField;
use App\Models\Module;
use Illuminate\Http\Request;

class DynamicFieldController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $query = DynamicField::with('module');
        
        if ($request->has('module_id') && $request->module_id) {
            $query->where('module_id', $request->module_id);
        }
        
        $fields = $query->orderBy('module_id')->orderBy('order')->paginate(20);
        $modules = Module::orderBy('display_name')->get();
        
        return view('admin.fields.index', compact('fields', 'modules'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $modules = Module::where('is_active', true)->orderBy('display_name')->get();
        $selectedModule = $request->module_id ? Module::find($request->module_id) : null;
        
        return view('admin.fields.create', compact('modules', 'selectedModule'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'field_name' => 'required|string|max:255|alpha_dash',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,select,textarea,date,datetime,checkbox,radio,file',
            'field_options' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'default_value' => 'nullable|string',
            'help_text' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'is_searchable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_form' => 'boolean',
            'order' => 'integer|min:0',
            'group' => 'nullable|string|max:100',
        ]);

        // Check unique field_name per module
        $exists = DynamicField::where('module_id', $request->module_id)
            ->where('field_name', $request->field_name)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Field name already exists in this module.');
        }

        DynamicField::create($request->all());

        return redirect()->route('admin.fields.index', ['module_id' => $request->module_id])
            ->with('success', 'Dynamic field created successfully.');
    }

    public function show(DynamicField $field)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $field->load('module');
        return view('admin.fields.show', compact('field'));
    }

    public function edit(DynamicField $field)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $modules = Module::where('is_active', true)->orderBy('display_name')->get();
        return view('admin.fields.edit', compact('field', 'modules'));
    }

    public function update(Request $request, DynamicField $field)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'field_name' => 'required|string|max:255|alpha_dash',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|in:text,email,number,select,textarea,date,datetime,checkbox,radio,file',
            'field_options' => 'nullable|string',
            'validation_rules' => 'nullable|string',
            'default_value' => 'nullable|string',
            'help_text' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'is_searchable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_form' => 'boolean',
            'order' => 'integer|min:0',
            'group' => 'nullable|string|max:100',
        ]);

        // Check unique field_name per module (except current)
        $exists = DynamicField::where('module_id', $request->module_id)
            ->where('field_name', $request->field_name)
            ->where('id', '!=', $field->id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Field name already exists in this module.');
        }

        $field->update($request->all());

        return redirect()->route('admin.fields.index', ['module_id' => $request->module_id])
            ->with('success', 'Dynamic field updated successfully.');
    }

    public function destroy(DynamicField $field)
    {
        if (!auth()->user()->isSuperadmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $moduleId = $field->module_id;
        $field->delete();
        
        return redirect()->route('admin.fields.index', ['module_id' => $moduleId])
            ->with('success', 'Dynamic field deleted successfully.');
    }
}
