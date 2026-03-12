@extends('layouts.admin')

@section('title', 'Stock Out')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Stock Out</h1>
        <p class="text-gray-600">Mark items as damaged or write off inventory</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-sm border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Inventory
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200">
            <h2 class="text-lg font-bold text-gray-900">Remove Stock</h2>
            <p class="text-sm text-gray-600 mt-1">Mark items as damaged or permanently write them off</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.inventory.stock-out.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Item *</label>
                    <select name="itemID" id="stockOutItem" required onchange="updateItemInfo()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('itemID') border-red-500 @enderror">
                        <option value="">Choose an item...</option>
                        @foreach($items as $item)
                            <option value="{{ $item->itemID }}" 
                                    data-available="{{ $item->quantityAvailable - $item->quantityDamaged }}"
                                    data-total="{{ $item->quantityAvailable }}"
                                    data-damaged="{{ $item->quantityDamaged }}"
                                    {{ old('itemID') == $item->itemID ? 'selected' : '' }}>
                                {{ $item->itemName }} (Usable: {{ $item->quantityAvailable - $item->quantityDamaged }})
                            </option>
                        @endforeach
                    </select>
                    @error('itemID')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Item Info -->
                <div id="itemInfoCard" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                            <p id="infoTotal" class="text-lg font-bold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Damaged</p>
                            <p id="infoDamaged" class="text-lg font-bold text-red-600">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Usable</p>
                            <p id="infoUsable" class="text-lg font-bold text-green-600">-</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" name="quantity" min="1" required placeholder="e.g., 5"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                           value="{{ old('quantity') }}">
                    @error('quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 p-4 hover:border-yellow-400 transition-colors {{ old('reason') == 'damaged' ? 'border-yellow-500 bg-yellow-50' : '' }}">
                            <input type="radio" name="reason" value="damaged" class="sr-only peer" {{ old('reason', 'damaged') == 'damaged' ? 'checked' : '' }}>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">Damaged</span>
                                <span class="text-xs text-gray-500 mt-1">Item is broken but kept in inventory count</span>
                            </div>
                            <div class="absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-500"></div>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 p-4 hover:border-red-400 transition-colors {{ old('reason') == 'write_off' ? 'border-red-500 bg-red-50' : '' }}">
                            <input type="radio" name="reason" value="write_off" class="sr-only peer" {{ old('reason') == 'write_off' ? 'checked' : '' }}>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">Write Off</span>
                                <span class="text-xs text-gray-500 mt-1">Permanently remove from total inventory</span>
                            </div>
                            <div class="absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500"></div>
                        </label>
                    </div>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-semibold"
                            onclick="return confirm('Are you sure you want to stock out these items?')">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        Confirm Stock Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateItemInfo() {
    const select = document.getElementById('stockOutItem');
    const card = document.getElementById('itemInfoCard');
    const option = select.options[select.selectedIndex];

    if (!option.value) {
        card.classList.add('hidden');
        return;
    }

    document.getElementById('infoTotal').textContent = option.dataset.total;
    document.getElementById('infoDamaged').textContent = option.dataset.damaged;
    document.getElementById('infoUsable').textContent = option.dataset.available;
    card.classList.remove('hidden');
}

// Show info on page load if item was selected (validation error reload)
document.addEventListener('DOMContentLoaded', updateItemInfo);
</script>
@endsection
