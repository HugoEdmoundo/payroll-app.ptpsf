@extends('layouts.app')

@section('title', 'Pengaturan Gaji Harian & OJT')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan Gaji Harian & OJT</h1>
            <p class="mt-1 text-sm text-gray-600">Configure salary for Harian and OJT employees by location</p>
        </div>
        <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Harian:</strong> Gaji pokok only (no BPJS, no Koperasi, no Tunjangan)<br>
                    <strong>OJT:</strong> Gaji pokok + Koperasi (no BPJS, no Tunjangan)<br>
                    <strong>Kontrak:</strong> Uses Pengaturan Gaji (Kontrak) module
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('payroll.pengaturan-gaji.status-pegawai.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- HARIAN Section -->
        <div class="card p-6 mb-6">
            <div class="flex items-center mb-6 pb-4 border-b">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-day text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Harian (Daily Workers)</h2>
                    <p class="text-sm text-gray-600">First 14 days - Gaji pokok only</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($settings['lokasi_kerja'] as $key => $lokasi)
                    @php
                        $harianData = $pengaturanGaji->get($lokasi)?->where('status_pegawai', 'Harian')->first();
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <input type="hidden" name="harian[{{ $key }}][lokasi_kerja]" value="{{ $lokasi }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja</label>
                            <input type="text" 
                                   value="{{ $lokasi }}" 
                                   disabled
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="harian[{{ $key }}][gaji_pokok]" 
                                       value="{{ old('harian.'.$key.'.gaji_pokok', $harianData->gaji_pokok ?? 90000) }}" 
                                       required
                                       min="0"
                                       step="0.01"
                                       class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500">
                            </div>
                            @error('harian.'.$key.'.gaji_pokok')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- OJT Section -->
        <div class="card p-6 mb-6">
            <div class="flex items-center mb-6 pb-4 border-b">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">OJT (On-the-Job Training)</h2>
                    <p class="text-sm text-gray-600">After 14 days, 3 months - Gaji pokok + Koperasi</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($settings['lokasi_kerja'] as $key => $lokasi)
                    @php
                        $ojtData = $pengaturanGaji->get($lokasi)?->where('status_pegawai', 'OJT')->first();
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <input type="hidden" name="ojt[{{ $key }}][lokasi_kerja]" value="{{ $lokasi }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja</label>
                            <input type="text" 
                                   value="{{ $lokasi }}" 
                                   disabled
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="ojt[{{ $key }}][gaji_pokok]" 
                                       value="{{ old('ojt.'.$key.'.gaji_pokok', $ojtData->gaji_pokok ?? 3100000) }}" 
                                       required
                                       min="0"
                                       step="0.01"
                                       class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            </div>
                            @error('ojt.'.$key.'.gaji_pokok')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
               class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition">
                <i class="fas fa-save mr-2"></i>Save Configuration
            </button>
        </div>
    </form>
</div>
@endsection
