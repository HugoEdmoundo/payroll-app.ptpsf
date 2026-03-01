@extends('layouts.app')

@section('title', 'Pengaturan Gaji')
@section('breadcrumb', 'Pengaturan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Pengaturan Gaji</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Master salary configuration by employee type, position, and location</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('pengaturan_gaji.view'))
            <a href="{{ route('payroll.pengaturan-gaji.status-pegawai.index') }}" 
               class="px-3 py-2 text-xs sm:text-sm border-2 border-indigo-500 text-indigo-600 rounded-lg font-medium hover:bg-indigo-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-user-clock mr-1.5"></i>Status Pegawai
            </a>
            <a href="{{ route('payroll.pengaturan-bpjs-koperasi.edit') }}" 
               class="px-3 py-2 text-xs sm:text-sm border-2 border-green-500 text-green-600 rounded-lg font-medium hover:bg-green-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-shield-alt mr-1.5"></i>BPJS & Koperasi
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('pengaturan_gaji.export'))
            <a href="{{ route('payroll.pengaturan-gaji.export') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-download mr-1.5"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('pengaturan_gaji.create'))
            <a href="{{ route('payroll.pengaturan-gaji.create') }}" 
               class="px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Add Configuration
            </a>
            @endif
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.pengaturan-gaji.index') }}">
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
                               placeholder="Search by position, location, or type...">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('jenis_karyawan'))
                <a href="{{ route('payroll.pengaturan-gaji.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Pengaturan Gaji Table with Real-Time Auto-Refresh -->
    <div class="card p-0 overflow-hidden" x-data="realtimeTable({ interval: 'slow' })">
        <div x-show="loading" class="absolute top-2 right-2 z-10">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                <i class="fas fa-sync fa-spin mr-1"></i> Updating...
            </span>
        </div>
        
        <div data-realtime-content>
            @include('components.pengaturan-gaji.table', ['pengaturanGaji' => $pengaturanGaji])
        </div>
    </div>

    <!-- Pagination -->
    @if($pengaturanGaji->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $pengaturanGaji->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
