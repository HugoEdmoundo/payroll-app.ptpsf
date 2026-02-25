@extends('layouts.app')

@section('title', 'Kasbon')
@section('breadcrumb', 'Kasbon')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kasbon</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Employee loan management with direct deduction or installment payment</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->hasPermission('kasbon.export'))
            <a href="{{ route('payroll.kasbon.export') }}" 
               class="px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 whitespace-nowrap">
                <i class="fas fa-download mr-1.5"></i>Export
            </a>
            @endif
            
            @if(auth()->user()->hasPermission('kasbon.create'))
            <a href="{{ route('payroll.kasbon.create') }}" 
               class="px-3 py-2 text-xs sm:text-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 whitespace-nowrap">
                <i class="fas fa-plus mr-1.5"></i>Add Kasbon
            </a>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card p-4 bg-blue-50 border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Kasbon Types:</p>
                <ul class="list-disc list-inside">
                    <li><strong>Langsung:</strong> Deducted directly from salary in one payment</li>
                    <li><strong>Cicilan:</strong> Paid in installments over multiple months</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('payroll.kasbon.index') }}">
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
                               placeholder="Search by employee or description...">
                    </div>
                </div>
                <div>
                    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <select name="metode" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Methods</option>
                        <option value="Langsung" {{ request('metode') == 'Langsung' ? 'selected' : '' }}>Langsung</option>
                        <option value="Cicilan" {{ request('metode') == 'Cicilan' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                @if(request('search') || request('status') || request('metode'))
                <a href="{{ route('payroll.kasbon.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Kasbon Table -->
    <div class="card p-0 overflow-hidden">
        @include('components.kasbon.table', ['kasbonList' => $kasbonList])
    </div>

    <!-- Pagination -->
    @if($kasbonList->hasPages())
    <div class="flex justify-center">
        <div class="inline-flex rounded-md shadow-sm">
            {{ $kasbonList->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
