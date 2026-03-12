@extends('layouts.admin')

@section('title', 'Manage Inventory')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Inventory</h1>
    <p class="text-gray-600">Track items, availability, and rental pricing</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Search Inventory</h2>
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-col md:flex-row gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by item name or category..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all"
                >
                <button type="submit" class="px-6 py-3 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                    Search
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Item</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Category</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Total Qty</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Damaged</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Available</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Rental Price</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($items as $item)
                                @php
                                    $statusClass = match($item->status) {
                                        'Available' => 'bg-green-100 text-green-800',
                                        'Damaged' => 'bg-yellow-100 text-yellow-800',
                                        'Unavailable' => 'bg-gray-200 text-gray-700',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                    $availableQty = $item->quantityAvailable - $item->quantityDamaged;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-900">{{ $item->itemName }}</div>
                                        <div class="text-xs text-gray-500">#{{ $item->itemID }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $item->category }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $item->quantityAvailable }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->quantityDamaged > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->quantityDamaged }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-semibold {{ $availableQty > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $availableQty }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-semibold">₱{{ number_format($item->rentalPrice, 2) }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="relative inline-block text-left">
                                            <button type="button" onclick="toggleDropdown(event, 'dropdown-{{ $item->itemID }}')" class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:text-gray-900 hover:bg-gray-200 bg-gray-100 rounded-md transition-colors border border-gray-300">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            <div id="dropdown-{{ $item->itemID }}" class="hidden absolute right-0 mt-1 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1">
                                                    <a href="{{ route('admin.inventory.show', $item->itemID) }}" class="flex items-center px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100">
                                                        <svg class="w-3.5 h-3.5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a href="{{ route('admin.inventory.edit', $item->itemID) }}" class="flex items-center px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100">
                                                        <svg class="w-3.5 h-3.5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <div class="border-t border-gray-100 my-1"></div>
                                                    <form action="{{ route('admin.inventory.delete', $item->itemID) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="flex items-center w-full px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 text-left">
                                                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($items, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $items->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4m8-6v6"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No inventory items found</h3>
                    <p class="text-sm text-gray-500 mt-1">Add inventory items to track availability and pricing.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="space-y-4">
            <!-- Total Items Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Total Items</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $totalItems }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4m8-6v6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Items Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Available</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $availableItems }}</p>
                    </div>
                    <div class="bg-green-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Low Stock</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $lowStockItems }}</p>
                    </div>
                    <div class="bg-amber-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.732-3L13.732 4a2 2 0 00-3.464 0L3.268 16A2 2 0 005 19z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Unavailable Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Unavailable</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $unavailableItems }}</p>
                    </div>
                    <div class="bg-red-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Damaged Items Card -->
        <div class="bg-white rounded-2xl p-6 mt-6 shadow-md border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Damaged Items</p>
                    <p class="text-4xl font-bold text-gray-900 mt-3">{{ $damagedItems->sum('quantityDamaged') }}</p>
                </div>
                <div class="bg-orange-100 rounded-2xl p-5">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.732-3L13.732 4a2 2 0 00-3.464 0L3.268 16A2 2 0 005 19z"></path>
                    </svg>
                </div>
            </div>

            @if($damagedItems->count() > 0)
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    @foreach($damagedItems as $damaged)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <p class="font-medium text-gray-900">{{ $damaged->itemName }}</p>
                                <p class="text-xs text-gray-500">{{ $damaged->category }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                {{ $damaged->quantityDamaged }} damaged
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 border-t border-gray-200 pt-4">No damaged items currently.</p>
            @endif
        </div>

        <!-- Inventory Tips Section -->
        <div class="bg-white rounded-2xl p-8 mt-6 shadow-md border-l-4 border-blue-500">
            <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-xl">Inventory Tips</h3>
            </div>
            
            <ul class="space-y-4">
                <!-- Tip 1 -->
                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500 text-white font-bold group-hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">Search Inventory Quickly</p>
                        <p class="text-gray-600 text-sm mt-1">Use the search bar to find items by name or category. This makes locating specific inventory much faster.</p>
                    </div>
                </li>

                <!-- Tip 2 -->
                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-amber-500 text-white font-bold group-hover:bg-amber-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">Monitor Low Stock Items</p>
                        <p class="text-gray-600 text-sm mt-1">Regularly review items with low quantities to prevent shortages and keep customers satisfied.</p>
                    </div>
                </li>

                <!-- Tip 3 -->
                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-green-500 text-white font-bold group-hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">View Rental History</p>
                        <p class="text-gray-600 text-sm mt-1">Click "View Details" on any item to see its complete rental history and revenue information.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function toggleDropdown(event, dropdownId) {
    event.stopPropagation();
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        if (dropdown.id !== dropdownId) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    const dropdown = document.getElementById(dropdownId);
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('button')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});
</script>
@endsection
