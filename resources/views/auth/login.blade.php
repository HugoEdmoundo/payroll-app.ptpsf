@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-md">
    <!-- Card Container -->
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-center">
            <div class="flex justify-center mb-3">
                <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center overflow-hidden ring-4 ring-white/30">
                    <img src="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}" 
                         alt="Logo PSF" 
                         class="h-full w-full object-cover"
                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-white font-bold text-2xl\'>P</div>';">
                </div>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">PAYROLL PSF</h1>
            <p class="text-white/80 text-sm">Sign in to continue</p>
        </div>

        <!-- Form Section -->
        <div class="px-8 py-6">
            
            @if($errors->any())
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-3">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <span class="text-sm text-red-800">{{ $errors->first() }}</span>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-3">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <span class="text-sm text-red-800">{{ session('error') }}</span>
                </div>
            </div>
            @endif
            
            @if(session('success'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 p-3">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-sm text-green-800">{{ session('success') }}</span>
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('email') }}"
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                               placeholder="your@email.com">
                    </div>
                </div>
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="#" 
                       onclick="alert('Please contact your administrator to reset your password.')" 
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} PAYROLL PSF. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection