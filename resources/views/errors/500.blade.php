<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg" type="image/x-icon">
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="flex flex-col items-center">
            <div class="h-16 w-16 rounded-xl overflow-hidden shadow-sm mb-4">
                <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg"
                     alt="Logo PSF"
                     class="h-full w-full object-cover"
                     onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-16 w-16 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl\'>P</div>';">
            </div>
            <div class="text-8xl font-black bg-gradient-to-r from-red-400 to-red-600 bg-clip-text text-transparent mb-2">
                500
            </div>
            <div class="w-16 h-1 bg-gradient-to-r from-red-400 to-red-600 rounded-full mb-6"></div>
            <div class="text-5xl text-gray-300 mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-2">Server Error</h1>
            <p class="text-gray-500 mb-8">Something went wrong on our end. Please try again later.</p>
            <a href="{{ url('/') }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-indigo-200">
                <i class="fas fa-home mr-2"></i>
                Go to Home
            </a>
        </div>
    </div>
</body>
</html>
