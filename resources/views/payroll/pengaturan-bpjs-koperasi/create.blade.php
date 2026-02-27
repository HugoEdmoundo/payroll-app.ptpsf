@extends('layouts.app')

@section('title', 'Add BPJS & Koperasi Configuration')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add BPJS & Koperasi Configuration</h1>
            <p class="mt-1 text-sm text-gray-600">Create new configuration</p>
        </div>
        <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="card p-6">
        <form action="{{ route('payroll.pengaturan-bpjs-koperasi.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai <span class="text-red-500">*</span></label>
                <select name="status_pegawai" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('status_pegawai') border-red-500 @enderror">
                    <option value="">Select Status</option>
                    <option value="Kontrak" {{ old('status_pegawai') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="OJT" {{ old('status_pegawai') == 'OJT' ? 'selected' : '' }}>OJT</option>
                </select>
                @error('status_pegawai')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Note: BPJS hanya untuk Kontrak, Koperasi untuk Kontrak & OJT</p>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">BPJS (Pendapatan - Hanya Kontrak)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                        <input type="number" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                        <input type="number" name="bpjs_kecelakaan_kerja" value="{{ old('bpjs_kecelakaan_kerja', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian</label>
                        <input type="number" name="bpjs_kematian" value="{{ old('bpjs_kematian', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT</label>
                        <input type="number" name="bpjs_jht" value="{{ old('bpjs_jht', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP</label>
                        <input type="number" name="bpjs_jp" value="{{ old('bpjs_jp', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Koperasi (Kontrak & OJT)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Koperasi</label>
                        <input type="number" name="koperasi" value="{{ old('koperasi', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('payroll.pengaturan-bpjs-koperasi.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
