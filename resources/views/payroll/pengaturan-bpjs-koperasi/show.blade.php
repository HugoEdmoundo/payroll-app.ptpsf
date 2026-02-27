@extends('layouts.app')

@section('title', 'BPJS & Koperasi Configuration Detail')
@section('breadcrumb', 'BPJS & Koperasi Configuration Detail')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">BPJS & Koperasi Configuration Detail</h1>
            <p class="mt-1 text-sm text-gray-600">View configuration details</p>
        </div>
        <div class="flex items-center space-x-2">
            @if(auth()->user()->hasPermission('pengaturan_gaji.edit'))
            <a href="{{ route('payroll.pengaturan-bpjs-koperasi.edit', $pengaturanBpjsKoperasi) }}" 
               class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endif
            <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Basic Info -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Karyawan</label>
                <p class="text-base text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $pengaturanBpjsKoperasi->jenis_karyawan }}
                    </span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status Pegawai</label>
                <p class="text-base text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $pengaturanBpjsKoperasi->status_pegawai == 'Kontrak' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $pengaturanBpjsKoperasi->status_pegawai == 'OJT' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $pengaturanBpjsKoperasi->status_pegawai == 'Harian' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ $pengaturanBpjsKoperasi->status_pegawai }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- BPJS Pendapatan -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">BPJS (Pendapatan)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kesehatan</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kesehatan_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kecelakaan Kerja</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kecelakaan_kerja_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kematian</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kematian_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS JHT</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_jht_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS JP</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_jp_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Total BPJS Pendapatan</label>
                <p class="text-lg font-semibold text-green-600">Rp {{ number_format($pengaturanBpjsKoperasi->total_bpjs_pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- BPJS Pengeluaran -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">BPJS (Pengeluaran/Potongan)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kesehatan</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kesehatan_pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kecelakaan Kerja</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kecelakaan_kerja_pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS Kematian</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_kematian_pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS JHT</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_jht_pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">BPJS JP</label>
                <p class="text-base text-gray-900">Rp {{ number_format($pengaturanBpjsKoperasi->bpjs_jp_pengeluaran, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Total BPJS Pengeluaran</label>
                <p class="text-lg font-semibold text-red-600">Rp {{ number_format($pengaturanBpjsKoperasi->total_bpjs_pengeluaran, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Koperasi -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Koperasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Potongan Koperasi</label>
                <p class="text-lg font-semibold text-red-600">Rp {{ number_format($pengaturanBpjsKoperasi->koperasi, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Timestamps</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                <p class="text-base text-gray-900">{{ $pengaturanBpjsKoperasi->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                <p class="text-base text-gray-900">{{ $pengaturanBpjsKoperasi->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
