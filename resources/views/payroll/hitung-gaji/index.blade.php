@extends('layouts.app')

@section('title', 'Hitung Gaji')
@section('breadcrumb', 'Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Pilih periode untuk memproses perhitungan gaji</p>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Cara Kerja:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Pilih periode yang ingin diproses</li>
                    <li>Sistem akan menampilkan semua karyawan untuk periode tersebut</li>
                    <li>Data dari Acuan Gaji + perhitungan NKI & Absensi sudah otomatis terisi</li>
                    <li>Anda tinggal tambahkan adjustment jika diperlukan (optional)</li>
                    <li>Setelah selesai, approve untuk generate slip gaji</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Periode List -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Periode</h3>
        
        @if($periodes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($periodes as $item)
            <div class="border border-gray-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}
                        </h4>
                        <p class="text-sm text-gray-500">{{ $item['periode'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Karyawan:</span>
                        <span class="font-semibold text-gray-900">{{ $item['total_acuan'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Sudah Diproses:</span>
                        <span class="font-semibold text-green-600">{{ $item['total_hitung'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Belum Diproses:</span>
                        <span class="font-semibold text-orange-600">{{ $item['pending'] }}</span>
                    </div>
                </div>

                <!-- Status Badges -->
                @if($item['total_hitung'] > 0)
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($item['draft'] > 0)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        {{ $item['draft'] }} Draft
                    </span>
                    @endif
                    @if($item['preview'] > 0)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $item['preview'] }} Preview
                    </span>
                    @endif
                    @if($item['approved'] > 0)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        {{ $item['approved'] }} Approved
                    </span>
                    @endif
                </div>
                @endif

                <!-- Action Button -->
                <a href="{{ route('payroll.hitung-gaji.create', ['periode' => $item['periode']]) }}" 
                   class="block w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-center font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition">
                    <i class="fas fa-arrow-right mr-2"></i>Proses Periode Ini
                </a>
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
