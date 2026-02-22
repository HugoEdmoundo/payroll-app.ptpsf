@extends('layouts.app')

@section('title', 'Import Karyawan')
@section('breadcrumb', 'Karyawan')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('karyawan.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Data Karyawan</a>
</li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Import Karyawan</h1>
            <p class="mt-1 text-sm text-gray-600">Upload Excel file to import multiple karyawan</p>
        </div>

        <!-- Instructions -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Import Instructions</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Download the template file first</li>
                            <li>Fill in the data according to the format</li>
                            <li>Ensure required fields are filled (nama_karyawan, join_date, jabatan, lokasi_kerja, jenis_karyawan, status_pegawai, no_rekening, bank, status_karyawan)</li>
                            <li>Email and no_telp are optional fields</li>
                            <li>Supported format: .xlsx, .xls, .csv</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Template -->
        <div class="mb-6">
            <a href="{{ route('karyawan.download-template') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition duration-150">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
        </div>

        <!-- Import Form -->
        <form action="{{ route('karyawan.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Excel File</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" 
                                           name="file" 
                                           type="file" 
                                           class="sr-only" 
                                           accept=".xlsx,.xls,.csv" 
                                           required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">Excel/CSV files</p>
                        </div>
                    </div>
                    @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('karyawan.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <i class="fas fa-upload mr-2"></i>Import Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
