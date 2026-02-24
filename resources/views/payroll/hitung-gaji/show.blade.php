@extends('layouts.app')

@section('title', 'Hitung Gaji Detail')
@section('breadcrumb', 'Hitung Gaji Detail')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hitung Gaji Detail</h1>
            <p class="mt-1 text-sm text-gray-600">View salary calculation details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('payroll.hitung-gaji.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            
            @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.edit'))
            <a href="{{ route('payroll.hitung-gaji.edit', $hitungGaji) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endif
            
            @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.edit'))
            <form action="{{ route('payroll.hitung-gaji.preview', $hitungGaji) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                    <i class="fas fa-eye mr-2"></i>Preview
                </button>
            </form>
            @endif
            
            @if($hitungGaji->status === 'preview' && auth()->user()->hasPermission('hitung_gaji.edit'))
            <form action="{{ route('payroll.hitung-gaji.back-to-draft', $hitungGaji) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg text-sm font-medium hover:bg-yellow-700">
                    <i class="fas fa-undo mr-2"></i>Back to Draft
                </button>
            </form>
            @endif
            
            @if($hitungGaji->status === 'preview' && auth()->user()->hasPermission('hitung_gaji.approve'))
            <form action="{{ route('payroll.hitung-gaji.approve', $hitungGaji) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to approve this? This action cannot be undone.')"
                  class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-check-circle mr-2"></i>Approve
                </button>
            </form>
            @endif
            
            @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.delete'))
            <form action="{{ route('payroll.hitung-gaji.destroy', $hitungGaji) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this?')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="card p-6">
        <x-hitung-gaji.show :hitungGaji="$hitungGaji" />
    </div>
</div>
@endsection
