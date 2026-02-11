@extends('layouts.app')

@section('title', 'Superadmin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👑</h1>
                <p class="mt-2 text-gray-600">Here's what's happening with your payroll system today.</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                    <i class="fas fa-crown mr-1"></i> Superadmin
                </span>
                <span class="text-sm text-gray-500">
                    <i class="far fa-calendar mr-1"></i>{{ now()->format('F d, Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50 to-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Total Karyawan</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_karyawan'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user-tie text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-50 to-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Active Karyawan</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['active_karyawan'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Total Roles</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_roles'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-user-tag text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('karyawan.create') }}" 
               class="flex flex-col items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition duration-150">
                <i class="fas fa-user-plus text-indigo-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Add Karyawan</span>
            </a>
            <a href="{{ route('admin.users.create') }}" 
               class="flex flex-col items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition duration-150">
                <i class="fas fa-user-plus text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Add User</span>
            </a>
            <a href="{{ route('admin.roles.create') }}" 
               class="flex flex-col items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition duration-150">
                <i class="fas fa-plus-circle text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Create Role</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" 
               class="flex flex-col items-center justify-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                <i class="fas fa-cogs text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">System Settings</span>
            </a>
        </div>
    </div>
</div>
@endsection