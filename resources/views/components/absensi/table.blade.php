@props(['absensiList' => []])

@if($absensiList->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hari/Bulan</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hadir</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Absence</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tanpa Ket</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($absensiList as $absensi)
            @php
                $totalAbsence = $absensi->absence + $absensi->tanpa_keterangan;
                $attendanceRate = $absensi->jumlah_hari_bulan > 0 ? ($absensi->hadir / $absensi->jumlah_hari_bulan) * 100 : 0;
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $absensi->karyawan->nama_karyawan ?? '-' }}</div>
                    <div class="text-sm text-gray-500">{{ $absensi->karyawan->jenis_karyawan ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $absensi->periode)->format('F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                    {{ $absensi->jumlah_hari_bulan }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-sm font-semibold text-green-600">{{ $absensi->hadir }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-sm font-semibold text-red-600">{{ $absensi->absence }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="text-sm font-semibold text-red-600">{{ $absensi->tanpa_keterangan }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($attendanceRate >= 95) bg-green-100 text-green-800
                        @elseif($attendanceRate >= 85) bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ number_format($attendanceRate, 1) }}%
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('payroll.absensi.show', $absensi->id_absensi) }}" 
                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('payroll.absensi.edit', $absensi->id_absensi) }}" 
                           class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('payroll.absensi.destroy', $absensi->id_absensi) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')"
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
        <i class="fas fa-calendar-check text-gray-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data absensi</h3>
    <p class="text-gray-500 mb-6">Mulai dengan membuat data absensi baru.</p>
    <a href="{{ route('payroll.absensi.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 transition duration-150">
        <i class="fas fa-plus mr-2"></i>Tambah Absensi
    </a>
</div>
@endif
