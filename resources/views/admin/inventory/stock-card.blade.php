@extends('layouts.admin')

@section('title', 'Stock Card - ' . $item->itemName)

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Stock Card</h1>
        <p class="text-gray-600">History of stock changes for <span class="font-semibold">{{ $item->itemName }}</span></p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.inventory.show', $item->itemID) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-gray-700 rounded-md hover:bg-gray-100 transition-colors text-sm font-semibold border border-gray-200">
            <svg class="w-4 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Item
        </a>
    </div>
</div>

<!-- Item Info Card -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Item Information</h2>
    </div>
    <div class="p-6 grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Item Name</p>
            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $item->itemName }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Category</p>
            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $item->category }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Quantity</p>
            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $item->quantityAvailable }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Damaged</p>
            <p class="text-sm font-semibold text-red-600 mt-1">{{ $item->quantityDamaged }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Usable</p>
            <p class="text-sm font-semibold text-green-600 mt-1">{{ $item->quantityAvailable - $item->quantityDamaged }}</p>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Transaction History</h2>
                <p class="text-gray-600 text-sm mt-1">All stock changes for this item</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                {{ $transactions->count() }} records
            </span>
        </div>
    </div>

    @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Type</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Qty</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Available (Before)</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Available (After)</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Damaged (Before)</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Damaged (After)</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $txn)
                        @php
                            $typeBadge = match($txn->type) {
                                'stock_in'  => 'bg-green-100 text-green-800',
                                'stock_out' => 'bg-orange-100 text-orange-800',
                                'damage'    => 'bg-red-100 text-red-800',
                                'restore'   => 'bg-blue-100 text-blue-800',
                                default     => 'bg-gray-100 text-gray-800',
                            };
                            $typeLabel = match($txn->type) {
                                'stock_in'  => 'Stock In',
                                'stock_out' => 'Stock Out',
                                'damage'    => 'Damaged',
                                'restore'   => 'Restored',
                                default     => ucfirst($txn->type),
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $txn->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeBadge }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-center font-semibold text-gray-900">
                                {{ $txn->quantity }}
                            </td>
                            <td class="py-3 px-4 text-sm text-center text-gray-600">{{ $txn->available_before }}</td>
                            <td class="py-3 px-4 text-sm text-center font-semibold text-gray-900">{{ $txn->available_after }}</td>
                            <td class="py-3 px-4 text-sm text-center text-gray-600">{{ $txn->damaged_before }}</td>
                            <td class="py-3 px-4 text-sm text-center font-semibold text-gray-900">{{ $txn->damaged_after }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $txn->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-6 text-center">
            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions yet</h3>
            <p class="mt-1 text-sm text-gray-500">Stock changes will appear here when items are added, damaged, or restored.</p>
        </div>
    @endif
</div>
@endsection
