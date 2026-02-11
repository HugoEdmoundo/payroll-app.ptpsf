@if(session('success') || session('error') || session('warning') || session('info') || session('status'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform opacity-0 translate-y-2 translate-x-2"
     x-transition:enter-end="transform opacity-100 translate-y-0 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform opacity-100"
     x-transition:leave-end="transform opacity-0 translate-y-2 translate-x-2"
     class="fixed top-20 right-4 z-50 max-w-sm w-full">
    
    @if(session('success') || session('status'))
    <div class="bg-green-50 border border-green-200 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') ?? session('status') }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-green-500 hover:text-green-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-red-500 hover:text-red-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('warning'))
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-yellow-800">
                    {{ session('warning') }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-yellow-500 hover:text-yellow-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-blue-800">
                    {{ session('info') }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-blue-500 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Error Bag (Validation Errors) -->
@if($errors->any())
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform opacity-0 translate-y-2 translate-x-2"
     x-transition:enter-end="transform opacity-100 translate-y-0 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform opacity-100"
     x-transition:leave-end="transform opacity-0 translate-y-2 translate-x-2"
     class="fixed top-20 right-4 z-50 max-w-sm w-full">
    <div class="bg-red-50 border border-red-200 rounded-lg shadow-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-red-800 mb-1">
                    Please fix the following errors:
                </p>
                <ul class="text-xs text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="inline-flex text-red-500 hover:text-red-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
/* Animasi untuk toast */
[x-cloak] { display: none !important; }
</style>