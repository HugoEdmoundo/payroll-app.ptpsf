@extends('layouts.app')

@section('title', 'Kelola Periode - Acuan Gaji')
@section('breadcrumb', 'Kelola Periode')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('payroll.acuan-gaji.index') }}" 
               class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Periode</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola dan hapus periode acuan gaji</p>
            </div>
        </div>
    </div>

    <!-- Periode List -->
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Periode</h3>
            <p class="text-sm text-gray-600 mt-1">Total {{ $periodes->count() }} periode</p>
        </div>
        
        @if($periodes->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Karyawan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gaji Bersih</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodes as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $item['periode'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-users mr-2"></i>{{ $item['total_karyawan'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-green-600">
                                Rp {{ number_format($item['total_gaji_bersih'], 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('payroll.acuan-gaji.periode', $item['periode']) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-indigo-300 rounded-lg text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye mr-2"></i>Lihat
                                </a>
                                
                                @if(auth()->user()->hasPermission('acuan_gaji.delete'))
                                <form action="{{ route('payroll.acuan-gaji.periode.delete', $item['periode']) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus periode {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}?\n\nTotal {{ $item['total_karyawan'] }} data acuan gaji akan dihapus!\n\nPeringatan: Data yang sudah dihapus tidak dapat dikembalikan!')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition"
                                            title="Hapus Periode">
                                        <i class="fas fa-trash mr-2"></i>Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Periode</h3>
            <p class="text-gray-500 mb-4">Silakan generate atau import acuan gaji terlebih dahulu.</p>
            <a href="{{ route('payroll.acuan-gaji.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
