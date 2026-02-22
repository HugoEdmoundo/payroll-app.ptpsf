@extends('layouts.app')

@section('title', 'History Acuan Gaji')
@section('breadcrumb', 'History Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">History Acuan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">View all generated salary reference history</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('payroll.acuan-gaji.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>Back to Acuan Gaji
            </a>
            <a href="{{ route('payroll.acuan-gaji.export', request()->all()) }}" 
               class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-150">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-4 bg-purple-50 border-purple-200">
        <div class="flex items-start">
            <i class="fas fa-history text-purple-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-purple-700">
                <p class="font-medium mb-1">History Features:</p>
                <ul class="list-disc list-inside">
                    <li>View all generated salary reference data by periode</li>
                    <li>Filter by jenis karyawan and jabatan</li>
                    <li>Export historical data for reporting</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.acuan-gaji.history') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Employee</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                    <select name="periode" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Periode</option>
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($periode . '-01')->format('F Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan</label>
                    <select name="jenis_karyawan" 
                            id="jenisKaryawanFilter"
                            onchange="this.form.submit()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Types</option>
                        @foreach(\App\Models\SystemSetting::getOptions('jenis_karyawan') as $key => $value)
                            <option value="{{ $value }}" {{ request('jenis_karyawan') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @if(request('jenis_karyawan') && count($jabatanList) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                            <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
                @if(request('search') || request('periode') || request('jenis_karyawan') || request('jabatan'))
                <a href="{{ route('payroll.acuan-gaji.history') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Jumlah Karyawan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $acuanGajiList->total() }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran BPJS</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">
                        Rp {{ number_format($acuanGajiList->sum(function($item) {
                            return $item->bpjs_kesehatan_pengeluaran + 
                                   $item->bpjs_kecelakaan_kerja_pengeluaran + 
                                   $item->bpjs_kematian_pengeluaran + 
                                   $item->bpjs_jht_pengeluaran + 
                                   $item->bpjs_jp_pengeluaran;
                        }), 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hospital text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Gaji (Selain BPJS)</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">
                        Rp {{ number_format($acuanGajiList->sum(function($item) {
                            return $item->gaji_bersih + 
                                   $item->bpjs_kesehatan_pengeluaran + 
                                   $item->bpjs_kecelakaan_kerja_pengeluaran + 
                                   $item->bpjs_kematian_pengeluaran + 
                                   $item->bpjs_jht_pengeluaran + 
                                   $item->bpjs_jp_pengeluaran;
                        }), 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
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
@endsection
