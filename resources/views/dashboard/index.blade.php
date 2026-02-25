@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-1 text-sm text-gray-600">Here's what's happening with your payroll system today.</p>
            </div>
            <div class="hidden md:block">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ now()->format('l, d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Karyawan -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Karyawan</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_karyawan'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Karyawan -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Karyawan Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['active_karyawan'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users (if has permission) -->
        @if(isset($stats['total_users']))
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Roles (if has permission) -->
        @if(isset($stats['total_roles']))
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Roles</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_roles'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tag text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Karyawan -->
    @if(isset($stats['recent_karyawan']) && $stats['recent_karyawan']->count() > 0)
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Karyawan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stats['recent_karyawan'] as $karyawan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($karyawan->nama_karyawan, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $karyawan->nama_karyawan }}</div>
                                    <div class="text-sm text-gray-500">{{ $karyawan->jenis_karyawan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $karyawan->jabatan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $karyawan->lokasi_kerja }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $karyawan->status_karyawan === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $karyawan->status_karyawan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $karyawan->join_date ? $karyawan->join_date->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <a href="{{ route('karyawan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                View all karyawan <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if(auth()->user()->hasPermission('karyawan.create'))
        <a href="{{ route('karyawan.create') }}" class="card p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Add Karyawan</h3>
                    <p class="text-sm text-gray-500">Create new employee</p>
                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasPermission('acuan_gaji.view'))
        <a href="{{ route('payroll.acuan-gaji.index') }}" class="card p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Acuan Gaji</h3>
                    <p class="text-sm text-gray-500">Manage salary reference</p>
                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->hasPermission('slip_gaji.view'))
        <a href="{{ route('payroll.slip-gaji.index') }}" class="card p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-900">Slip Gaji</h3>
                    <p class="text-sm text-gray-500">View salary slips</p>
                </div>
            </div>
        </a>
        @endif
    </div>
</div>
@endsection
