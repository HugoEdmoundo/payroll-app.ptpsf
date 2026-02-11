@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ array_key_first($groups) }}' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Configure system options and dropdown values</p>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50">
            <div class="flex overflow-x-auto">
                @foreach($groups as $key => $label)
                <button @click="activeTab = '{{ $key }}'" 
                        :class="activeTab === '{{ $key }}' ? 'border-indigo-500 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap focus:outline-none transition duration-150">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
        
        <div class="p-6">
            @foreach($groups as $key => $label)
                @php
                    $groupSettings = $settings[$key] ?? collect([]);
                @endphp
                
                <div x-show="activeTab === '{{ $key }}'" x-cloak>
                    <!-- FORM UTAMA -->
                    <form action="{{ route('admin.settings.update', $key) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $label }}</h3>
                                <p class="mt-1 text-sm text-gray-600">Manage {{ strtolower($label) }} dropdown options</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                {{ $groupSettings->count() }} options
                            </span>
                        </div>
                        
                        <!-- LIST EXISTING SETTINGS -->
                        <div class="space-y-3">
                            @forelse($groupSettings as $index => $setting)
                            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-indigo-200 transition duration-150">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <!-- Key (Readonly) -->
                                    <div class="md:col-span-4">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Key (internal)</label>
                                        <input type="text" 
                                               name="settings[{{ $index }}][key]" 
                                               value="{{ $setting->key }}"
                                               class="w-full px-3 py-2 text-sm bg-gray-100 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                               readonly>
                                    </div>
                                    
                                    <!-- Value (Editable) -->
                                    <div class="md:col-span-6">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Value (display)</label>
                                        <input type="text" 
                                               name="settings[{{ $index }}][value]" 
                                               value="{{ $setting->value }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                               required>
                                        <input type="hidden" name="settings[{{ $index }}][order]" value="{{ $index }}">
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="md:col-span-2 flex items-end justify-end">
                                        <button type="button" 
                                                onclick="deleteSetting('{{ $key }}', {{ $setting->id }})"
                                                class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition duration-150"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                                    <i class="fas fa-database text-gray-500 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No {{ $label }} Yet</h4>
                                <p class="text-gray-500 mb-4">Start by adding your first {{ strtolower($label) }} option below.</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- FORM TAMBAH BARU - INI INPUTNYA! -->
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-5 rounded-lg border border-indigo-100">
                                <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-plus-circle text-indigo-600 mr-2"></i>
                                    Add New {{ $label }} Option
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Input Key -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Key <span class="text-xs text-red-500">*</span>
                                            <span class="text-xs text-gray-500 ml-2">(no spaces, lowercase)</span>
                                        </label>
                                        <input type="text" 
                                               name="new_key" 
                                               placeholder="e.g., aktif, tetap, kontrak, bca"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               required
                                               pattern="[a-z_]+"
                                               title="Only lowercase letters and underscores allowed">
                                        <p class="text-xs text-gray-500 mt-1">Internal identifier (lowercase, underscore)</p>
                                    </div>
                                    
                                    <!-- Input Value -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Value <span class="text-xs text-red-500">*</span>
                                            <span class="text-xs text-gray-500 ml-2">(display text)</span>
                                        </label>
                                        <div class="flex space-x-2">
                                            <input type="text" 
                                                   name="new_value" 
                                                   placeholder="e.g., Aktif, Tetap, Kontrak, BCA"
                                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                   required>
                                            <button type="submit"
                                                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                                <i class="fas fa-plus mr-1"></i> Add
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Text that appears in dropdown</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SAVE ALL CHANGES BUTTON -->
                        @if($groupSettings->count() > 0)
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-md hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 shadow-sm">
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
</style>

<script>
    function deleteSetting(group, id) {
        if (confirm('Are you sure you want to delete this option? This action cannot be undone.')) {
            const form = document.getElementById(`delete-form-${group}`);
            form.action = `{{ url('admin/settings') }}/${group}/${id}`;
            form.submit();
        }
    }
    
    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        // Set active tab from hash or default to first
        if (window.location.hash) {
            const tab = window.location.hash.replace('#', '');
            if (document.querySelector(`[x-data]`)) {
                const alpineData = document.querySelector('[x-data]').__x;
                if (alpineData) {
                    alpineData.$data.activeTab = tab;
                }
            }
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"], .bg-green-50, .bg-red-50, .bg-yellow-50, .bg-blue-50');
            alerts.forEach(alert => {
                if (alert.classList.contains('fixed')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    });
</script>
@endsection