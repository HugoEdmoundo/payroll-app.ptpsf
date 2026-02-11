@if(session('success') || session('error') || session('warning') || session('info'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform opacity-0 translate-y-2"
     x-transition:enter-end="transform opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform opacity-100"
     x-transition:leave-end="transform opacity-0"
     class="fixed top-20 right-4 z-50 max-w-sm w-full">
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-green-500 hover:text-green-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-red-500 hover:text-red-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-yellow-500 hover:text-yellow-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('info'))
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-blue-500 hover:text-blue-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
</div>
@endif