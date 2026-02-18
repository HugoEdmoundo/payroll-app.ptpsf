@props(['user'])

<div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>Permissions Summary
    </h3>
    
    @if($user->role && $user->role->is_superadmin)
        <div class="flex items-center justify-center py-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 mb-3">
                    <i class="fas fa-crown text-purple-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900">Superadmin Access</h4>
                <p class="text-sm text-gray-600 mt-1">Full system access granted</p>
            </div>
        </div>
    @else
        @php
            $allPermissions = $user->getAllPermissions();
            $permissionsByModule = $allPermissions->groupBy('module');
        @endphp
        
        <div class="space-y-3">
            @foreach($permissionsByModule as $module => $permissions)
            <div class="border border-gray-200 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-semibold text-gray-900 capitalize">
                        <i class="fas fa-cube text-indigo-500 mr-1"></i>{{ ucfirst($module) }}
                    </h4>
                    <span class="text-xs text-gray-500">{{ $permissions->count() }} permissions</span>
                </div>
                <div class="flex flex-wrap gap-1">
                    @foreach($permissions as $permission)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        @if($permission->action_type === 'view') bg-blue-100 text-blue-800
                        @elseif($permission->action_type === 'create') bg-green-100 text-green-800
                        @elseif($permission->action_type === 'edit') bg-yellow-100 text-yellow-800
                        @elseif($permission->action_type === 'delete') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($permission->action_type) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endforeach
            
            @if($permissionsByModule->isEmpty())
            <div class="text-center py-6">
                <i class="fas fa-exclamation-circle text-gray-400 text-3xl mb-2"></i>
                <p class="text-sm text-gray-600">No permissions assigned</p>
            </div>
            @endif
        </div>
        
        @php
            $userSpecificPermissions = $user->userPermissions;
        @endphp
        
        @if($userSpecificPermissions->count() > 0)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                User-Specific Overrides
            </h4>
            <div class="space-y-1">
                @foreach($userSpecificPermissions as $permission)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-700">{{ $permission->name }}</span>
                    @if($permission->pivot->is_granted)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i>Granted
                    </span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times mr-1"></i>Denied
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>
