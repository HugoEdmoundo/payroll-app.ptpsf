@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
    <div>
        <div class="flex flex-col items-center">
            <!-- Logo -->
            <div class="h-20 w-20 rounded-lg flex items-center justify-center overflow-hidden mb-4">
                <img src="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}" 
                     alt="Logo PSF" 
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-20 w-20 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl\'>P</div>';">
            </div>
            <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                PAYROLL PSF
            </span>
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
                <div class="mt-1 relative" x-data="{ showPassword: false }">
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required
                           class="appearance-none relative block w-full px-3 py-3 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Enter your password">
                    <button type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
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

            <!-- FORGOT PASSWORD LINK WITH BADGE -->
            <div class="text-sm">
                <button type="button" 
                        onclick="showComingSoon()"
                        class="font-medium text-indigo-600 hover:text-indigo-500 inline-flex items-center">
                    Forgot password?
                </button>
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

<!-- COMING SOON NOTIFICATION - DARI ATAS KE BAWAH -->
<script>
function showComingSoon() {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'fixed top-0 left-0 right-0 z-50 flex justify-center pointer-events-none';
    toast.innerHTML = `
        <div class="mt-4 bg-blue-600 rounded-lg shadow-xl p-4 max-w-sm w-full mx-4 pointer-events-auto animate-slide-down">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-white text-lg animate-spin-slow"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-white animate-fade-in">
                        Coming soon! Hubungi admin untuk reset password.
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="closeToast(this)" class="text-white/80 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- Progress bar -->
            <div class="mt-2 h-1 bg-white/30 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full animate-progress"></div>
            </div>
        </div>
    `;
    
    // Remove existing toast if any
    const existingToast = document.querySelector('.fixed.top-0.left-0.right-0.z-50');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Add to document
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds with animation
    setTimeout(() => {
        const toastContent = toast.querySelector('.animate-slide-down');
        if (toastContent) {
            toastContent.classList.remove('animate-slide-down');
            toastContent.classList.add('animate-slide-up');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        } else {
            toast.remove();
        }
    }, 3000);
}

// Function to close toast manually
function closeToast(button) {
    const toast = button.closest('.fixed');
    const toastContent = toast.querySelector('.animate-slide-down, .animate-slide-up');
    if (toastContent) {
        toastContent.classList.remove('animate-slide-down');
        toastContent.classList.add('animate-slide-up');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    } else {
        toast.remove();
    }
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        0% {
            transform: translateY(-100px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        0% {
            transform: translateY(0);
            opacity: 1;
        }
        100% {
            transform: translateY(-100px);
            opacity: 0;
        }
    }
    
    @keyframes spinSlow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateX(-10px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes progress {
        0% {
            width: 100%;
        }
        100% {
            width: 0%;
        }
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }
    
    .animate-slide-down {
        animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }
    
    .animate-slide-up {
        animation: slideUp 0.3s ease-out forwards;
    }
    
    .animate-spin-slow {
        animation: spinSlow 2s linear infinite;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out 0.1s both;
    }
    
    .animate-progress {
        animation: progress 3s linear forwards;
    }
    
    .animate-bounce-slow {
        animation: bounce 2s ease-in-out infinite;
    }
    
    /* Hover effect untuk close button */
    .hover\\:scale-110:hover {
        transform: scale(1.1);
    }
    
    .transition-all {
        transition: all 0.2s ease;
    }
`;
document.head.appendChild(style);
</script>
@endsection