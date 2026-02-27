@extends('layouts.app')

@section('title', 'Pengaturan BPJS & Koperasi')
@section('breadcrumb', 'Pengaturan BPJS & Koperasi')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Pengaturan BPJS & Koperasi</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Configuration for BPJS and Koperasi by employee type and status</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('pengaturan_gaji.export'))
            <a href="{{ route('payroll.pengaturan-bpjs-koperasi.export', request()->only(['jenis_karyawan', 'status_pegawai'])) }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-download mr-1.5"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('pengaturan_gaji.create'))
            <a href="{{ route('payroll.pengaturan-bpjs-koperasi.create') }}" 
               class="px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Add Configuration
            </a>
            @endif
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <select name="jenis_karyawan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Employee Types</option>
                        <option value="Teknisi" {{ request('jenis_karyawan') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="Borongan" {{ request('jenis_karyawan') == 'Borongan' ? 'selected' : '' }}>Borongan</option>
                    </select>
                </div>
                <div class="flex-1">
                    <select name="status_pegawai" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="Kontrak" {{ request('status_pegawai') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="OJT" {{ request('status_pegawai') == 'OJT' ? 'selected' : '' }}>OJT</option>
                        <option value="Harian" {{ request('status_pegawai') == 'Harian' ? 'selected' : '' }}>Harian</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                @if(request('jenis_karyawan') || request('status_pegawai'))
                <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pegawai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BPJS (Pendapatan)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BPJS (Pengeluaran)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koperasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengaturanBpjsKoperasi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $item->jenis_karyawan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->status_pegawai == 'Kontrak' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->status_pegawai == 'OJT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $item->status_pegawai == 'Harian' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $item->status_pegawai }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->total_bpjs_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->total_bpjs_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->koperasi, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @if(auth()->user()->hasPermission('pengaturan_gaji.view'))
                                <a href="{{ route('payroll.pengaturan-bpjs-koperasi.show', $item) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('pengaturan_gaji.edit'))
                                <a href="{{ route('payroll.pengaturan-bpjs-koperasi.edit', $item) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->hasPermission('pengaturan_gaji.delete'))
                                <form action="{{ route('payroll.pengaturan-bpjs-koperasi.destroy', $item) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this configuration?');">
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No configurations found. <a href="{{ route('payroll.pengaturan-bpjs-koperasi.create') }}" class="text-indigo-600 hover:text-indigo-900">Add one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($pengaturanBpjsKoperasi->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $pengaturanBpjsKoperasi->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
