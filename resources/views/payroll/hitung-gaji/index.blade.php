@extends('layouts.app')

@section('title', 'Hitung Gaji')
@section('breadcrumb', 'Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-4 sm:p-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Hitung Gaji</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Pilih periode untuk melihat data hitung gaji</p>
        </div>
    </div>

    <!-- Periode Cards -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Periode</h3>
        
        @if($periodes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($periodes as $item)
            <div class="relative block border border-gray-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-lg transition group">
                <a href="{{ route('payroll.hitung-gaji.periode', $item['periode']) }}" class="block">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}
                            </h4>
                            <p class="text-xs text-gray-500">{{ $item['periode'] }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                            <i class="fas fa-calculator text-white text-xl"></i>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Total Karyawan</span>
                        <span class="text-lg font-bold text-indigo-600">{{ $item['total_karyawan'] }}</span>
                    </div>
                </a>
                @if(auth()->user()->hasPermission('hitung_gaji.delete'))
                <div class="mt-2 pt-2 border-t border-gray-100 text-right">
                    <form action="{{ route('payroll.hitung-gaji.periode.delete', $item['periode']) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus periode {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}? Seluruh data hitung gaji di periode ini akan dihapus permanen!')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                            <i class="fas fa-trash mr-1"></i>Hapus Periode
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Periode</h3>
            <p class="text-gray-500 mb-4">Silakan generate Acuan Gaji terlebih dahulu.</p>
            <a href="{{ route('payroll.acuan-gaji.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-arrow-left mr-2"></i>Ke Acuan Gaji
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
