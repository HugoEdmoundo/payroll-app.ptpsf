@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('breadcrumb', 'Karyawan')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Karyawan</h1>
            <p class="mt-1 text-sm text-gray-600">Manage and view employee data</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            @if(auth()->user()->hasPermission('karyawan.export'))
            <a href="{{ route('karyawan.export') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-download mr-2"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('karyawan.import'))
            <a href="{{ route('karyawan.import') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-upload mr-2"></i>Import
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('karyawan.create'))
            <a href="{{ route('karyawan.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Add Karyawan
            </a>
            @endif
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Search by name, ID, or jabatan...">
                </div>
            </div>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="Aktif">Active</option>
                    <option value="Non-Aktif">Inactive</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Types</option>
                    <option value="Tetap">Permanent</option>
                    <option value="Kontrak">Contract</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Karyawan Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.karyawan.table', ['karyawan' => $karyawan])
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