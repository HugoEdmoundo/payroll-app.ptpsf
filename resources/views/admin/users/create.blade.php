@extends('layouts.app')

@section('title', 'Create User')
@section('breadcrumb', 'Users')
@section('breadcrumb_items')
<li class="inline-flex items-center">
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Manage Users</a>
</li>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
            <p class="mt-1 text-sm text-gray-600">Add a new user to the system</p>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Login *
                                <span class="text-xs text-gray-500">(untuk login)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle"></i> Hanya superadmin yang bisa edit email login
                            </p>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Verifikasi
                                <span class="text-xs text-gray-500">(untuk forgot password)</span>
                            </label>
                            <input type="email" name="email_valid" value="{{ old('email_valid') }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle"></i> User bisa edit email verifikasi sendiri
                            </p>
                            @error('email_valid')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div x-data="{ showPassword: false, showConfirm: false }">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" name="password" required
                                           class="w-full px-4 py-2 pr-10 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" 
                                            @click.prevent="showPassword = !showPassword"
                                            tabindex="-1"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition z-10">
                                        <i class="fas text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                                           class="w-full px-4 py-2 pr-10 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button" 
                                            @click.prevent="showConfirm = !showConfirm"
                                            tabindex="-1"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition z-10">
                                        <i class="fas text-sm" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Role & Additional Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Role & Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                            <select name="role_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                            <input type="text" name="position" value="{{ old('position') }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection