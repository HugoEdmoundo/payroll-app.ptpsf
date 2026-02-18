@extends('layouts.app')

@section('title', 'CMS Dashboard')
@section('breadcrumb', 'CMS')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">CMS Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Manage system modules, fields, and configurations</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <i class="fas fa-cube text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Modules</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_modules'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-600">{{ $stats['active_modules'] }} active</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <i class="fas fa-list text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Dynamic Fields</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_fields'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-600">{{ $stats['active_fields'] }} active</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                        <i class="fas fa-cog text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">System Settings</dt>
                        <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_settings'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.settings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Manage settings →
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                        <i class="fas fa-shield-alt text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Quick Actions</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">CMS Tools</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-600">Full control</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Modules -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-cube text-indigo-600 mr-2"></i>Modules
                </h3>
                <a href="{{ route('admin.modules.create') }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    <i class="fas fa-plus mr-1"></i>Add Module
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($modules->take(5) as $module)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas {{ $module->icon }} text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $module->display_name }}</h4>
                                <p class="text-xs text-gray-500">{{ $module->dynamic_fields_count }} fields</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($module->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                            @endif
                            <a href="{{ route('admin.modules.show', $module) }}" 
                               class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No modules found</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.modules.index') }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        View all modules →
                    </a>
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-cog text-yellow-600 mr-2"></i>System Settings
                </h3>
                <a href="{{ route('admin.settings.index') }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Manage Settings
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($systemSettings->take(5) as $group => $settings)
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $group) }}</h4>
                            <p class="text-xs text-gray-500">{{ $settings->count() }} options</p>
                        </div>
                        <a href="{{ route('admin.settings.index') }}#{{ $group }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No settings found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- CMS Features -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-magic text-indigo-600 mr-2"></i>CMS Features
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-plus-circle text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Dynamic Fields</h4>
                        <p class="text-xs text-gray-600 mt-1">Add custom fields to any module without coding</p>
                        <a href="{{ route('admin.fields.create') }}" 
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                            Create Field →
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cube text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Module Management</h4>
                        <p class="text-xs text-gray-600 mt-1">Create and configure system modules</p>
                        <a href="{{ route('admin.modules.index') }}" 
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                            Manage Modules →
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-sliders-h text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">System Configuration</h4>
                        <p class="text-xs text-gray-600 mt-1">Configure system-wide settings and options</p>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                            Settings →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
