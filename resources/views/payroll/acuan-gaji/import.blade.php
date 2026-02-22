@extends('layouts.app')

@section('title', 'Import Acuan Gaji')
@section('breadcrumb', 'Import Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import Acuan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Upload Excel file to import salary reference data</p>
        </div>
        <a href="{{ route('payroll.acuan-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Instructions Card -->
    <div class="card p-6 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-2">Import Instructions:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Download the template by exporting existing data or use the correct format</li>
                    <li>Fill in the Excel file with your data</li>
                    <li>Required columns: <code class="bg-blue-100 px-1 rounded">nama_karyawan</code>, <code class="bg-blue-100 px-1 rounded">periode</code></li>
                    <li>Periode format: YYYY-MM (e.g., 2026-02)</li>
                    <li>If employee already has data for the periode, it will be updated</li>
                    <li>All numeric fields default to 0 if not provided</li>
                    <li>Total Pendapatan, Total Pengeluaran, and Gaji Bersih are auto-calculated</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Download Template -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Step 1: Download Template</h3>
        <p class="text-sm text-gray-600 mb-4">Export existing data to get the correct format, or start with empty template</p>
        <a href="{{ route('payroll.acuan-gaji.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-download mr-2"></i>Download Template (Export Current Data)
        </a>
    </div>

    <!-- Upload Form -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Step 2: Upload File</h3>
        <form method="POST" action="{{ route('payroll.acuan-gaji.import.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Excel File *
                    </label>
                    <input type="file" 
                           name="file" 
                           accept=".xlsx,.xls,.csv"
                           required
                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('file') border-red-500 @enderror">
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: .xlsx, .xls, .csv (Max: 2MB)</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('payroll.acuan-gaji.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700">
                        <i class="fas fa-upload mr-2"></i>Import Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Excel Format Reference -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Excel Column Reference</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Column Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Required</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Format</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Example</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono">nama_karyawan</td>
                        <td class="px-4 py-3 text-sm"><span class="text-red-600">Yes</span></td>
                        <td class="px-4 py-3 text-sm">Text</td>
                        <td class="px-4 py-3 text-sm">John Doe</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono">periode</td>
                        <td class="px-4 py-3 text-sm"><span class="text-red-600">Yes</span></td>
                        <td class="px-4 py-3 text-sm">YYYY-MM</td>
                        <td class="px-4 py-3 text-sm">2026-02</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono">gaji_pokok</td>
                        <td class="px-4 py-3 text-sm">No</td>
                        <td class="px-4 py-3 text-sm">Number</td>
                        <td class="px-4 py-3 text-sm">5000000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono">tunjangan_prestasi</td>
                        <td class="px-4 py-3 text-sm">No</td>
                        <td class="px-4 py-3 text-sm">Number</td>
                        <td class="px-4 py-3 text-sm">1000000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-500" colspan="4">
                            ... and all other numeric fields (see exported template for complete list)
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
