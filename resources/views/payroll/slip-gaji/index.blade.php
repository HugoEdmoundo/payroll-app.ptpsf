@extends('layouts.app')

@section('title', 'Slip Gaji')
@section('breadcrumb', 'Slip Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Slip Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Pilih periode untuk melihat slip gaji karyawan</p>
        </div>
    </div>

    <!-- Periode Cards -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Periode</h3>
        
        @if($periodes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($periodes as $item)
            <a href="{{ route('payroll.slip-gaji.periode', $item['periode']) }}" 
               class="block border border-gray-200 rounded-lg p-5 hover:border-purple-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}
                        </h4>
                        <p class="text-xs text-gray-500">{{ $item['periode'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-file-invoice text-white text-xl"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <span class="text-sm text-gray-600">Total Karyawan</span>
                    <span class="text-lg font-bold text-purple-600">{{ $item['total_karyawan'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-file-invoice text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Slip Gaji</h3>
            <p class="text-gray-500 mb-4">Silakan selesaikan Hitung Gaji terlebih dahulu.</p>
            <a href="{{ route('payroll.hitung-gaji.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-arrow-left mr-2"></i>Ke Hitung Gaji
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
