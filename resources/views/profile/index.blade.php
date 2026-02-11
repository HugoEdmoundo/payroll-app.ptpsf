@extends('layouts.app')

@section('title', 'My Profile')
@section('breadcrumb', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="space-y-6">
        <!-- Profile Header -->
        <div class="card p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                        <p class="text-gray-600">{{ auth()->user()->email }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ auth()->user()->role->name }}
                            </span>
                            @if(auth()->user()->is_active)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i> Active
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Profile Information</h2>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email (disabled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500">
                        <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <input type="text" name="position" value="{{ old('position', auth()->user()->position) }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Profile Photo -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                        <div class="flex items-center space-x-4">
                            <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-200">
                                @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/profile-photos/' . auth()->user()->profile_photo) }}" 
                                     alt="Profile" class="h-full w-full object-cover">
                                @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-user text-2xl"></i>
                                </div>
                                @endif
                            </div>
                            <input type="file" name="profile_photo" 
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Max 2MB. Supported formats: JPG, PNG</p>
                        @error('profile_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Password Change Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Leave blank if you don't want to change password</p>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection