@extends('layouts.app')

@section('title', 'Edit NKI')
@section('breadcrumb', 'Edit NKI')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit NKI Record</h1>
            <p class="mt-1 text-sm text-gray-600">Update performance rating record</p>
        </div>
        <a href="{{ route('payroll.nki.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="card p-6">
        <form action="{{ route('payroll.nki.update', $nki->id_nki) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('components.nki.form', ['nki' => $nki, 'karyawanList' => $karyawanList])
            
            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('payroll.nki.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <i class="fas fa-save mr-2"></i>Update NKI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
