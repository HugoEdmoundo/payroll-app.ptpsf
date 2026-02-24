@extends('layouts.app')

@section('title', 'Detail Hitung Gaji')
@section('breadcrumb', 'Detail Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $hitungGaji->karyawan->nama_karyawan }} - {{ \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y') }}</p>
        </div>
        <div class="flex space-x-3">
            @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.edit'))
            <a href="{{ route('payroll.hitung-gaji.edit', $hitungGaji) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endif
            
            <a href="{{ route('payroll.hitung-gaji.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Status Card -->
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    @if($hitungGaji->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-edit mr-2"></i> Draft
                        </span>
                    @elseif($hitungGaji->status === 'preview')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i> Approved
                        </span>
                    @endif
                </div>
                
                @if($hitungGaji->status === 'approved')
                <div>
                    <p class="text-sm text-gray-600">Approved At</p>
                    <p class="text-sm font-medium text-gray-900">{{ $hitungGaji->approved_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>
            
            <div class="flex space-x-2">
                @if($hitungGaji->status === 'draft')
                    <form action="{{ route('payroll.hitung-gaji.preview', $hitungGaji) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-eye mr-2"></i>Preview
                        </button>
                    </form>
                @elseif($hitungGaji->status === 'preview')
                    <form action="{{ route('payroll.hitung-gaji.back-to-draft', $hitungGaji) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Draft
                        </button>
                    </form>
                    <form action="{{ route('payroll.hitung-gaji.approve', $hitungGaji) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Approve
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Name</p>
                <p class="text-sm font-medium text-gray-900">{{ $hitungGaji->karyawan->nama_karyawan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Position</p>
                <p class="text-sm font-medium text-gray-900">{{ $hitungGaji->karyawan->jabatan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Employee Type</p>
                <p class="text-sm font-medium text-gray-900">{{ $hitungGaji->karyawan->jenis_karyawan }}</p>
            </div>
        </div>
    </div>

    <!-- Pendapatan Section -->
    <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Pendapatan (Income)</h3>
        
        <!-- Pendapatan Acuan (Read-Only) -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                From Acuan Gaji (Read-Only)
            </h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->pendapatan_acuan as $key => $value)
                    @if($value > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($value, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endforeach
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total Pendapatan Acuan</span>
                        <span class="text-green-600">Rp {{ number_format($hitungGaji->total_pendapatan_acuan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penyesuaian Pendapatan -->
        @if($hitungGaji->penyesuaian_pendapatan && count($hitungGaji->penyesuaian_pendapatan) > 0)
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <i class="fas fa-edit text-indigo-500 mr-2"></i>
                Adjustments
            </h4>
            <div class="bg-indigo-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->penyesuaian_pendapatan as $item)
                <div class="flex justify-between items-start text-sm">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item['komponen'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $item['deskripsi'] }}</p>
                    </div>
                    <span class="font-medium {{ $item['tipe'] === '+' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item['tipe'] }} Rp {{ number_format($item['nominal'], 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
                <div class="border-t border-indigo-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total Penyesuaian</span>
                        <span class="{{ $hitungGaji->total_penyesuaian_pendapatan >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($hitungGaji->total_penyesuaian_pendapatan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Pendapatan Akhir -->
        <div class="mt-6 bg-green-100 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">TOTAL PENDAPATAN AKHIR</span>
                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($hitungGaji->total_pendapatan_akhir, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Pengeluaran Section -->
    <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengeluaran (Deductions)</h3>
        
        <!-- Pengeluaran Acuan (Read-Only) -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                From Acuan Gaji (Read-Only)
            </h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->pengeluaran_acuan as $key => $value)
                    @if($value > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($value, 0, ',', '.') }}</span>
                    </div>
                    @endif
                @endforeach
                <div class="border-t border-gray-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total Pengeluaran Acuan</span>
                        <span class="text-red-600">Rp {{ number_format($hitungGaji->total_pengeluaran_acuan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penyesuaian Pengeluaran -->
        @if($hitungGaji->penyesuaian_pengeluaran && count($hitungGaji->penyesuaian_pengeluaran) > 0)
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <i class="fas fa-edit text-indigo-500 mr-2"></i>
                Adjustments
            </h4>
            <div class="bg-indigo-50 rounded-lg p-4 space-y-2">
                @foreach($hitungGaji->penyesuaian_pengeluaran as $item)
                <div class="flex justify-between items-start text-sm">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item['komponen'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $item['deskripsi'] }}</p>
                    </div>
                    <span class="font-medium {{ $item['tipe'] === '+' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $item['tipe'] }} Rp {{ number_format($item['nominal'], 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
                <div class="border-t border-indigo-300 pt-2 mt-2">
                    <div class="flex justify-between text-sm font-medium">
                        <span class="text-gray-700">Total Penyesuaian</span>
                        <span class="{{ $hitungGaji->total_penyesuaian_pengeluaran >= 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($hitungGaji->total_penyesuaian_pengeluaran, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total Pengeluaran Akhir -->
        <div class="mt-6 bg-red-100 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">TOTAL PENGELUARAN AKHIR</span>
                <span class="text-2xl font-bold text-red-600">Rp {{ number_format($hitungGaji->total_pengeluaran_akhir, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Take Home Pay -->
    <div class="card p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-indigo-100 text-sm mb-1">TAKE HOME PAY</p>
                <p class="text-white text-4xl font-bold">Rp {{ number_format($hitungGaji->take_home_pay, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <i class="fas fa-money-bill-wave text-white text-5xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Catatan Umum -->
    @if($hitungGaji->catatan_umum)
    <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan Umum</h3>
        <p class="text-sm text-gray-700">{{ $hitungGaji->catatan_umum }}</p>
    </div>
    @endif
</div>
@endsection
