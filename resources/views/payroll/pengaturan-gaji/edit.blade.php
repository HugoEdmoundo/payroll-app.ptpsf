@extends('layouts.app')

@section('title', 'Edit Pengaturan Gaji')
@section('breadcrumb', 'Edit Pengaturan Gaji')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Salary Configuration</h1>
            <p class="mt-1 text-sm text-gray-600">Update salary configuration template</p>
        </div>
        <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="card p-6">
        <form action="{{ route('payroll.pengaturan-gaji.update', $pengaturanGaji->id_pengaturan) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('components.pengaturan-gaji.form', ['pengaturanGaji' => $pengaturanGaji, 'settings' => $settings])
            
            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('payroll.pengaturan-gaji.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <i class="fas fa-save mr-2"></i>Update Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
