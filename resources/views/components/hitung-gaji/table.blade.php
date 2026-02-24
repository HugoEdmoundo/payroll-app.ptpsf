@props(['hitungGajiList'])

@if($hitungGajiList->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Take Home Pay</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($hitungGajiList as $hitungGaji)
            <tr class="hover:bg-gray-50 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($hitungGaji->karyawan->nama_karyawan, 0, 2)) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $hitungGaji->karyawan->nama_karyawan }}</div>
                            <div class="text-sm text-gray-500">{{ $hitungGaji->karyawan->jabatan }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $hitungGaji->periode)->format('F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 font-medium">
                    Rp {{ number_format($hitungGaji->total_pendapatan_akhir, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 font-medium">
                    Rp {{ number_format($hitungGaji->total_pengeluaran_akhir, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-indigo-600 font-bold">
                    Rp {{ number_format($hitungGaji->take_home_pay, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if($hitungGaji->status === 'draft')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-edit mr-1"></i> Draft
                        </span>
                    @elseif($hitungGaji->status === 'preview')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Approved
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('payroll.hitung-gaji.show', $hitungGaji) }}" 
                           class="text-indigo-600 hover:text-indigo-900" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.edit'))
                        <a href="{{ route('payroll.hitung-gaji.edit', $hitungGaji) }}" 
                           class="text-blue-600 hover:text-blue-900" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if($hitungGaji->status === 'draft' && auth()->user()->hasPermission('hitung_gaji.delete'))
                        <form action="{{ route('payroll.hitung-gaji.destroy', $hitungGaji) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this?')"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
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
    <i class="fas fa-calculator text-gray-400 text-5xl mb-4"></i>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No Hitung Gaji Data</h3>
    <p class="text-gray-500 mb-4">Start by creating hitung gaji from acuan gaji data.</p>
    @if(auth()->user()->hasPermission('hitung_gaji.create'))
    <a href="{{ route('payroll.hitung-gaji.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Create Hitung Gaji
    </a>
    @endif
</div>
@endif
