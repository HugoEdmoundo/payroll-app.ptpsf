@extends('layouts.app')

@section('title', 'Detail Karyawan')
@section('breadcrumb', 'Karyawan')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('karyawan.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Data Karyawan</a>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $karyawan->nama_karyawan }}</h1>
            <div class="mt-1 flex items-center space-x-2">
                <span class="text-sm text-gray-600">{{ $karyawan->jabatan }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-sm text-gray-600">{{ $karyawan->lokasi_kerja }}</span>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('karyawan.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Karyawan Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            K{{ str_pad($karyawan->id_karyawan, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $karyawan->join_date->format('d F Y') }}
                            <span class="text-xs text-gray-500 ml-1">
                                {{ $karyawan->join_date->format('H:i:s') }} WIB
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Masa Kerja</label>
                        <p class="mt-1 text-sm font-medium text-gray-900 font-mono">
                            <i class="far fa-hourglass-half text-blue-500 mr-1"></i>
                            {{ $karyawan->masa_kerja }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pegawai</label>
                        <p class="mt-1">
                            @if($karyawan->status_pegawai == 'Aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i> {{ $karyawan->status_pegawai }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i> {{ $karyawan->status_pegawai }}
                            </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Karyawan</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->jenis_karyawan }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Karyawan</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->status_karyawan }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Kerja</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->lokasi_kerja }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            @if($karyawan->email)
                                <i class="fas fa-envelope text-indigo-500 mr-1"></i>
                                <a href="mailto:{{ $karyawan->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $karyawan->email }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">No Telepon</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">
                            @if($karyawan->no_telp)
                                <i class="fas fa-phone text-green-500 mr-1"></i>
                                <a href="tel:{{ $karyawan->no_telp }}" class="text-green-600 hover:text-green-800">{{ $karyawan->no_telp }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bank & Financial Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bank & Financial Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->bank }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Account Number</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->no_rekening }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">NPWP</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->npwp ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">BPJS Kesehatan</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->bpjs_kesehatan_no ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">BPJS TK</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->bpjs_tk_no ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Status</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Employment Status</span>
                        @if($karyawan->status_pegawai == 'Aktif')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Employee Type</span>
                        <span class="text-sm font-medium text-gray-900">{{ $karyawan->jenis_karyawan }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Work Location</span>
                        <span class="text-sm font-medium text-gray-900">{{ $karyawan->lokasi_kerja }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Masa Kerja</span>
                        <span class="text-sm font-medium text-gray-900 font-mono bg-blue-50 px-2 py-1 rounded">
                            <i class="far fa-hourglass-half text-blue-500 mr-1"></i>
                            {{ $karyawan->masa_kerja }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Family Info -->
            @if($karyawan->status_perkawinan || $karyawan->nama_istri)
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Family Information</h2>
                <div class="space-y-3">
                    @if($karyawan->status_perkawinan)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Marital Status</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->status_perkawinan }}</p>
                    </div>
                    @endif
                    @if($karyawan->nama_istri)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Spouse Name</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->nama_istri }}</p>
                    </div>
                    @endif
                    @if($karyawan->jumlah_anak > 0)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Children</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->jumlah_anak }}</p>
                    </div>
                    @endif
                    @if($karyawan->no_telp_istri)
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Spouse Phone</label>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $karyawan->no_telp_istri }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    @if(auth()->user()->hasPermission('karyawan.edit'))
                    <a href="{{ route('karyawan.edit', $karyawan) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                        <i class="fas fa-edit mr-2"></i>Edit Karyawan
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('karyawan.delete'))
                    <form action="{{ route('karyawan.destroy', $karyawan) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this karyawan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 rounded-lg text-sm font-medium hover:bg-red-50 transition duration-150">
                            <i class="fas fa-trash mr-2"></i>Delete Karyawan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection