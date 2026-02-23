@props(['acuanGajiList' => []])

@if($acuanGajiList->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gaji Pokok</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pengeluaran</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gaji Bersih</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($acuanGajiList as $acuan)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $acuan->karyawan->nama_karyawan ?? '-' }}</div>
                    <div class="text-sm text-gray-500">{{ $acuan->karyawan->jenis_karyawan ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $acuan->periode)->format('F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                    Rp {{ number_format($acuan->gaji_pokok, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-right font-mono font-semibold">
                    Rp {{ number_format($acuan->total_pendapatan, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right font-mono font-semibold">
                    Rp {{ number_format($acuan->total_pengeluaran, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 text-right font-mono font-bold">
                    Rp {{ number_format($acuan->gaji_bersih, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('payroll.acuan-gaji.show', $acuan->id_acuan) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if(auth()->user()->hasPermission('acuan_gaji.edit'))
                        <a href="{{ route('payroll.acuan-gaji.edit', $acuan->id_acuan) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasPermission('acuan_gaji.delete'))
                        <form action="{{ route('payroll.acuan-gaji.destroy', $acuan->id_acuan) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus acuan gaji ini?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada acuan gaji</h3>
    <p class="text-gray-500 mb-6">Mulai dengan membuat acuan gaji baru.</p>
    @if(auth()->user()->hasPermission('acuan_gaji.create'))
    <a href="{{ route('payroll.acuan-gaji.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Tambah Acuan Gaji
    </a>
    @endif
</div>
@endif
