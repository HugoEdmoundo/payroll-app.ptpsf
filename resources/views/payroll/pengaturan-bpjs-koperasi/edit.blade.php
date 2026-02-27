@extends('layouts.app')

@section('title', 'Edit BPJS & Koperasi Configuration')
@section('breadcrumb', 'Edit BPJS & Koperasi Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit BPJS & Koperasi Configuration</h1>
            <p class="mt-1 text-sm text-gray-600">Update BPJS and Koperasi configuration</p>
        </div>
        <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form -->
    <div class="card p-6">
        <form action="{{ route('payroll.pengaturan-bpjs-koperasi.update', $pengaturanBpjsKoperasi) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Employee Type & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan <span class="text-red-500">*</span></label>
                    <select name="jenis_karyawan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('jenis_karyawan') border-red-500 @enderror">
                        <option value="">Select Employee Type</option>
                        @foreach($settings['jenis_karyawan'] as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_karyawan', $pengaturanBpjsKoperasi->jenis_karyawan) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_karyawan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai <span class="text-red-500">*</span></label>
                    <select name="status_pegawai" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status_pegawai') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        @foreach($settings['status_pegawai'] as $status)
                        <option value="{{ $status }}" {{ old('status_pegawai', $pengaturanBpjsKoperasi->status_pegawai) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status_pegawai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- BPJS Pendapatan Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">BPJS (Pendapatan)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                        <input type="number" name="bpjs_kesehatan_pendapatan" value="{{ old('bpjs_kesehatan_pendapatan', $pengaturanBpjsKoperasi->bpjs_kesehatan_pendapatan) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                        <input type="number" name="bpjs_kecelakaan_kerja_pendapatan" value="{{ old('bpjs_kecelakaan_kerja_pendapatan', $pengaturanBpjsKoperasi->bpjs_kecelakaan_kerja_pendapatan) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian</label>
                        <input type="number" name="bpjs_kematian_pendapatan" value="{{ old('bpjs_kematian_pendapatan', $pengaturanBpjsKoperasi->bpjs_kematian_pendapatan) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT</label>
                        <input type="number" name="bpjs_jht_pendapatan" value="{{ old('bpjs_jht_pendapatan', $pengaturanBpjsKoperasi->bpjs_jht_pendapatan) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP</label>
                        <input type="number" name="bpjs_jp_pendapatan" value="{{ old('bpjs_jp_pendapatan', $pengaturanBpjsKoperasi->bpjs_jp_pendapatan) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- BPJS Pengeluaran Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">BPJS (Pengeluaran/Potongan)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                        <input type="number" name="bpjs_kesehatan_pengeluaran" value="{{ old('bpjs_kesehatan_pengeluaran', $pengaturanBpjsKoperasi->bpjs_kesehatan_pengeluaran) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                        <input type="number" name="bpjs_kecelakaan_kerja_pengeluaran" value="{{ old('bpjs_kecelakaan_kerja_pengeluaran', $pengaturanBpjsKoperasi->bpjs_kecelakaan_kerja_pengeluaran) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian</label>
                        <input type="number" name="bpjs_kematian_pengeluaran" value="{{ old('bpjs_kematian_pengeluaran', $pengaturanBpjsKoperasi->bpjs_kematian_pengeluaran) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT</label>
                        <input type="number" name="bpjs_jht_pengeluaran" value="{{ old('bpjs_jht_pengeluaran', $pengaturanBpjsKoperasi->bpjs_jht_pengeluaran) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP</label>
                        <input type="number" name="bpjs_jp_pengeluaran" value="{{ old('bpjs_jp_pengeluaran', $pengaturanBpjsKoperasi->bpjs_jp_pengeluaran) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Koperasi Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Koperasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Koperasi</label>
                        <input type="number" name="koperasi" value="{{ old('koperasi', $pengaturanBpjsKoperasi->koperasi) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Update Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
