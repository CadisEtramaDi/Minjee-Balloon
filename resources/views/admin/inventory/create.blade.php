@extends('layouts.admin')

@section('title', 'Add Inventory Item')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Add Inventory Item</h1>
        <p class="text-gray-600">Create a new inventory record</p>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-sm border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back
    </a>
</div>

<div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8 max-w-3xl">
    <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                <input type="text" name="itemName" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('itemName') border-red-500 @enderror"
                       value="{{ old('itemName') }}">
                @error('itemName')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('category') border-red-500 @enderror">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Available *</label>
                <input type="number" name="quantityAvailable" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('quantityAvailable') border-red-500 @enderror"
                       value="{{ old('quantityAvailable') }}">
                @error('quantityAvailable')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost (₱) *</label>
                    <input type="number" step="0.01" name="purchase_cost" required
                        value="{{ old('purchase_cost', $item->purchase_cost ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent">
                </div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rental Price (₱) *</label>
                <input type="number" name="rentalPrice" required min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('rentalPrice') border-red-500 @enderror"
                       value="{{ old('rentalPrice') }}">
                @error('rentalPrice')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="">Select Status</option>
                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Damaged" {{ old('status') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('admin.inventory.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                Add Item
            </button>
        </div>
    </form>
</div>
@endsection
