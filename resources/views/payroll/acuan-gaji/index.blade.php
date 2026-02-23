@extends('layouts.app')

@section('title', 'Acuan Gaji')
@section('breadcrumb', 'Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Acuan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Salary reference combining configuration and components data</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            @if(auth()->user()->hasPermission('acuan_gaji.import'))
            <a href="{{ route('payroll.acuan-gaji.import') }}" 
               class="px-4 py-2 border border-indigo-300 rounded-lg text-sm font-medium text-indigo-700 hover:bg-indigo-50 transition duration-150">
                <i class="fas fa-upload mr-2"></i>Import
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('acuan_gaji.export'))
            <a href="{{ route('payroll.acuan-gaji.export', request()->all()) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-download mr-2"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('acuan_gaji.generate'))
            <button onclick="document.getElementById('generateModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-150">
                <i class="fas fa-magic mr-2"></i>Generate
            </button>
            @endif
            
            @if(auth()->user()->hasPermission('acuan_gaji.create'))
            <a href="{{ route('payroll.acuan-gaji.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Add Manual
            </a>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Acuan Gaji Features:</p>
                <ul class="list-disc list-inside">
                    <li><strong>Generate:</strong> Auto-create salary reference for all employees based on Pengaturan Gaji + Komponen data</li>
                    <li><strong>Pendapatan:</strong> Copied from Pengaturan Gaji (READ-ONLY)</li>
                    <li><strong>Pengeluaran:</strong> Auto-calculated from Komponen (NKI, Absensi, Kasbon) + manual input</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.acuan-gaji.index') }}">
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
                <div>
                    <select name="jenis_karyawan" 
                            id="jenisKaryawanFilter"
                            onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Types</option>
                        @foreach(\App\Models\SystemSetting::getOptions('jenis_karyawan') as $key => $value)
                            <option value="{{ $value }}" {{ request('jenis_karyawan') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @if(request('jenis_karyawan') && count($jabatanList) > 0)
                <div>
                    <select name="jabatan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                            <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('periode') || request('jenis_karyawan') || request('jabatan'))
                <a href="{{ route('payroll.acuan-gaji.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Acuan Gaji Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.acuan-gaji.table', ['acuanGajiList' => $acuanGajiList])
    </div>

    <!-- Pagination -->
    @if($acuanGajiList->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $acuanGajiList->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Generate Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Generate Acuan Gaji</h3>
            <form method="POST" action="{{ route('payroll.acuan-gaji.generate') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode *</label>
                        <input type="month" 
                               name="periode" 
                               required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan (Optional)</label>
                        <select name="jenis_karyawan" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Types</option>
                            @foreach(\App\Models\SystemSetting::getOptions('jenis_karyawan') as $key => $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Leave empty to generate for all employee types</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('generateModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-magic mr-2"></i>Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
