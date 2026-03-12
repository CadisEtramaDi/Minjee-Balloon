@extends('layouts.admin')

@section('title', 'Stock In')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Stock In</h1>
        <p class="text-gray-600">Add stock to existing items or create new inventory items</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-sm border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Inventory
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Add to Existing Item -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
            <h2 class="text-lg font-bold text-gray-900">Add Stock to Existing Item</h2>
            <p class="text-sm text-gray-600 mt-1">Select an item and add more quantity</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.inventory.stock-in.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="existing">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Item *</label>
                    <select name="itemID" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('itemID') border-red-500 @enderror">
                        <option value="">Choose an item...</option>
                        @foreach($items as $item)
                            <option value="{{ $item->itemID }}" {{ old('itemID') == $item->itemID ? 'selected' : '' }}>
                                {{ $item->itemName }} (Current: {{ $item->quantityAvailable }})
                            </option>
                        @endforeach
                    </select>
                    @error('itemID')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add *</label>
                    <input type="number" name="addQuantity" min="1" required placeholder="e.g., 10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('addQuantity') border-red-500 @enderror"
                           value="{{ old('addQuantity') }}">
                    @error('addQuantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create New Item -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
            <h2 class="text-lg font-bold text-gray-900">Add New Item</h2>
            <p class="text-sm text-gray-600 mt-1">Create a brand new inventory item</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.inventory.stock-in.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="new">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" name="itemName" required placeholder="e.g., Round Table"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('itemName') border-red-500 @enderror"
                           value="{{ old('itemName') }}">
                    @error('itemName')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        <option value="Tables" {{ old('category') == 'Tables' ? 'selected' : '' }}>Tables</option>
                        <option value="Dining Wares" {{ old('category') == 'Dining Wares' ? 'selected' : '' }}>Dining Wares</option>
                        <option value="Catering Equipment" {{ old('category') == 'Catering Equipment' ? 'selected' : '' }}>Catering Equipment</option>
                        <option value="Entertainment" {{ old('category') == 'Entertainment' ? 'selected' : '' }}>Entertainment</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="quantityAvailable" min="1" required placeholder="e.g., 20"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantityAvailable') border-red-500 @enderror"
                               value="{{ old('quantityAvailable') }}">
                        @error('quantityAvailable')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost (₱) *</label>
                        <input type="number" step="0.01" name="purchase_cost" min="0" required placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('purchase_cost') border-red-500 @enderror"
                               value="{{ old('purchase_cost') }}">
                        @error('purchase_cost')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rental Price (₱) *</label>
                    <input type="number" step="0.01" name="rentalPrice" min="0" required placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rentalPrice') border-red-500 @enderror"
                           value="{{ old('rentalPrice') }}">
                    @error('rentalPrice')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
