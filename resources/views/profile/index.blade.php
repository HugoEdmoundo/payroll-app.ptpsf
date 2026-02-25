@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/profile-photos/' . auth()->user()->profile_photo) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="h-full w-full object-cover">
                            @else
                                <span class="text-white text-2xl font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                            <p class="text-gray-600">{{ auth()->user()->email }}</p>
                            <div class="mt-1 flex items-center space-x-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ auth()->user()->role ? auth()->user()->role->name : 'No Role' }}
                                </span>
                                @if(auth()->user()->is_active)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name) }}" 
                                               required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                                        @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email Login (Readonly) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Login
                                            <span class="text-xs text-gray-500">(untuk login)</span>
                                        </label>
                                        <input type="email" 
                                               value="{{ auth()->user()->email }}" 
                                               readonly
                                               class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-lock"></i> Hanya superadmin yang bisa edit email login
                                        </p>
                                    </div>

                                    <!-- Email Verifikasi (Editable) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Verifikasi
                                            <span class="text-xs text-gray-500">(untuk forgot password)</span>
                                        </label>
                                        <input type="email" 
                                               name="email_valid" 
                                               value="{{ old('email_valid', auth()->user()->email_valid) }}"
                                               placeholder="email@example.com"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email_valid') border-red-500 @enderror">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle"></i> Anda bisa edit email verifikasi sendiri
                                        </p>
                                        @error('email_valid')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input type="text" 
                                               name="phone" 
                                               value="{{ old('phone', auth()->user()->phone) }}"
                                               placeholder="08xxxxxxxxxx"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                                        @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Position -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                        <input type="text" 
                                               name="position" 
                                               value="{{ old('position', auth()->user()->position) }}"
                                               placeholder="e.g., Manager, Staff"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('position') border-red-500 @enderror">
                                        @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Photo -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Photo</h3>
                                <div class="flex items-center space-x-6">
                                    <div class="h-24 w-24 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        @if(auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/profile-photos/' . auth()->user()->profile_photo) }}" 
                                             alt="Profile" 
                                             class="h-full w-full object-cover"
                                             id="preview">
                                        @else
                                        <div class="h-full w-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-user text-3xl"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" 
                                               name="profile_photo" 
                                               id="photo"
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Supported: JPG, PNG, GIF. No size limit.
                                        </p>
                                        @error('profile_photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="border-t border-gray-200 pt-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                        <div class="relative">
                                            <input :type="showCurrent ? 'text' : 'password'" 
                                                   name="current_password"
                                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('current_password') border-red-500 @enderror">
                                            <button type="button" 
                                                    @click="showCurrent = !showCurrent"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                                <i class="fas" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <div class="relative">
                                            <input :type="showNew ? 'text' : 'password'" 
                                                   name="new_password"
                                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('new_password') border-red-500 @enderror">
                                            <button type="button" 
                                                    @click="showNew = !showNew"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                                <i class="fas" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                        @error('new_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                        <div class="relative">
                                            <input :type="showConfirm ? 'text' : 'password'" 
                                                   name="new_password_confirmation"
                                                   class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <button type="button" 
                                                    @click="showConfirm = !showConfirm"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                                <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex items-end">
                                        <p class="text-xs text-gray-500">Leave blank to keep current password</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('dashboard') }}" 
                               class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar - Permissions -->
        <div class="lg:col-span-1">
            @include('components.user.permission-badge', ['user' => auth()->user()])
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('photo')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            if (preview) {
                preview.src = e.target.result;
            } else {
                const container = document.querySelector('.h-24.w-24');
                if (container) {
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-full w-full object-cover" id="preview">`;
                }
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection