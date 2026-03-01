@extends('layouts.app')

@section('title', 'Pengaturan BPJS & Koperasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan BPJS & Koperasi</h1>
            <p class="mt-1 text-sm text-gray-600">Konfigurasi global untuk BPJS dan Koperasi</p>
        </div>
        <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="card p-6">
        <form action="{{ route('payroll.pengaturan-bpjs-koperasi.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Catatan Penting:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>BPJS Pendapatan: Otomatis diterapkan untuk karyawan <strong>Kontrak</strong></li>
                            <li>Koperasi: Otomatis diterapkan untuk karyawan <strong>Kontrak</strong> dan <strong>OJT</strong></li>
                            <li>Karyawan Harian tidak mendapat BPJS dan Koperasi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- BPJS Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b flex items-center">
                    <i class="fas fa-hospital text-green-600 mr-2"></i>
                    BPJS (Pendapatan - Untuk Kontrak)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                        <div class="relative">
                            <input type="number" 
                                   name="bpjs_kesehatan_pendapatan" 
                                   value="{{ old('bpjs_kesehatan_pendapatan', $pengaturanBpjsKoperasi->bpjs_kesehatan_pendapatan) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                        <div class="relative">
                            <input type="number" 
                                   name="bpjs_kecelakaan_kerja_pendapatan" 
                                   value="{{ old('bpjs_kecelakaan_kerja_pendapatan', $pengaturanBpjsKoperasi->bpjs_kecelakaan_kerja_pendapatan) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian</label>
                        <div class="relative">
                            <input type="number" 
                                   name="bpjs_kematian_pendapatan" 
                                   value="{{ old('bpjs_kematian_pendapatan', $pengaturanBpjsKoperasi->bpjs_kematian_pendapatan) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT</label>
                        <div class="relative">
                            <input type="number" 
                                   name="bpjs_jht_pendapatan" 
                                   value="{{ old('bpjs_jht_pendapatan', $pengaturanBpjsKoperasi->bpjs_jht_pendapatan) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP</label>
                        <div class="relative">
                            <input type="number" 
                                   name="bpjs_jp_pendapatan" 
                                   value="{{ old('bpjs_jp_pendapatan', $pengaturanBpjsKoperasi->bpjs_jp_pendapatan) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Koperasi Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b flex items-center">
                    <i class="fas fa-handshake text-blue-600 mr-2"></i>
                    Koperasi (Untuk Kontrak & OJT)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Koperasi</label>
                        <div class="relative">
                            <input type="number" 
                                   name="koperasi" 
                                   value="{{ old('koperasi', $pengaturanBpjsKoperasi->koperasi) }}" 
                                   min="0" 
                                   step="0.01" 
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
