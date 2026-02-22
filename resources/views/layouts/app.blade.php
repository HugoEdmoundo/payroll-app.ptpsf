<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Payroll System')</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="icon" href="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg" type="image/x-icon">
    
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