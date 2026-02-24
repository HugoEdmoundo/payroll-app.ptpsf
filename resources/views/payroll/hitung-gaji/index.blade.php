@extends('layouts.app')

@section('title', 'Hitung Gaji')
@section('breadcrumb', 'Hitung Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hitung Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Salary calculation with adjustments and approval workflow</p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            @if(auth()->user()->hasPermission('hitung_gaji.create'))
            <a href="{{ route('payroll.hitung-gaji.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-plus mr-2"></i>Create Hitung Gaji
            </a>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Hitung Gaji Workflow:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Draft:</strong> Import data from Acuan Gaji (read-only) + add adjustments (editable)</li>
                    <li><strong>Preview:</strong> Review before approval (no editing)</li>
                    <li><strong>Approved:</strong> Final calculation, ready to generate Slip Gaji</li>
                    <li><strong>Adjustments:</strong> Every adjustment must have komponen, nominal, tipe (+/-), and deskripsi</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.hitung-gaji.index') }}">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Search by employee name...">
                    </div>
                </div>
                <div>
                    <input type="month" 
                           name="periode"
                           value="{{ request('periode') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <select name="status" 
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="preview" {{ request('status') == 'preview' ? 'selected' : '' }}>Preview</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('periode') || request('status'))
                <a href="{{ route('payroll.hitung-gaji.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Hitung Gaji Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.hitung-gaji.table', ['hitungGajiList' => $hitungGajiList])
    </div>

    <!-- Pagination -->
    @if($hitungGajiList->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $hitungGajiList->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
