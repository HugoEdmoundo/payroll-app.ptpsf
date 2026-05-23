<div x-show="$store.app.loading"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] bg-white/90 backdrop-blur-sm flex flex-col items-center justify-center"
     x-cloak>
    <div id="global-loader" class="w-32 h-32"></div>
    <p class="mt-6 text-sm font-medium text-gray-400 animate-pulse">Loading...</p>
</div>
