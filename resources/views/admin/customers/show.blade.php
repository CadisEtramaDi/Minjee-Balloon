@extends('layouts.admin')

@section('title', 'Customer Details - ' . $customer->fname . ' ' . $customer->lname)

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Customer Details</h1>
        <p class="text-gray-600">View and manage customer information</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-gray-700 rounded-md hover:bg-gray-100 transition-colors text-sm font-semibold border border-gray-200">
        <svg class="w-4 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Customers
    </a>
</div>

@php
    $totalValue = $customer->bookings->sum(function($booking) {
        return $booking->payments->sum('amountpaid') ?: 0;
    });
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <p class="text-gray-900 font-medium">{{ $customer->fname }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <p class="text-gray-900 font-medium">{{ $customer->lname }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <p class="text-gray-900 font-medium">{{ $customer->phonenumber }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer ID</label>
                    <p class="text-gray-900 font-medium">{{ $customer->customerID }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <p class="text-gray-900 font-medium">{{ $customer->address }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Bookings
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">All bookings made by this customer</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $customer->bookings->count() }} total
                    </span>
                </div>
            </div>

            @if($customer->bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Booking ID</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Event Date</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Time</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-xs uppercase tracking-wide">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($customer->bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $booking->bookingID }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900">
                                        {{ $booking->eventdate ? \Carbon\Carbon::parse($booking->eventdate)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-900">{{ $booking->eventtime ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-sm">
                                        @php
                                            $statusClass = match($booking->status) {
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'paid' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <a href="{{ route('admin.bookings.show', $booking->bookingID) }}" class="inline-flex items-center px-3 py-1.5 bg-[#0EA5E9] text-white rounded-md text-xs font-semibold hover:bg-sky-600 transition-colors">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings</h3>
                    <p class="mt-1 text-sm text-gray-500">This customer hasn't made any bookings yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->bookings->count() }}</p>
                </div>

                <div class="p-3 bg-green-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Confirmed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->bookings->where('status', 'confirmed')->count() }}</p>
                </div>

                <div class="p-3 bg-yellow-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->bookings->where('status', 'pending')->count() }}</p>
                </div>

                <div class="p-3 bg-purple-50 rounded-lg">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Paid</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->bookings->where('status', 'paid')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Total Value</h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-700">Total Payments Received</p>
                <p class="text-3xl font-bold text-green-900">₱{{ number_format($totalValue, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
