@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="card p-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-indigo-100">Here's what's happening with your system today.</p>
        <p class="mt-1 text-indigo-100">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6" x-data="dashboardStatsWidget()">
        <!-- Hidden data for initial load -->
        <script type="application/json" id="stats-data">
            @json($stats)
        </script>
        
        <!-- Total Karyawan -->
        <div class="card p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-600">Total Karyawan</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 truncate" x-text="stats.total_karyawan ? stats.total_karyawan.toLocaleString() : '0'"></p>
                </div>
            </div>
        </div>

        <!-- Active Karyawan -->
        <div class="card p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-600">Active Karyawan</p>
                    <p class="text-xl lg:text-2xl font-bold text-green-600 truncate" x-text="stats.active_karyawan ? stats.active_karyawan.toLocaleString() : '0'"></p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="card p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-600">Total Users</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 truncate" x-text="stats.total_users ? stats.total_users.toLocaleString() : '0'"></p>
                </div>
            </div>
        </div>

        <!-- Total Roles -->
        <div class="card p-4 lg:p-6">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-pink-100 flex items-center justify-center">
                    <i class="fas fa-user-tag text-pink-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-600">Total Roles</p>
                    <p class="text-xl lg:text-2xl font-bold text-gray-900 truncate" x-text="stats.total_roles ? stats.total_roles.toLocaleString() : '0'"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="card p-6" x-data="activityWidget()">
            <!-- Hidden data for initial load -->
            <script type="application/json" id="activities-data">
                @json($recentActivities)
            </script>
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history text-indigo-600 mr-2"></i>
                    Recent Activities
                </h3>
                <a href="{{ route('admin.activity-logs.index') }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800 transition">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div x-show="activities.length > 0" class="space-y-3">
                <template x-for="activity in activities" :key="activity.id">
                    <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas text-indigo-600 text-sm"
                                   :class="{
                                       'fa-sign-in-alt': activity.action === 'login',
                                       'fa-sign-out-alt': activity.action === 'logout',
                                       'fa-edit': !['login', 'logout'].includes(activity.action)
                                   }"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900" x-text="activity.user_name"></p>
                            <p class="text-sm text-gray-600" x-text="activity.description"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="activity.time"></p>
                        </div>
                    </div>
                </template>
            </div>
            
            <div x-show="activities.length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No recent activities</p>
            </div>
        </div>

        <!-- Managed Users -->
        <div class="card p-6" x-data="managedUsersWidget()">
            <!-- Hidden data for initial load -->
            <script type="application/json" id="managed-users-data">
                @json($managedUsers)
            </script>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-users-cog text-purple-600 mr-2"></i>
                Managed Users
            </h3>
            
            <div x-show="users.length > 0" class="space-y-3">
                <template x-for="user in users" :key="user.id">
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold"
                                 x-text="user.avatar"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="user.name"></p>
                                <p class="text-xs text-gray-500" x-text="user.email"></p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                              x-text="user.role"></span>
                    </div>
                </template>
            </div>
            
            <div x-show="users.length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-user-slash text-4xl mb-2"></i>
                <p>No managed users</p>
            </div>
        </div>
    </div>
</div>
@endsection
