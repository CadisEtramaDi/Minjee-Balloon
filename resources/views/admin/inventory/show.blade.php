@extends('layouts.admin')

@section('title', 'Inventory Item - ' . $item->itemName)

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Inventory Item</h1>
        <p class="text-gray-600">View item details and rental history</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.inventory.edit', $item->itemID) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-sm font-semibold">
            Edit Item
        </a>

        <a href="{{ route('admin.inventory.stock-card', $item->itemID) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition-colors text-sm font-semibold shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Stock Card
        </a>

        <button onclick="openAddStockModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm font-semibold shadow-sm">
            + Add Stock
        </button>
        
        @if($item->quantityDamaged < $item->quantityAvailable)
        <button onclick="openMarkDamageModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-500 text-black rounded-md hover:bg-yellow-600 transition-colors text-sm font-semibold">
            Mark as Damaged
        </button>
        @endif

        @if($item->quantityDamaged > 0)
        <button onclick="openRestoreDamageModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors text-sm font-semibold">
            Restore Damaged Items
        </button>
        @endif
        
        
        @if($item->status !== 'Unavailable')
        <form method="POST" action="{{ route('admin.inventory.update-status', $item->itemID) }}" class="inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="Unavailable">
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 text-black rounded-md hover:bg-red-600 transition-colors text-sm font-semibold">
                Stock Out
            </button>
        </form>
        @endif
        
        @if($item->status !== 'Available')
        <form method="POST" action="{{ route('admin.inventory.update-status', $item->itemID) }}" class="inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="Available">
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 text-black rounded-md hover:bg-green-600 transition-colors text-sm font-semibold">
                Mark as Available
            </button>
        </form>
        @endif
        
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-gray-700 rounded-md hover:bg-gray-100 transition-colors text-sm font-semibold border border-gray-200">
            <svg class="w-4 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Inventory
        </a>
    </div>
</div>

@php
    $statusClass = match($item->status) {
        'Available' => 'bg-green-100 text-green-800',
        'Damaged' => 'bg-yellow-100 text-yellow-800',
        'Unavailable' => 'bg-gray-200 text-gray-700',
        default => 'bg-gray-100 text-gray-600'
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Item Details</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                    <p class="text-gray-900 font-medium">{{ $item->itemName }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item ID</label>
                    <p class="text-gray-900 font-medium">{{ $item->itemID }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <p class="text-gray-900 font-medium">{{ $item->category }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ $item->status }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Quantity</label>
                    <p class="text-gray-900 font-medium">{{ $item->quantityAvailable }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Damaged</label>
                    <p class="text-gray-900 font-medium">{{ $item->quantityDamaged }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available for Booking</label>
                    <p class="text-gray-900 font-medium text-lg {{ $item->quantityAvailable - $item->quantityDamaged > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->quantityAvailable - $item->quantityDamaged }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Purchase Cost</p>
                    <p class="text-lg font-semibold text-gray-900">
                        ₱{{ number_format($item->purchase_cost ?? 0, 2) }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rental Price</label>
                    <p class="text-gray-900 font-medium">₱{{ number_format($item->rentalPrice, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Booking History</h2>
                        <p class="text-gray-600 text-sm mt-1">Bookings that include this item</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $item->bookingItems->count() }} records
                    </span>
                </div>
            </div>

            @if($item->bookingItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px]">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Booking ID</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Customer</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Event Date</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Qty</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Subtotal</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($item->bookingItems as $bookingItem)
                                @php
                                    $booking = $bookingItem->booking;
                                    $eventDate = $booking->eventDATE ?? $booking->eventdate ?? null;
                                    $statusValue = strtolower($booking->status ?? '');
                                    $bookingStatusClass = match($statusValue) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'completed' => 'bg-blue-100 text-blue-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $booking->bookingID }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900">
                                        {{ $booking->customer->fname ?? 'N/A' }} {{ $booking->customer->lname ?? '' }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-900">
                                        {{ $eventDate ? \Carbon\Carbon::parse($eventDate)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-900">{{ $bookingItem->quantity }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-semibold">₱{{ number_format($bookingItem->subtotal, 2) }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bookingStatusClass }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4m8-6v6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                    <p class="mt-1 text-sm text-gray-500">This item has not been rented yet.</p>
                </div>
            @endif
        </div>
    </div>

    @php
    $profit = $item->totalRevenue - $item->purchase_cost;
    $isProfitable = $profit >= 0;
    @endphp

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $isProfitable ? 'border-green-500' : 'border-red-500' }}">
        <div class="flex items-center">
            <div class="p-3 rounded-full {{ $isProfitable ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-sm font-medium text-gray-600">Return on Investment (ROI)</h2>
                <p class="text-2xl font-bold {{ $isProfitable ? 'text-green-600' : 'text-red-600' }}">
                    ₱{{ number_format($profit, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Cost: ₱{{ number_format($item->purchase_cost, 2) }}
                </p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Item Summary</h3>
            <div class="space-y-3">
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Total Rentals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $item->bookingItems->count() }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Units Rented</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalRented }}</p>
                </div>
                <div class="p-3 bg-sky-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Quick Notes</h3>
            <ul class="text-sm text-gray-700 space-y-2">
                <li>Review status to keep availability accurate.</li>
                <li>Low quantities may affect upcoming bookings.</li>
                <li>Rental totals reflect booking item subtotals.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Stockin Modal -->
<div id="addStockModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Add New Stock</h3>
        <p class="text-gray-600 mb-4">How many new <span class="font-semibold">{{ $item->itemName }}</span> did you purchase or acquire?</p>
        
        <form action="{{ route('admin.inventory.add-stock', $item->itemID) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add</label>
                <input type="number" name="addQuantity" min="1" required placeholder="e.g., 10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAddStockModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold">
                    Confirm Stock In
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mark Damage Modal -->
<div id="markDamageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Mark Items as Damaged</h2>
        <form method="POST" action="{{ route('admin.inventory.mark-damage', $item->itemID) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity to Mark as Damaged
                </label>
                <input type="number" name="damageQuantity" required min="1" max="{{ $item->quantityAvailable - $item->quantityDamaged }}" 
                       placeholder="Enter quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-2">
                    Available to mark: {{ $item->quantityAvailable - $item->quantityDamaged }}
                </p>
                @error('damageQuantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeMarkDamageModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 transition-colors font-semibold">
                    Mark as Damaged
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Restore Damage Modal -->
<div id="restoreDamageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Restore Damaged Items</h2>
        <form method="POST" action="{{ route('admin.inventory.restore-damage', $item->itemID) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity to Restore
                </label>
                <input type="number" name="restoreQuantity" required min="1" max="{{ $item->quantityDamaged }}" 
                       placeholder="Enter quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-2">
                    Damaged items available: {{ $item->quantityDamaged }}
                </p>
                @error('restoreQuantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRestoreDamageModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold">
                    Restore Items
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddStockModal() {
    document.getElementById('addStockModal').classList.remove('hidden');
}

function closeAddStockModal() {
    document.getElementById('addStockModal').classList.add('hidden');
}

function openMarkDamageModal() {
    document.getElementById('markDamageModal').classList.remove('hidden');
}

function closeMarkDamageModal() {
    document.getElementById('markDamageModal').classList.add('hidden');
}

function openRestoreDamageModal() {
    document.getElementById('restoreDamageModal').classList.remove('hidden');
}

function closeRestoreDamageModal() {
    document.getElementById('restoreDamageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const markDamageModal = document.getElementById('markDamageModal');
    const restoreDamageModal = document.getElementById('restoreDamageModal');
    const addStockModal = document.getElementById('addStockModal');
    
    if (event.target === markDamageModal) {
        closeMarkDamageModal();
    }
    if (event.target === restoreDamageModal) {
        closeRestoreDamageModal();
    }
    if (event.target === addStockModal) {
        closeAddStockModal();
    }
});
</script>
@endsection
