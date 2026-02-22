@extends('layouts.app')

@section('title', 'Edit Acuan Gaji')
@section('breadcrumb', 'Edit Acuan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Acuan Gaji</h1>
            <p class="mt-1 text-sm text-gray-600">Update salary reference data</p>
        </div>
        <a href="{{ route('payroll.acuan-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form -->
    <div class="card p-6">
        <form method="POST" action="{{ route('payroll.acuan-gaji.update', $acuanGaji->id_acuan) }}">
            @csrf
            @method('PUT')
            @include('components.acuan-gaji.form', ['acuanGaji' => $acuanGaji, 'karyawanList' => $karyawanList])
            
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('payroll.acuan-gaji.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
