@extends('layouts.app')

@section('title', 'Manage Roles')
@section('breadcrumb', 'Roles')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Manage Roles</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage system roles and permissions</p>
        </div>
        <div>
            <a href="{{ route('admin.roles.create') }}" 
               class="inline-flex items-center px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Create Role
            </a>
        </div>
    </div>

    <!-- Roles Table with Real-Time Auto-Refresh -->
    <div class="card p-0 overflow-hidden" x-data="realtimeTable({ interval: 'slow' })">
        <div x-show="loading" class="absolute top-2 right-2 z-10">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                <i class="fas fa-sync fa-spin mr-1"></i> Updating...
            </span>
        </div>
        
        <div data-realtime-content>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                            @if($role->is_default)
                            <span class="text-xs text-blue-600 font-medium">Default</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $role->description ?: 'No description' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $role->users_count }} users
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($role->is_superadmin)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-crown mr-1"></i> Superadmin
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1"></i> Regular
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if(!$role->is_superadmin)
                                    <!-- Edit Button - SEMUA ROLE BISA DIEDIT KECUALI SUPERADMIN -->
                                    <a href="{{ route('admin.roles.edit', $role) }}" 
                                    class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded transition duration-150"
                                    title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- DELETE BUTTON - HANYA UNTUK ROLE HASIL CREATE -->
                                    @if(!$role->is_superadmin && !$role->is_default)
                                        @if($role->users_count == 0)
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                                                onsubmit="return confirm('Are you sure you want to delete role \"{{ $role->name }}\"? This action cannot be undone.')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition duration-150"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 p-1 cursor-not-allowed" 
                                                title="Cannot delete: {{ $role->users_count }} user(s) assigned">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        @endif
                                    @endif
                                    
                                    <!-- DEFAULT ROLE (User) - TIDAK BISA DI DELETE -->
                                    @if($role->is_default && $role->name === 'User')
                                        <span class="text-gray-400 p-1 cursor-not-allowed" 
                                            title="Default role cannot be deleted">
                                            <i class="fas fa-ban"></i>
                                        </span>
                                    @endif
                                @else
                                    <!-- SUPER ADMIN - TIDAK BISA DI DELETE -->
                                    <span class="text-purple-600 p-1" title="Super Admin Role">
                                        <i class="fas fa-crown"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($roles->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $roles->links() }}
        </div>
    </div>
    @endif
</div>
@endsection