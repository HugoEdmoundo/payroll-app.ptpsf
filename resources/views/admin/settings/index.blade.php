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
                        
                        <!-- Existing Options List -->
                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Current Options</h4>
                            
                            @forelse($groupSettings as $index => $setting)
                            <div class="group relative bg-white rounded-lg border-2 border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center p-4">
                                    <!-- Drag Handle -->
                                    <div class="flex-shrink-0 mr-4 text-gray-400 group-hover:text-gray-600">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <!-- Key -->
                                        <div class="md:col-span-4">
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Key</label>
                                            <div class="relative">
                                                <input type="text" 
                                                       name="settings[{{ $index }}][key]" 
                                                       value="{{ $setting->key }}"
                                                       class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-lg font-mono"
                                                       readonly>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <i class="fas fa-lock text-gray-400 text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Value -->
                                        <div class="md:col-span-6">
                                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Display Value</label>
                                            <input type="text" 
                                                   name="settings[{{ $index }}][value]" 
                                                   value="{{ $setting->value }}"
                                                   class="w-full px-3 py-2.5 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                                                   required>
                                            <input type="hidden" name="settings[{{ $index }}][order]" value="{{ $index }}">
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="md:col-span-2 flex items-end justify-end">
                                            <button type="button" 
                                                    onclick="deleteSetting('{{ $key }}', {{ $setting->id }})"
                                                    class="px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition duration-150 font-medium"
                                                    title="Delete option">
                                                <i class="fas fa-trash-alt mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-sm mb-4">
                                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">No Options Yet</h4>
                                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by adding your first {{ strtolower($label) }} option using the form above.</p>
                                <div class="inline-flex items-center text-sm text-indigo-600 font-medium">
                                    <i class="fas fa-arrow-up mr-2"></i>
                                    Add your first option above
                                </div>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Save Button -->
                        @if($groupSettings->count() > 0)
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>Save All Changes
                            </button>
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