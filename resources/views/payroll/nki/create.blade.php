@extends('layouts.app')

@section('title', 'Tambah NKI')
@section('breadcrumb', 'Payroll / NKI / Create')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Penilaian NKI</h1>
        <a href="{{ route('payroll.nki.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <form action="{{ route('payroll.nki.store') }}" method="POST" class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan *</label>
                <select name="karyawan_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Karyawan</option>
                    @foreach($karyawan as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }} - {{ $k->jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode *</label>
                <input type="month" name="periode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Komponen Penilaian (Skala 0-10)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kemampuan (20%) *</label>
                    <input type="number" name="kemampuan" required step="0.01" min="0" max="10" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kontribusi (20%) *</label>
                    <input type="number" name="konstribusi" required step="0.01" min="0" max="10" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kedisiplinan (40%) *</label>
                    <input type="number" name="kedisiplinan" required step="0.01" min="0" max="10" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lainnya (20%) *</label>
                    <input type="number" name="lainnya" required step="0.01" min="0" max="10" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-indigo-900 mb-2">Rumus Perhitungan:</h4>
            <p class="text-sm text-indigo-700">NKI = (Kemampuan × 20%) + (Kontribusi × 20%) + (Kedisiplinan × 40%) + (Lainnya × 20%)</p>
            <p class="text-sm text-indigo-700 mt-2">Persentase Prestasi:</p>
            <ul class="text-sm text-indigo-700 ml-4 list-disc">
                <li>NKI ≥ 8.5 → 100%</li>
                <li>NKI ≥ 8.0 → 80%</li>
                <li>NKI ≥ 7.0 → 70%</li>
            </ul>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
            <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            <a href="{{ route('payroll.nki.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
