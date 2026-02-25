@extends('layouts.app')

@section('title', 'Absensi')
@section('breadcrumb', 'Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Absensi</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Employee attendance tracking and absence deduction calculation</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('absensi.export'))
            <a href="{{ route('payroll.absensi.export') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-download mr-1.5"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('absensi.import'))
            <a href="{{ route('payroll.absensi.import') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-upload mr-1.5"></i>Import
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('absensi.create'))
            <a href="{{ route('payroll.absensi.create') }}" 
               class="px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Add Absensi
            </a>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Potongan Absensi Formula:</p>
                <p>(Absence + Tanpa Keterangan) ÷ Jumlah Hari Bulan × (Gaji Pokok + Tunjangan Prestasi + Operasional)</p>
                <p class="mt-1 text-xs">Note: BPJS tidak ikut dihitung dalam potongan absensi</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.absensi.index') }}">
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
                               placeholder="Search by employee name...">
                    </div>
                </div>
                <div>
                    <input type="month" 
                           name="periode"
                           value="{{ request('periode') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('periode'))
                <a href="{{ route('payroll.absensi.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Absensi Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.absensi.table', ['absensiList' => $absensiList])
    </div>

    <!-- Pagination -->
    @if($absensiList->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $absensiList->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
