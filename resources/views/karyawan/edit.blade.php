@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('breadcrumb', 'Karyawan')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('karyawan.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Data Karyawan</a>
</li>
<li class="inline-flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <a href="{{ route('karyawan.show', $karyawan) }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">{{ $karyawan->nama_karyawan }}</a>
</li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Karyawan</h1>
            <p class="mt-1 text-sm text-gray-600">Update employee information</p>
        </div>
        
        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('components.karyawan.form', ['karyawan' => $karyawan, 'settings' => $settings])
            
            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('karyawan.show', $karyawan) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Update Karyawan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection