<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Payroll System')</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" class="rounded-lg">
    
    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}" type="image/x-icon">
    
    <!-- Real-time Auto-Refresh System - Load BEFORE Alpine -->
    <script src="{{ asset('js/realtime.js') }}"></script>
    <script src="{{ asset('js/realtime-universal.js') }}"></script>
    
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
        
        /* Mobile friendly - prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
        
        /* Ensure content is not hidden */
        @media (max-width: 1023px) {
            main {
                min-height: calc(100vh - 3.5rem);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased" x-data="{ sidebarOpen: false, dropdownOpen: false }">

    <!-- Include Header -->
    @include('partials.header')
    
    <!-- Include Sidebar -->
    @include('partials.sidebar')
    
    <!-- Main Content -->
    <main class="lg:pl-64 pt-14 lg:pt-16">
        <div class="p-4 md:p-6 lg:p-8">
            <!-- Flash Messages -->
            @include('partials.flash')
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>