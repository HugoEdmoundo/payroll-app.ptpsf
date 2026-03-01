@extends('layouts.app')

@section('title', 'Acuan Gaji - ' . \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y'))
@section('breadcrumb', 'Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('payroll.acuan-gaji.index') }}" 
                class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Acuan Gaji</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="fas fa-calendar-alt text-indigo-600 text-xs sm:text-sm"></i>
                        <p class="text-xs sm:text-sm font-medium text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                @if(auth()->user()->hasPermission('acuan_gaji.export'))
                <a href="{{ route('payroll.acuan-gaji.export', ['periode' => $periode]) }}" 
                class="inline-flex items-center px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 transition whitespace-nowrap">
                    <i class="fas fa-download mr-1.5"></i>Export
                </a>
                @endif
                
                @if(auth()->user()->hasPermission('acuan_gaji.create'))
                <a href="{{ route('payroll.acuan-gaji.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 shadow-sm transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Data
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Karyawan -->
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-600">Total Karyawan</p>
                    <p class="text-lg lg:text-xl font-bold text-blue-900 truncate">{{ number_format($stats->total_karyawan ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Total BPJS -->
        <div class="card p-4 bg-gradient-to-br from-green-50 to-green-100 border-green-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-green-500 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-green-600">Total BPJS</p>
                    <p class="text-base lg:text-lg font-bold text-green-900 truncate">Rp {{ number_format($stats->total_bpjs ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Gaji Bersih -->
        <div class="card p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-indigo-500 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-indigo-600">Total Gaji Bersih</p>
                    <p class="text-base lg:text-lg font-bold text-indigo-900 truncate">Rp {{ number_format($stats->total_gaji_bersih ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Perusahaan -->
        <div class="card p-4 bg-gradient-to-br from-red-50 to-red-100 border-red-200">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 flex-shrink-0 rounded-full bg-red-500 flex items-center justify-center">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-red-600">Pengeluaran</p>
                    <p class="text-base lg:text-lg font-bold text-red-900 truncate">Rp {{ number_format($stats->total_pengeluaran_perusahaan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4">
        <form method="GET" action="{{ route('payroll.acuan-gaji.periode', $periode) }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama, jenis, lokasi, jabatan..."
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               onkeyup="if(event.key === 'Enter') this.form.submit()">
                    </div>
                </div>
                
                <!-- Lokasi Kerja Filter -->
                <div>
                    <select name="lokasi_kerja" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Lokasi Kerja</option>
                        @foreach($lokasiKerjaList as $lokasi)
                        <option value="{{ $lokasi }}" {{ request('lokasi_kerja') == $lokasi ? 'selected' : '' }}>
                            {{ $lokasi }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Jabatan Filter -->
                <div>
                    <select name="jabatan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="this.form.submit()">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>
                            {{ $jabatan }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if(request('search') || request('lokasi_kerja') || request('jabatan'))
            <div class="mt-3">
                <a href="{{ route('payroll.acuan-gaji.periode', $periode) }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-times mr-1"></i>Clear Filters
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Table with Real-Time Auto-Refresh -->
    <div class="card overflow-hidden" x-data="realtimeTable({ interval: 'normal' })">
        <div x-show="loading" class="absolute top-2 right-2 z-10">
            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">
                <i class="fas fa-sync fa-spin mr-1"></i> Updating...
            </span>
        </div>
        
        <div data-realtime-content>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengeluaran</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Bersih</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($acuanGajiList as $acuan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $acuan->karyawan->nama_karyawan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $acuan->karyawan->jabatan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $acuan->karyawan->jenis_karyawan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($acuan->karyawan->status_pegawai === 'Kontrak') bg-green-100 text-green-800
                                @elseif($acuan->karyawan->status_pegawai === 'OJT') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $acuan->karyawan->status_pegawai }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $acuan->lokasi_kerja }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 font-mono">
                            Rp {{ number_format($acuan->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 font-medium font-mono">
                            Rp {{ number_format($acuan->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 font-medium font-mono">
                            Rp {{ number_format($acuan->total_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-indigo-600 font-bold font-mono">
                            Rp {{ number_format($acuan->gaji_bersih, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('payroll.acuan-gaji.show', $acuan) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(auth()->user()->hasPermission('acuan_gaji.edit'))
                                <a href="{{ route('payroll.acuan-gaji.edit', $acuan) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('acuan_gaji.delete'))
                                <form action="{{ route('payroll.acuan-gaji.destroy', $acuan) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <i class="fas fa-file-invoice-dollar text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data</h3>
                            <p class="text-gray-500">Belum ada data acuan gaji untuk periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($acuanGajiList->hasPages())
    <div class="flex justify-center">
        {{ $acuanGajiList->links() }}
    </div>
    @endif
</div>
@endsection
