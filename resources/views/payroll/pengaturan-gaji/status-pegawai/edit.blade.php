@extends('layouts.app')

@section('title', 'Edit Pengaturan Gaji Status Pegawai')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pengaturan Gaji Status Pegawai</h1>
            <p class="mt-1 text-sm text-gray-600">Update salary configuration for employee status</p>
        </div>
        <a href="{{ route('payroll.pengaturan-gaji.status-pegawai.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form -->
    <div class="card p-6">
        <form action="{{ route('payroll.pengaturan-gaji.status-pegawai.update', $pengaturanGaji->id_pengaturan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Pegawai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pegawai *</label>
                    <select name="status_pegawai" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih Status Pegawai</option>
                        @foreach($settings['status_pegawai'] as $status)
                            <option value="{{ $status }}" {{ old('status_pegawai', $pengaturanGaji->status_pegawai) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_pegawai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi Kerja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kerja *</label>
                    <select name="lokasi_kerja" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih Lokasi Kerja</option>
                        @foreach($settings['lokasi_kerja'] as $key => $value)
                            <option value="{{ $value }}" {{ old('lokasi_kerja', $pengaturanGaji->lokasi_kerja) == $value ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_kerja')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gaji Pokok -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok *</label>
                    <div class="relative">
                        <input type="number" 
                               name="gaji_pokok" 
                               value="{{ old('gaji_pokok', $pengaturanGaji->gaji_pokok) }}" 
                               required
                               min="0"
                               step="0.01"
                               class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    @error('gaji_pokok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" 
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                              placeholder="Optional notes...">{{ old('keterangan', $pengaturanGaji->keterangan) }}</textarea>
                    @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>  </div>
                    @error('gaji_pokok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" 
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                              placeholder="Optional notes...">{{ old('keterangan', $pengaturanGaji->keterangan) }}</textarea>
                    @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('payroll.pengaturan-gaji.status-pegawai.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition">
                    <i class="fas fa-save mr-2"></i>Update Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
