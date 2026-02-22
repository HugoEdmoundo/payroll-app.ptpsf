@props(['kasbonList' => []])

@if($kasbonList->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nominal</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Metode</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($kasbonList as $kasbon)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $kasbon->karyawan->nama_karyawan ?? '-' }}</div>
                    <div class="text-sm text-gray-500">{{ $kasbon->karyawan->jenis_karyawan ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $kasbon->periode)->format('M Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $kasbon->tanggal_pengajuan->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $kasbon->deskripsi }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                    Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $kasbon->metode_pembayaran == 'Langsung' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ $kasbon->metode_pembayaran }}
                        @if($kasbon->metode_pembayaran == 'Cicilan')
                            ({{ $kasbon->cicilan_terbayar }}/{{ $kasbon->jumlah_cicilan }})
                        @endif
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $kasbon->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $kasbon->status_pembayaran }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('payroll.kasbon.show', $kasbon->id_kasbon) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('payroll.kasbon.edit', $kasbon->id_kasbon) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('payroll.kasbon.destroy', $kasbon->id_kasbon) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus data kasbon ini?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
        <i class="fas fa-hand-holding-usd text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kasbon</h3>
    <p class="text-gray-500 mb-6">Mulai dengan membuat data kasbon baru.</p>
    <a href="{{ route('payroll.kasbon.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Tambah Kasbon
    </a>
</div>
@endif
