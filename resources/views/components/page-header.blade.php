@props(['title', 'subtitle' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
    <div class="min-w-0 flex-1">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $title }}</h1>
        @if($subtitle)
        <p class="mt-1 text-xs sm:text-sm text-gray-600 line-clamp-2">{{ $subtitle }}</p>
        @endif
    </div>
    
    @if(isset($actions))
    <div class="flex flex-wrap items-center gap-2">
        {{ $actions }}
    </div>
    @endif
</div>
