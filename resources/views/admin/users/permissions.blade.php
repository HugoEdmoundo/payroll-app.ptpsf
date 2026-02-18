@extends('layouts.app')

@section('title', 'Manage User Permissions')
@section('breadcrumb', 'Users')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Permissions</h1>
            <p class="mt-1 text-sm text-gray-600">
                Configure specific permissions for <span class="font-semibold text-indigo-600">{{ $user->name }}</span>
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full overflow-hidden flex-shrink-0 mr-4 bg-gradient-to-r from-indigo-500 to-purple-600">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/profile-photos/' . $user->profile_photo) }}" 
                         alt="{{ $user->name }}" 
                         class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full flex items-center justify-center text-white font-semibold text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <div class="mt-2 flex items-center space-x-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $user->role->is_superadmin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                        Role: {{ $user->role->name }}
                    </span>
                    @if($user->position)
                    <span class="text-xs text-gray-500">{{ $user->position }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Permission Priority</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Inherit from Role:</strong> User will have permissions based on their role</li>
                        <li><strong>Grant:</strong> User will have this permission regardless of role (override)</li>
                        <li><strong>Deny:</strong> User will NOT have this permission even if role has it (override)</li>
                        <li>User-specific permissions always take priority over role permissions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Form -->
    <form action="{{ route('admin.users.permissions.update', $user) }}" method="POST" x-data="permissionManager()">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach($permissions as $module => $modulePermissions)
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <!-- Module Header -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 capitalize">
                        <i class="fas fa-cube mr-2 text-indigo-600"></i>{{ ucfirst($module) }} Module
                    </h3>
                </div>

                <!-- Permissions List -->
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($modulePermissions as $permission)
                        <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $permission->name }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($permission->action_type === 'view') bg-blue-100 text-blue-800
                                        @elseif($permission->action_type === 'create') bg-green-100 text-green-800
                                        @elseif($permission->action_type === 'edit') bg-yellow-100 text-yellow-800
                                        @elseif($permission->action_type === 'delete') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($permission->action_type) }}
                                    </span>
                                    
                                    @if(in_array($permission->id, $rolePermissions))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-check-circle mr-1"></i>From Role
                                    </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ $permission->description }}</p>
                                
                                <!-- Notes Input (shown when grant or deny is selected) -->
                                <div x-show="getPermissionValue({{ $permission->id }}) !== 'inherit'" 
                                     x-transition
                                     class="mt-2">
                                    <input type="text" 
                                           name="notes[{{ $permission->id }}]" 
                                           value="{{ $userPermissions[$permission->id]['notes'] ?? '' }}"
                                           placeholder="Add notes (optional)"
                                           class="w-full text-xs px-3 py-1.5 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                            
                            <!-- Permission Actions -->
                            <div class="flex items-center space-x-2">
                                @php
                                    $currentValue = 'inherit';
                                    if(isset($userPermissions[$permission->id])) {
                                        $currentValue = $userPermissions[$permission->id]['is_granted'] ? 'grant' : 'deny';
                                    }
                                @endphp
                                
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="permissions[{{ $permission->id }}]" 
                                           value="inherit"
                                           {{ $currentValue === 'inherit' ? 'checked' : '' }}
                                           @change="updatePermission({{ $permission->id }}, 'inherit')"
                                           class="form-radio h-4 w-4 text-gray-600 focus:ring-gray-500">
                                    <span class="ml-2 text-xs text-gray-700">Inherit</span>
                                </label>
                                
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="permissions[{{ $permission->id }}]" 
                                           value="grant"
                                           {{ $currentValue === 'grant' ? 'checked' : '' }}
                                           @change="updatePermission({{ $permission->id }}, 'grant')"
                                           class="form-radio h-4 w-4 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-xs text-green-700 font-medium">Grant</span>
                                </label>
                                
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="permissions[{{ $permission->id }}]" 
                                           value="deny"
                                           {{ $currentValue === 'deny' ? 'checked' : '' }}
                                           @change="updatePermission({{ $permission->id }}, 'deny')"
                                           class="form-radio h-4 w-4 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-xs text-red-700 font-medium">Deny</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Form Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3 bg-white rounded-lg p-6">
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-save mr-2"></i>Save Permissions
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function permissionManager() {
    return {
        permissions: {},
        
        updatePermission(id, value) {
            this.permissions[id] = value;
        },
        
        getPermissionValue(id) {
            return this.permissions[id] || 'inherit';
        }
    }
}
</script>
@endpush
@endsection
