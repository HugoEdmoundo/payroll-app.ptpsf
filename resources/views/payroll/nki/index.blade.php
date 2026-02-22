@extends('layouts.app')

@section('title', 'NKI (Nilai Kemampuan & Kontribusi)')
@section('breadcrumb', 'Payroll / NKI')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">NKI - Penilaian Karyawan</h1>
            <p class="mt-1 text-sm text-gray-600">Nilai Kemampuan & Kontribusi untuk tunjangan prestasi</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('payroll.nki.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Tambah NKI

@section('title', 'NKI (Tunjangan Prestasi)')
@section('breadcrumb', 'NKI')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">NKI (Tunjangan Prestasi)</h1>
            <p class="mt-1 text-sm text-gray-600">Performance rating: Kemampuan (20%), Kontribusi (20%), Kedisiplinan (40%), Lainnya (20%)</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('payroll.nki.export') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-download mr-2"></i>Export
            </a>
            <a href="{{ route('payroll.nki.import') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-upload mr-2"></i>Import
            </a>
            <a href="{{ route('payroll.nki.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Add NKI
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai NKI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nki as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->karyawan->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $item->karyawan->jabatan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->periode }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $item->nilai_nki >= 8.5 ? 'bg-green-100 text-green-800' : ($item->nilai_nki >= 8.0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ number_format($item->nilai_nki, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                            {{ $item->persentase_prestasi }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('payroll.nki.show', $item) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payroll.nki.edit', $item) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('payroll.nki.destroy', $item) }}" method="POST" 
                                      onsubmit="return confirm('Yakin hapus NKI ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data NKI</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($nki->hasPages())
    <div class="mt-6">
        {{ $nki->links() }}
    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">NKI Calculation Formula:</p>
                <p>NKI ≥ 8.5 → 100% | NKI ≥ 8.0 → 80% | NKI < 8.0 → 70%</p>
                <p class="mt-1">Tunjangan Prestasi = Nilai Acuan Prestasi × Persentase NKI</p>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.nki.index') }}">
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
                <a href="{{ route('payroll.nki.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- NKI Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.nki.table', ['nkiList' => $nkiList])
    </div>

    <!-- Pagination -->
    @if($nkiList->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $nkiList->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
