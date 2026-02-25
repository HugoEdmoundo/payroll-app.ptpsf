<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login')</title>
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}" type="image/x-icon">
    
    <style>
        /* Prevent scroll on mobile and desktop */
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        /* Custom scrollbar for content if needed */
        .custom-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(99, 102, 241, 0.3) transparent;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(99, 102, 241, 0.3);
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 h-screen flex items-center justify-center p-4 overflow-hidden">
    <div class="w-full h-full flex items-center justify-center">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>