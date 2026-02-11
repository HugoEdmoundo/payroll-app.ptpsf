<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Payroll System')</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Custom styles */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        .card {
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 h-full" x-data="{ sidebarOpen: false, dropdownOpen: false }">
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Include Header -->
    @include('partials.header')
    
    <!-- Include Sidebar -->
    @include('partials.sidebar')
    
    <!-- Include Mobile Nav -->
    @include('partials.mobile-nav')
    
    <!-- Main Content -->
    <main class="lg:pl-64 min-h-screen pt-16">
        <div class="p-4 md:p-6">
            <!-- Flash Messages -->
            @include('partials.flash')
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>