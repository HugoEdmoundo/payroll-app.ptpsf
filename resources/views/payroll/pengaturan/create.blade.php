@extends('layouts.app')

@section('title', 'Tambah Pengaturan Gaji')
@section('breadcrumb', 'Payroll / Pengaturan Gaji / Create')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Pengaturan Gaji</h1>
        <a href="{{ route('payroll.pengaturan.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <form action="{{ route('payroll.pengaturan.store') }}" method="POST" class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 space-y-6">
        @csrf
        
        <!-- Basic Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Karyawan *</label>
                <select name="jenis_karyawan" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Jenis</option>
                    @foreach($jenisKaryawan as $jenis)
                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan *</label>
                <input type="text" name="jabatan" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah *</label>
                <select name="wilayah_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Wilayah</option>
                    @foreach($wilayah as $w)
                    <option value="{{ $w->id }}">{{ $w->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Gaji & Tunjangan -->
        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pendapatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gaji Pokok *</label>
                    <input type="number" name="gaji_pokok" required step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Operasional</label>
                    <input type="number" name="tunjangan_operasional" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Prestasi</label>
                    <input type="number" name="tunjangan_prestasi" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Konjungtur</label>
                    <input type="number" name="tunjangan_konjungtur" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Ibadah</label>
                    <input type="number" name="benefit_ibadah" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Komunikasi</label>
                    <input type="number" name="benefit_komunikasi" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Operasional</label>
                    <input type="number" name="benefit_operasional" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- BPJS -->
        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">BPJS</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kesehatan</label>
                    <input type="number" name="bpjs_kesehatan" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kecelakaan Kerja</label>
                    <input type="number" name="bpjs_kecelakaan_kerja" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Kematian</label>
                    <input type="number" name="bpjs_kematian" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JHT</label>
                    <input type="number" name="bpjs_jht" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BPJS JP</label>
                    <input type="number" name="bpjs_jp" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Potongan -->
        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Potongan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Potongan Koperasi</label>
                    <input type="number" name="potongan_koperasi" value="100000" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="border-t pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
            <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <a href="{{ route('payroll.pengaturan.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
