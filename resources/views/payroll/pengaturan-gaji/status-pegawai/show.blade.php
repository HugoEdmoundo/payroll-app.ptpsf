@extends('layouts.app')

@section('title', 'Detail Pengaturan Gaji Status Pegawai')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pengaturan Gaji Status Pegawai</h1>
            <p class="mt-1 text-sm text-gray-600">View salary configuration details</p>
        </div>
        <div class="flex space-x-2">
            @if(auth()->user()->hasPermission('pengaturan_gaji.edit'))
            <a href="{{ route('payroll.pengaturan-gaji.status-pegawai.edit', $pengaturanGaji->id_pengaturan) }}" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endif
            <a href="{{ route('payroll.pengaturan-gaji.status-pegawai.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="card p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Pegawai -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status Pegawai</label>
                <p class="text-lg font-semibold text-gray-900">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $pengaturanGaji->status_pegawai === 'Harian' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $pengaturanGaji->status_pegawai }}
                    </span>
                </p>
            </div>

            <!-- Lokasi Kerja -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi Kerja</label>
                <p class="text-lg font-semibold text-gray-900">{{ $pengaturanGaji->lokasi_kerja }}</p>
            </div>

            <!-- Gaji Pokok -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Gaji Pokok</label>
                <p class="text-2xl font-bold text-indigo-600">
                    Rp {{ number_format($pengaturanGaji->gaji_pokok, 0, ',', '.') }}
                </p>
            </div>

            <!-- Keterangan -->
            @if($pengaturanGaji->keterangan)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
                <p class="text-gray-900">{{ $pengaturanGaji->keterangan }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="md:col-span-2 pt-4 border-t">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-500">
                    <div>
                        <span class="font-medium">Created:</span> 
                        {{ $pengaturanGaji->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> 
                        {{ $pengaturanGaji->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Pegawai -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status Pegawai</label>
                <p class="text-lg font-semibold text-gray-900">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $pengaturanGaji->status_pegawai === 'Harian' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $pengaturanGaji->status_pegawai }}
                    </span>
                </p>
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jabatan</label>
                <p class="text-lg font-semibold text-gray-900">{{ $pengaturanGaji->jabatan }}</p>
            </div>

            <!-- Lokasi Kerja -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi Kerja</label>
                <p class="text-lg font-semibold text-gray-900">{{ $pengaturanGaji->lokasi_kerja }}</p>
            </div>

            <!-- Gaji Pokok -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Gaji Pokok</label>
                <p class="text-2xl font-bold text-indigo-600">
                    Rp {{ number_format($pengaturanGaji->gaji_pokok, 0, ',', '.') }}
                </p>
            </div>

            <!-- Keterangan -->
            @if($pengaturanGaji->keterangan)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
                <p class="text-gray-900">{{ $pengaturanGaji->keterangan }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="md:col-span-2 pt-4 border-t">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-500">
                    <div>
                        <span class="font-medium">Created:</span> 
                        {{ $pengaturanGaji->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> 
                        {{ $pengaturanGaji->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
