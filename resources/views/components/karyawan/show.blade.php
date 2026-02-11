@props(['karyawan'])

<div class="bg-white rounded-lg border border-gray-200 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Full Name:</span>
                    <p class="mt-1">{{ $karyawan->nama_karyawan }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Join Date:</span>
                    <p class="mt-1">{{ $karyawan->join_date->format('d F Y') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Employee Type:</span>
                    <p class="mt-1">{{ $karyawan->jenis_karyawan }}</p>
                </div>
            </div>
        </div>
        
        <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Employment Details</h4>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Position:</span>
                    <p class="mt-1">{{ $karyawan->jabatan }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Work Location:</span>
                    <p class="mt-1">{{ $karyawan->lokasi_kerja }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Status:</span>
                    <p class="mt-1">
                        @if($karyawan->status_pegawai == 'Aktif')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 pt-6 border-t border-gray-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <span class="text-sm font-medium text-gray-500">Bank:</span>
                <p class="mt-1">{{ $karyawan->bank }}</p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">Account Number:</span>
                <p class="mt-1">{{ $karyawan->no_rekening }}</p>
            </div>
        </div>
    </div>
</div>