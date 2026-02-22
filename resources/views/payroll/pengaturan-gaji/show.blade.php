@extends('layouts.app')

@section('title', 'Detail Pengaturan Gaji')
@section('breadcrumb', 'Detail Pengaturan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pengaturan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">
                ID: PG{{ str_pad($pengaturanGaji->id_pengaturan, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('payroll.pengaturan-gaji.edit', $pengaturanGaji->id_pengaturan) }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('payroll.pengaturan-gaji.destroy', $pengaturanGaji->id_pengaturan) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this configuration?')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="card p-6">
        @include('components.pengaturan-gaji.show', ['pengaturanGaji' => $pengaturanGaji])
    </div>
</div>
@endsection
