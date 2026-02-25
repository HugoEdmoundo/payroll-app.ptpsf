@extends('layouts.app')

@section('title', 'Acuan Gaji')
@section('breadcrumb', 'Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Acuan Gaji</h1>
                <p class="mt-1 text-sm text-gray-600">Pilih periode untuk melihat data acuan gaji</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('payroll.acuan-gaji.manage-periode') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <i class="fas fa-cog mr-2"></i>Kelola Periode
                </a>
                
                @if(auth()->user()->hasPermission('acuan_gaji.import'))
                <a href="{{ route('payroll.acuan-gaji.import') }}" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <i class="fas fa-upload mr-2"></i>Import
                </a>
                @endif
                
                @if(auth()->user()->hasPermission('acuan_gaji.create'))
                <button onclick="document.getElementById('generateModal').classList.remove('hidden')" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 shadow-sm transition">
                    <i class="fas fa-magic mr-2"></i>Generate
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Periode Cards -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Periode</h3>
        
        @if($periodes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($periodes as $item)
            <a href="{{ route('payroll.acuan-gaji.periode', $item['periode']) }}" 
               class="block border border-gray-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $item['periode'])->format('F Y') }}
                        </h4>
                        <p class="text-xs text-gray-500">{{ $item['periode'] }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <span class="text-sm text-gray-600">Total Karyawan</span>
                    <span class="text-lg font-bold text-indigo-600">{{ $item['total_karyawan'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Periode</h3>
            <p class="text-gray-500 mb-4">Silakan generate atau import acuan gaji terlebih dahulu.</p>
        </div>
        @endif
    </div>
</div>

<!-- Generate Modal -->
<div id="generateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Generate Acuan Gaji</h3>
            <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('payroll.acuan-gaji.generate') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                <input type="month" 
                       name="periode" 
                       required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('generateModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700">
                    <i class="fas fa-cog mr-2"></i>Generate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
