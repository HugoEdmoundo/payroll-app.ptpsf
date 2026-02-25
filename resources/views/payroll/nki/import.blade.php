@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('payroll.nki.index') }}" 
                   class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Import Data NKI
                    </h1>
                    <p class="mt-2 text-gray-600">Upload file Excel untuk import data NKI</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('payroll.nki.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Excel *</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <a href="{{ route('payroll.nki.download-template') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                        <i class="fas fa-download mr-2"></i>Download Template Excel
                    </a>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                    <p class="text-sm text-blue-800 font-medium mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Format File Excel:
                    </p>
                    <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                        <li>NIK Karyawan</li>
                        <li>Periode (YYYY-MM)</li>
                        <li>Kemampuan (0-10)</li>
                        <li>Kontribusi 1 (0-10)</li>
                        <li>Kontribusi 2 (0-10)</li>
                        <li>Kedisiplinan (0-10)</li>
                        <li>Keterangan (optional)</li>
                    </ul>
                    <p class="text-xs text-blue-700 mt-2">
                        <strong>Catatan:</strong> Nilai NKI dan Persentase Tunjangan akan dihitung otomatis.
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('payroll.nki.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150 shadow-lg">
                        <i class="fas fa-file-import mr-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
