@extends('layouts.app')

@section('title', 'Pengaturan Gaji')
@section('breadcrumb', 'Pengaturan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Master salary configuration by employee type, position, and location</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('payroll.pengaturan-gaji.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Add Configuration
            </a>
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

    <!-- Pengaturan Gaji Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.pengaturan-gaji.table', ['pengaturanGaji' => $pengaturanGaji])
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
