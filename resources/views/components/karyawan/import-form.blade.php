<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <h4 class="font-medium text-blue-900 mb-2">
        <i class="fas fa-info-circle mr-2"></i>Import Instructions
    </h4>
    <ul class="text-sm text-blue-700 space-y-1 list-disc pl-5">
        <li>File harus dalam format Excel (.xlsx, .xls) atau CSV</li>
        <li>Kolom wajib: Nama Karyawan, Join Date, Jabatan, Lokasi Kerja, Jenis Karyawan, Status Pegawai, No Rekening, Bank, Status Karyawan</li>
        <li>Download template untuk panduan format</li>
    </ul>
</div>

<form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select File *</label>
            <input type="file" name="file" required accept=".xlsx,.xls,.csv"
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="skip_errors" id="skip_errors"
                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label for="skip_errors" class="ml-2 text-sm text-gray-700">
                Skip rows with errors and continue importing
            </label>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
        <a href="{{ route('karyawan.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            <i class="fas fa-upload mr-2"></i>Import Data
        </button>
    </div>
</form>