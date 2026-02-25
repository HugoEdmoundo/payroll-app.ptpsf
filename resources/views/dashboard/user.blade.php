@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="card p-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-indigo-100">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
    </div>

    <!-- Stats Grid - Top Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Karyawan -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Karyawan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_karyawan']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Active employees</p>
                </div>
                <div class="h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="card p-6 bg-gradient-to-br from-red-50 to-pink-50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600">Total Pengeluaran</p>
                    <p class="text-3xl font-bold text-red-700 mt-2">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}</p>
                    <p class="text-xs text-red-500 mt-1">All expenses</p>
                </div>
                <div class="h-14 w-14 rounded-full bg-red-200 flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengeluaran Details - 4 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Teknisi & Borongan -->
        <div class="card p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-tools text-blue-600"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Teknisi & Borongan</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($pengeluaran['teknisi_borongan'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Gaji bersih</p>
        </div>

        <!-- Konsultan & Organik -->
        <div class="card p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-user-tie text-purple-600"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Konsultan & Organik</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($pengeluaran['konsultan_organik'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Gaji bersih</p>
        </div>

        <!-- BPJS -->
        <div class="card p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-green-600"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Pengeluaran BPJS</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($pengeluaran['bpjs'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total nett BPJS</p>
        </div>

        <!-- Koperasi -->
        <div class="card p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-3">
                <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-store text-orange-600"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Tagihan Koperasi</p>
            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($pengeluaran['koperasi'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total koperasi</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-6 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Dashboard Information</p>
                <p>Data ditampilkan berdasarkan periode <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</strong>. Total pengeluaran merupakan gabungan dari gaji bersih teknisi & borongan, konsultan & organik, BPJS, dan tagihan koperasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
