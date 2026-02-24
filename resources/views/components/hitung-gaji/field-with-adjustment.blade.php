@props(['field', 'label', 'value', 'adjustment' => null])

<div class="bg-white border border-gray-200 rounded-lg p-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left: Field Value (Read-Only) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
            <div class="flex items-center">
                <div class="flex-1 px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 font-medium">
                    Rp {{ number_format($value, 0, ',', '.') }}
                </div>
                <i class="fas fa-lock text-gray-400 ml-2" title="Read-only from Acuan Gaji"></i>
            </div>
        </div>
        
        <!-- Right: Adjustment Inputs (Optional) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Adjustment (Optional)
                <span class="text-xs text-gray-500">- If filled, description is required</span>
            </label>
            <div class="grid grid-cols-12 gap-2">
                <!-- Type -->
                <div class="col-span-3">
                    <select name="adjustments[{{ $field }}][type]" 
                            class="w-full px-2 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="+" {{ old("adjustments.{$field}.type", $adjustment['type'] ?? '+') == '+' ? 'selected' : '' }}>+</option>
                        <option value="-" {{ old("adjustments.{$field}.type", $adjustment['type'] ?? '+') == '-' ? 'selected' : '' }}>-</option>
                    </select>
                </div>
                
                <!-- Nominal -->
                <div class="col-span-9">
                    <input type="number" 
                           name="adjustments[{{ $field }}][nominal]" 
                           value="{{ old("adjustments.{$field}.nominal", $adjustment['nominal'] ?? '') }}"
                           step="0.01"
                           placeholder="Nominal adjustment"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                
                <!-- Description -->
                <div class="col-span-12">
                    <textarea name="adjustments[{{ $field }}][description]" 
                              rows="2"
                              placeholder="Description (required if adjustment filled)"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old("adjustments.{$field}.description", $adjustment['description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
