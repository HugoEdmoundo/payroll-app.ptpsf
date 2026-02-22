@extends('layouts.app')

@section('title', 'Add Absensi')
@section('breadcrumb', 'Add Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Attendance Record</h1>
            <p class="mt-1 text-sm text-gray-600">Create new attendance record</p>
        </div>
        <a href="{{ route('payroll.absensi.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="card p-6">
        <form action="{{ route('payroll.absensi.store') }}" method="POST">
            @csrf
            
            @include('components.absensi.form', ['absensi' => null, 'karyawanList' => $karyawanList])
            
            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('payroll.absensi.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <i class="fas fa-save mr-2"></i>Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
