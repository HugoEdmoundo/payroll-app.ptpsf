@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="card p-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-indigo-100">Here's what's happening with your system today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Karyawan -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Karyawan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_karyawan']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Karyawan -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Karyawan</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['active_karyawan']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Roles -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Roles</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_roles']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-pink-100 flex items-center justify-center">
                    <i class="fas fa-user-tag text-pink-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-history text-indigo-600 mr-2"></i>
                User Activities
            </h3>
            
            @if($recentActivities->count() > 0)
            <div class="space-y-3">
                @foreach($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-{{ $activity->action === 'login' ? 'sign-in-alt' : ($activity->action === 'logout' ? 'sign-out-alt' : 'edit') }} text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $activity->user->name ?? 'Unknown' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $activity->description ?? ucfirst($activity->action) . ' ' . ($activity->module ?? '') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No recent activities</p>
            </div>
            @endif
        </div>

        <!-- Managed Users -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-users-cog text-purple-600 mr-2"></i>
                Managed Users
            </h3>
            
            @if($managedUsers->count() > 0)
            <div class="space-y-3">
                @foreach($managedUsers as $managedUser)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($managedUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $managedUser->name }}</p>
                            <p class="text-xs text-gray-500">{{ $managedUser->email }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $managedUser->role->name ?? 'No Role' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-user-slash text-4xl mb-2"></i>
                <p>No managed users</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
