@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('breadcrumb', 'Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Data Karyawan</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage and view employee data</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('karyawan.export'))
            <a href="{{ route('karyawan.export') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-download mr-1.5"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('karyawan.import'))
            <a href="{{ route('karyawan.import') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-upload mr-1.5"></i>Import
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('karyawan.create'))
            <a href="{{ route('karyawan.create') }}" 
               class="px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Add Karyawan
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Karyawan -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Total Karyawan</p>
                    <p class="text-lg lg:text-xl font-bold text-blue-900 truncate">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <!-- Active -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Active</p>
                    <p class="text-lg lg:text-xl font-bold text-blue-900 truncate">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
        </div>

        <!-- Non-Active -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-user-clock text-yellow-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Non-Active</p>
                    <p class="text-lg lg:text-xl font-bold text-blue-900 truncate">{{ number_format($stats['non_active']) }}</p>
                </div>
            </div>
        </div>

        <!-- Resign -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-users text-red-600 text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Resign</p>
                    <p class="text-lg lg:text-xl font-bold text-blue-900 truncate">{{ number_format($stats['resign']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('karyawan.index') }}">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Search by name, email, phone, jabatan, lokasi...">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select name="status_karyawan" 
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status_karyawan') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Non-Active" {{ request('status_karyawan') == 'Non-Active' ? 'selected' : '' }}>Non-Active</option>
                        <option value="Resign" {{ request('status_karyawan') == 'Resign' ? 'selected' : '' }}>Resign</option>
                    </select>
                    <select name="jenis_karyawan"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="Organik" {{ request('jenis_karyawan') == 'Organik' ? 'selected' : '' }}>Organik</option>
                        <option value="Non-Organik" {{ request('jenis_karyawan') == 'Non-Organik' ? 'selected' : '' }}>Non-Organik</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search') || request('status_karyawan') || request('jenis_karyawan'))
                    <a href="{{ route('karyawan.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Karyawan Table with Real-Time Auto-Refresh -->
    <div class="card p-0 overflow-hidden" x-data="realtimeTable({ interval: 'normal' })">
        <!-- Loading Indicator -->
        <div x-show="loading" class="absolute top-2 right-2 z-10">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                <i class="fas fa-sync fa-spin mr-1"></i> Updating...
            </span>
        </div>
        
        <div data-realtime-content>
            @include('components.karyawan.table', ['karyawan' => $karyawan])
        </div>
    </div>

    <!-- Pagination -->
    @if($karyawan->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $karyawan->links() }}
        </div>
    </div>
    @endif
</div>
@endsection