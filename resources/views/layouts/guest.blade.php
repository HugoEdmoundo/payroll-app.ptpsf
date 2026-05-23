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
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.8/dist/cdn.min.js"></script>

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
    
    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 h-screen flex items-center justify-center p-4 overflow-hidden">
    <div class="w-full h-full flex items-center justify-center">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>