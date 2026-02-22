@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <a href="{{ route('payroll.absensi.index') }}" 
                       class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Detail Absensi
                        </h1>
                        <p class="mt-2 text-gray-600">
                            ID: ABS{{ str_pad($absensi->id_absensi, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('payroll.absensi.edit', $absensi->id_absensi) }}" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('payroll.absensi.destroy', $absensi->id_absensi) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <x-absensi.show :absensi="$absensi" />
        </div>
    </div>
</div>
@endsection
