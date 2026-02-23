@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
    <div>
        <div class="flex flex-col items-center">
            <!-- Logo -->
            <div class="h-20 w-20 rounded-lg flex items-center justify-center overflow-hidden mb-4">
                <img src="{{ asset('images/logo-psf.jpg') }}" 
                     alt="Logo PSF" 
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-20 w-20 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl\'>P</div>';">
            </div>
            <span class="text-2xl font-bold text-neutral-800 mb-2">PAYROLL PSF</span>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your credentials to access the system
            </p>
        </div>
    </div>
    
    <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
        @csrf
        
        @if($errors->any())
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ $errors->first() }}
                    </h3>
                </div>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </h3>
                </div>
            </div>
        </div>
        @endif
        
        @if(session('success'))
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </h3>
                </div>
            </div>
        </div>
        @endif
        
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your password">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember-me" name="remember" type="checkbox"
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-lock text-indigo-300 group-hover:text-indigo-400"></i>
                </span>
                Sign in
            </button>
        </div>
    </form>
    
    <div class="text-center">
        <p class="text-sm text-gray-500">
            © {{ date('Y') }} PAYROLL PSF. All rights reserved.
        </p>
    </div>
</div>
@endsection