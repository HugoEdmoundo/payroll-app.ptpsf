@extends('layouts.app')

@section('title', 'Create Role')
@section('breadcrumb', 'Roles')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Manage Roles</a>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New Role</h1>
            <p class="mt-1 text-sm text-gray-600">Define role permissions and access levels</p>
        </div>
        
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <input type="text" name="description" value="{{ old('description') }}"
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
                        <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                        <button type="button" onclick="toggleAllPermissions()" 
                                class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            <i class="fas fa-check-double mr-1"></i>Toggle All
                        </button>
                    </div>
                    <div class="space-y-4">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:border-indigo-200 transition duration-150">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-5 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide flex items-center">
                                        <i class="fas fa-{{ $group === 'Karyawan' ? 'users' : ($group === 'Payroll' ? 'money-bill-wave' : ($group === 'Admin' ? 'shield-alt' : 'cog')) }} text-indigo-600 mr-2"></i>
                                        {{ $group }}
                                        <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">
                                            {{ $groupPermissions->count() }}
                                        </span>
                                    </h4>
                                    <button type="button" onclick="toggleGroup('{{ $group }}')" 
                                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                        Select All
                                    </button>
                                </div>
                            </div>
                            <div class="p-5 bg-white">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                    <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition duration-150">
                                        <input type="checkbox" name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               id="permission_{{ $permission->id }}"
                                               data-group="{{ $group }}"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-0.5">
                                        <label for="permission_{{ $permission->id }}" class="ml-3 flex-1 cursor-pointer">
                                            <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                            @if($permission->description)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.roles.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Create Role
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

function toggleGroup(group) {
    const checkboxes = document.querySelectorAll(`input[data-group="${group}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection