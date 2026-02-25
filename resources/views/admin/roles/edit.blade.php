@extends('layouts.app')

@section('title', 'Edit Role')
@section('breadcrumb', 'Roles')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Manage Roles</a>
</li>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="card p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Role: {{ $role->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Update role permissions and access levels</p>
        </div>
        
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <input type="text" name="description" value="{{ old('description', $role->description) }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Permissions -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Module Permissions</h3>
                        <button type="button" onclick="toggleAllPermissions()" 
                                class="px-4 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 font-medium transition">
                                <i class="fas fa-check-double mr-1"></i>Select All / Deselect All
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">Pilih hak akses untuk setiap modul. Centang action yang diizinkan (View, Create, Edit, Delete, Import, Export).</p>
                    
                    <div class="space-y-4">
                        @php
                            $groupedByGroup = $permissions->groupBy('group');
                            $moduleIcons = [
                                'dashboard' => 'chart-line',
                                'karyawan' => 'users',
                                'pengaturan_gaji' => 'cog',
                                'nki' => 'star',
                                'absensi' => 'calendar-check',
                                'kasbon' => 'hand-holding-usd',
                                'acuan_gaji' => 'file-invoice-dollar',
                                'hitung_gaji' => 'calculator',
                                'slip_gaji' => 'receipt',
                                'users' => 'user-shield',
                                'roles' => 'user-tag',
                                'settings' => 'sliders-h',
                            ];
                        @endphp
                        
                        @foreach($groupedByGroup as $group => $groupPermissions)
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <span class="h-8 w-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ $group === 'Dashboard' ? 'chart-line' : ($group === 'Karyawan' ? 'users' : ($group === 'Payroll' ? 'money-bill-wave' : 'cog')) }} text-white text-sm"></i>
                                </span>
                                {{ $group }}
                            </h3>
                            
                            @php
                                $groupedByModule = $groupPermissions->groupBy('module');
                            @endphp
                            
                            <div class="space-y-3">
                                @foreach($groupedByModule as $module => $modulePermissions)
                                <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:border-indigo-300 transition duration-150">
                                    <!-- Module Header -->
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-5 py-3 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                                                    <i class="fas fa-{{ $moduleIcons[$module] ?? 'cube' }} text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900 capitalize">
                                                        {{ str_replace('_', ' ', $module) }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500">{{ $modulePermissions->count() }} permissions</p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="toggleModule('{{ $module }}')" 
                                                    class="px-3 py-1.5 text-xs bg-white border border-indigo-300 text-indigo-700 rounded-lg hover:bg-indigo-50 font-medium transition">
                                                <i class="fas fa-check-square mr-1"></i>Select All
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Module Permissions -->
                                    <div class="p-5 bg-white">
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                            @foreach($modulePermissions as $permission)
                                            <div class="flex items-center p-3 rounded-lg border-2 {{ in_array($permission->id, $rolePermissions) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }} hover:border-indigo-400 hover:bg-indigo-50 transition duration-150 cursor-pointer"
                                                 onclick="document.getElementById('permission_{{ $permission->id }}').click()">
                                                <input type="checkbox" name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="permission_{{ $permission->id }}"
                                                       data-module="{{ $module }}"
                                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded pointer-events-none">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 flex-1 cursor-pointer pointer-events-none">
                                                    <span class="text-sm font-semibold text-gray-900 capitalize">
                                                        {{ $permission->action_type }}
                                                    </span>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function toggleModule(module) {
    const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection
