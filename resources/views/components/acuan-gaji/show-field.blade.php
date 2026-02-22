<div class="bg-gray-50 rounded-lg p-4">
    <p class="text-sm text-gray-600">{{ $label }}</p>
    <p class="text-lg font-semibold text-gray-900 mt-1">
        Rp {{ number_format($value ?? 0, 0, ',', '.') }}
    </p>
</div>
