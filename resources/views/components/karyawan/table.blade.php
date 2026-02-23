@props(['karyawan' => []])

@if($karyawan->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masa Kerja</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($karyawan as $k)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded">
                        K{{ str_pad($k->id_karyawan, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $k->nama_karyawan }}</div>
                    <div class="text-sm text-gray-500">{{ $k->jenis_karyawan }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">
                        @if($k->email)
                        <div class="flex items-center mb-1">
                            <i class="fas fa-envelope text-gray-400 mr-2 w-4"></i>
                            <span>{{ $k->email }}</span>
                        </div>
                        @endif
                        @if($k->no_telp)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                            <span>{{ $k->no_telp }}</span>
                        </div>
                        @endif
                        @if(!$k->email && !$k->no_telp)
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $k->jabatan }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex flex-col">
                        <span class="font-medium">{{ $k->join_date->format('d/m/Y') }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                        <i class="far fa-hourglass-half mr-1"></i>
                        {{ $k->masa_kerja }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($k->status_pegawai == 'Aktif')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle mr-1 text-[6px] text-green-400"></i>
                        {{ $k->status_pegawai }}
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-circle mr-1 text-[6px] text-red-400"></i>
                        {{ $k->status_pegawai }}
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('karyawan.show', $k->id_karyawan) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if(auth()->user()->hasPermission('karyawan.edit'))
                        <a href="{{ route('karyawan.edit', $k->id_karyawan) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        
                        @if(auth()->user()->hasPermission('karyawan.delete'))
                        <form action="{{ route('karyawan.destroy', $k->id_karyawan) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this karyawan?')"
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
        <i class="fas fa-users text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No karyawan found</h3>
    <p class="text-gray-500 mb-6">Get started by creating a new karyawan.</p>
    @if(auth()->user()->hasPermission('karyawan.create'))
    <a href="{{ route('karyawan.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Add Karyawan
    </a>
    @endif
</div>
@endif