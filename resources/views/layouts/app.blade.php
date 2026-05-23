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
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.8/dist/cdn.min.js"></script>

    <!-- Lottie Web CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.13.0/lottie.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    backdropBlur: { '3xl': '48px' },
                    backgroundOpacity: { '5': '0.05', '15': '0.15' },
                    borderOpacity: { '15': '0.15' }
                }
            }
        }
    </script>

    <link rel="icon" href="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg">
    
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
        
        /* Hide scrollbar but keep scrollable */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
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

    @include('components.loading-overlay')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                loading: false,
                init() {
                    const container = document.getElementById('global-loader');
                    if (container && window.lottie) {
                        lottie.loadAnimation({
                            container: container,
                            renderer: 'svg',
                            loop: true,
                            autoplay: true,
                            path: 'https://assets8.lottiefiles.com/packages/lf20_hp09atmh.json'
                        });
                    }
                    document.addEventListener('click', e => {
                        const link = e.target.closest('a[href]:not([data-no-loader])');
                        if (link && link.hostname === window.location.hostname && link.target !== '_blank' && !link.hasAttribute('download')) {
                            this.loading = true;
                        }
                    });
                    document.addEventListener('submit', () => { this.loading = true; });
                    window.addEventListener('pageshow', () => { this.loading = false; });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>