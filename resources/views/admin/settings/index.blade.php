@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ array_key_first($groups) }}' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-600">Manage dropdown options and system configurations</p>
        </div>
        <div>
            <div class="inline-flex items-center px-3 py-2 text-xs sm:text-sm bg-blue-50 border border-blue-200 rounded-lg">
                <i class="fas fa-info-circle text-blue-600 mr-1.5"></i>
                <span class="text-blue-700">Changes apply immediately</span>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex overflow-x-auto scrollbar-hide">
                @foreach($groups as $key => $label)
                <button @click="activeTab = '{{ $key }}'" 
                        :class="activeTab === '{{ $key }}' ? 'border-indigo-500 text-indigo-600 bg-white shadow-sm' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="relative px-6 py-4 text-sm font-semibold border-b-2 whitespace-nowrap focus:outline-none transition-all duration-200 ease-in-out">
                    <span class="flex items-center">
                        <i class="fas fa-{{ $key === 'jenis_karyawan' ? 'users' : ($key === 'status_pegawai' ? 'id-badge' : ($key === 'status_perkawinan' ? 'heart' : ($key === 'jabatan' ? 'briefcase' : ($key === 'lokasi_kerja' ? 'map-marker-alt' : ($key === 'bank' ? 'university' : 'cog'))))) }} mr-2"></i>
                        {{ $label }}
                    </span>
                    <span x-show="activeTab === '{{ $key }}'" 
                          class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-500 to-purple-500"></span>
                </button>
                @endforeach
            </div>
        </div>
        
        <!-- Tab Content -->
        <div class="p-8">
            @foreach($groups as $key => $label)
                @php
                    $groupSettings = $settings[$key] ?? collect([]);
                @endphp
                
                <div x-show="activeTab === '{{ $key }}'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-cloak>
                    
                    <form action="{{ route('admin.settings.update', $key) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Section Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $label }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Configure available options for {{ strtolower($label) }} dropdown</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 text-sm font-semibold rounded-full">
                                    <i class="fas fa-list mr-1.5"></i>
                                    {{ $groupSettings->count() }} {{ $groupSettings->count() === 1 ? 'option' : 'options' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Add New Option Card -->
                        <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 rounded-xl p-6 border-2 border-indigo-200 shadow-sm">
                            <div class="flex items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-600 text-white">
                                        <i class="fas fa-plus text-lg"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Add New Option</h4>
                                    <p class="text-sm text-gray-600">Create a new {{ strtolower($label) }} option</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Key Input -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Key <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="new_key" 
                                           placeholder="e.g., aktif, tetap, jakarta"
                                           class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                                           pattern="[a-z_]+"
                                           title="Only lowercase letters and underscores">
                                    <p class="text-xs text-gray-500 mt-1.5">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Internal identifier (lowercase, underscore only)
                                    </p>
                                </div>
                                
                                <!-- Value Input -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Display Value <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-2">
                                        <input type="text" 
                                               name="new_value" 
                                               placeholder="e.g., Aktif, Tetap, Jakarta"
                                               class="flex-1 px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                                        <button type="submit"
                                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 shadow-md hover:shadow-lg">
                                            <i class="fas fa-plus mr-2"></i>Add
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1.5">
                                        <i class="fas fa-eye mr-1"></i>
                                        Text shown in dropdown menus
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Options -->
                        @if($groupSettings->count() > 0)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center">
                                    <i class="fas fa-list text-gray-600 mr-2"></i>
                                    Current Options
                                </h4>
                                <span class="text-xs text-gray-500">
                                    {{ $groupSettings->count() }} {{ $groupSettings->count() === 1 ? 'option' : 'options' }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($groupSettings->sortBy('order') as $setting)
                                <div class="group bg-white rounded-lg border-2 border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-150">
                                    <div class="p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center mb-1">
                                                    <i class="fas fa-tag text-indigo-500 text-xs mr-2"></i>
                                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Key</span>
                                                </div>
                                                <p class="text-sm font-mono text-gray-700 break-all">{{ $setting->key }}</p>
                                            </div>
                                            <button type="button"
                                                    onclick="deleteSetting('{{ $key }}', {{ $setting->id }})"
                                                    class="ml-2 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition opacity-0 group-hover:opacity-100"
                                                    title="Delete option">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="pt-3 border-t border-gray-100">
                                            <div class="flex items-center mb-1">
                                                <i class="fas fa-eye text-purple-500 text-xs mr-2"></i>
                                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Display Value</span>
                                            </div>
                                            <input type="text" 
                                                   name="settings[{{ $loop->index }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                            <input type="hidden" name="settings[{{ $loop->index }}][key]" value="{{ $setting->key }}">
                                            <input type="hidden" name="settings[{{ $loop->index }}][order]" value="{{ $setting->order }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="submit"
                                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 shadow-md hover:shadow-lg">
                                    <i class="fas fa-save mr-2"></i>Save All Changes
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 mb-4">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-medium text-gray-900 mb-1">No Options Yet</h5>
                            <p class="text-sm text-gray-600">Add your first option using the form above.</p>
                        </div>
                        @endif
                    </form>
                    
                    <!-- Delete Form (Hidden) -->
                    <form id="delete-form-{{ $key }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @endforeach
            
            <!-- Special Tab: Jabatan by Jenis Karyawan -->
            <div x-show="activeTab === 'jabatan_by_jenis'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-cloak>
                
                <div class="space-y-6">
                    
                    <!-- Add New Mapping Form -->
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-link text-indigo-600 mr-2"></i>
                                Jabatan by Jenis Karyawan
                            </h4>
                            <p class="mt-1 text-sm text-gray-600">Map jabatan options to specific jenis karyawan. This will filter jabatan dropdown when adding/editing karyawan.</p>
                        </div>
                        
                        <form action="{{ route('admin.settings.jabatan-jenis.store') }}" method="POST" class="p-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Karyawan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_karyawan" required 
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                        <option value="">Pilih Jenis Karyawan</option>
                                        @foreach($settings['jenis_karyawan'] ?? [] as $jenis)
                                        <option value="{{ $jenis->value }}">{{ $jenis->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jabatan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jabatan" required 
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition">
                                        <option value="">Pilih Jabatan</option>
                                        @foreach($settings['jabatan_options'] ?? [] as $jabatan)
                                        <option value="{{ $jabatan->value }}">{{ $jabatan->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 shadow-sm">
                                    <i class="fas fa-plus mr-2"></i>Add Mapping
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Current Mappings -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center">
                                <i class="fas fa-list text-gray-600 mr-2"></i>
                                Current Mappings
                            </h4>
                            <span class="text-xs text-gray-500">
                                {{ $jabatanByJenis->count() }} jenis karyawan configured
                            </span>
                        </div>
                        
                        @if($jabatanByJenis->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($jabatanByJenis as $jenis => $jabatanList)
                            <div class="bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transition duration-150">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-base font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-users text-indigo-600 mr-2 text-sm"></i>
                                            {{ $jenis }}
                                        </h5>
                                        <span class="px-2.5 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                            {{ count($jabatanList) }} jabatan
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($jabatanList as $jabatan)
                                        @php
                                            $mapping = \App\Models\JabatanJenisKaryawan::where('jenis_karyawan', $jenis)->where('jabatan', $jabatan)->first();
                                        @endphp
                                        <div class="inline-flex items-center px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm hover:bg-gray-100 transition group">
                                            <i class="fas fa-briefcase text-gray-400 mr-2 text-xs"></i>
                                            <span class="text-gray-700 font-medium">{{ $jabatan }}</span>
                                            <form action="{{ route('admin.settings.jabatan-jenis.destroy', $mapping->id) }}" method="POST" class="inline ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Delete mapping: {{ $jenis }} - {{ $jabatan }}?')" 
                                                        class="text-gray-400 hover:text-red-600 transition opacity-0 group-hover:opacity-100">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 mb-4">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-medium text-gray-900 mb-1">No Mappings Yet</h5>
                            <p class="text-sm text-gray-600">Add your first mapping using the form above.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { 
        display: none !important; 
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function deleteSetting(group, id) {
        if (confirm('⚠️ Are you sure you want to delete this option?\n\nThis action cannot be undone and may affect existing data.')) {
            const form = document.getElementById(`delete-form-${group}`);
            form.action = `{{ url('admin/settings') }}/${group}/${id}`;
            form.submit();
        }
    }
</script>
@endsection