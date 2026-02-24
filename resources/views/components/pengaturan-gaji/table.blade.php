@props(['pengaturanGaji' => []])

@if($pengaturanGaji->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Karyawan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Pokok</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">BPJS Total</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gaji Nett</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gaji</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($pengaturanGaji as $pg)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded">
                        PG{{ str_pad($pg->id_pengaturan, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($pg->jenis_karyawan == 'Konsultan') bg-purple-100 text-purple-800
                        @elseif($pg->jenis_karyawan == 'Organik') bg-blue-100 text-blue-800
                        @elseif($pg->jenis_karyawan == 'Teknisi') bg-green-100 text-green-800
                        @else bg-orange-100 text-orange-800
                        @endif">
                        {{ $pg->jenis_karyawan }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $pg->jabatan }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $pg->lokasi_kerja }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                    Rp {{ number_format($pg->gaji_pokok, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-right font-mono font-semibold">
                    Rp {{ number_format($pg->bpjs_total, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                    Rp {{ number_format($pg->gaji_nett, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600 text-right font-mono">
                    Rp {{ number_format($pg->total_gaji, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('payroll.pengaturan-gaji.show', $pg->id_pengaturan) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if(auth()->user()->hasPermission('pengaturan_gaji.edit'))
                        <a href="{{ route('payroll.pengaturan-gaji.edit', $pg->id_pengaturan) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasPermission('pengaturan_gaji.delete'))
                        <form action="{{ route('payroll.pengaturan-gaji.destroy', $pg->id_pengaturan) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus pengaturan gaji ini?')"
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
        <i class="fas fa-money-bill-wave text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengaturan gaji</h3>
    <p class="text-gray-500 mb-6">Mulai dengan membuat pengaturan gaji baru.</p>
    @if(auth()->user()->hasPermission('pengaturan_gaji.create'))
    <a href="{{ route('payroll.pengaturan-gaji.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Tambah Pengaturan Gaji
    </a>
    @endif
</div>
@endif
