@props(['nkiList' => []])

@if($nkiList->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kemampuan (20%)</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kontribusi 1 (20%)</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kontribusi 2 (40%)</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kedisiplinan (20%)</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai NKI</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Persentase</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($nkiList as $nki)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $nki->karyawan->nama_karyawan ?? '-' }}</div>
                    <div class="text-sm text-gray-500">{{ $nki->karyawan->jenis_karyawan ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $nki->periode)->format('F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    {{ number_format($nki->kemampuan, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    {{ number_format($nki->kontribusi_1, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    {{ number_format($nki->kontribusi_2, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    {{ number_format($nki->kedisiplinan, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-lg font-bold text-indigo-600">
                        {{ number_format($nki->nilai_nki, 2) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($nki->persentase_tunjangan == 100) bg-green-100 text-green-800
                        @elseif($nki->persentase_tunjangan == 80) bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $nki->persentase_tunjangan }}%
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('payroll.nki.show', $nki->id_nki) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if(auth()->user()->hasPermission('nki.edit'))
                        <a href="{{ route('payroll.nki.edit', $nki->id_nki) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasPermission('nki.delete'))
                        <form action="{{ route('payroll.nki.destroy', $nki->id_nki) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus data NKI ini?')"
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
        <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data NKI</h3>
    <p class="text-gray-500 mb-6">Mulai dengan membuat data NKI baru.</p>
    @if(auth()->user()->hasPermission('nki.create'))
    <a href="{{ route('payroll.nki.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Tambah NKI
    </a>
    @endif
</div>
@endif
