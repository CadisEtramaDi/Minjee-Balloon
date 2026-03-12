@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Bookings
    </a>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
        <div class="flex items-center mb-2">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-800 font-medium">Please fix the following errors:</p>
        </div>
        <ul class="list-disc list-inside text-red-700 text-sm ml-9">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p> 
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Booking Details Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-[#0EA5E9] to-sky-500 px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Booking #{{ $booking->bookingID }}</h2>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $booking->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->status === 'Awaiting Downpayment' ? 'bg-orange-100 text-orange-800' : '' }}
                    {{ $booking->status === 'Confirmed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $booking->status === 'Completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ $booking->status }}
                </span>
            </div>
            
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <!-- Customer Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Name</p>
                                <p class="font-medium text-gray-900">{{ $booking->customer->fname }} {{ $booking->customer->lname }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Phone</p>
                                <p class="font-medium text-gray-900">{{ $booking->customer->phonenumber }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Address</p>
                                <p class="font-medium text-gray-900">{{ $booking->customer->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Event Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Event Date & Time</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->eventDATE)->format('F d, Y') }} 
                                    <br>
                                    <span class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->timeStart)->format('h:i A') }} 
                                        @if($booking->timeEnd)
                                            - {{ \Carbon\Carbon::parse($booking->timeEnd)->format('h:i A') }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Location</p>
                                <p class="font-medium text-gray-900">{{ $booking->eventLocation }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payment Summary
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Total Amount:</span>
                            <span class="font-bold text-gray-900 text-lg">
                                @if($booking->totalAmount > 0)
                                    ₱{{ number_format($booking->totalAmount, 2) }}
                                @else
                                    <span class="text-yellow-600 text-sm font-medium italic bg-yellow-50 px-2 py-1 rounded">Amount not set yet</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Amount Paid:</span>
                            <span class="font-semibold text-green-600">₱{{ number_format($totalPaid, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                            <span class="text-gray-700 font-medium">Remaining Balance:</span>
                            <span class="font-bold text-lg {{ $remainingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                ₱{{ number_format($remainingBalance, 2) }}
                            </span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            @php
                                $progress = $booking->totalAmount > 0 ? min(100, round(($totalPaid / $booking->totalAmount) * 100)) : 0;
                            @endphp
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">Payment Progress</span>
                                <span class="font-bold {{ $progress >= 100 ? 'text-green-600' : 'text-[#0EA5E9]' }}">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all duration-500 {{ $progress >= 100 ? 'bg-green-500' : 'bg-[#0EA5E9]' }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-gray-400 text-sm mt-6">Created on {{ $booking->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>

        <!-- Payment History -->
        @if($booking->payments->count() > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Payment History
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Payment ID</th>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Amount</th>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Date</th>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Method</th>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-6 text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($booking->payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-6 text-gray-900 font-medium">#{{ $payment->paymentID }}</td>
                            <td class="py-3 px-6 text-gray-900 font-semibold">₱{{ number_format($payment->amountpaid, 2) }}</td>
                            <td class="py-3 px-6 text-gray-700">{{ $payment->paymentdate->format('M d, Y') }}</td>
                            <td class="py-3 px-6">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $payment->paymentmethod)) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                @if($payment->status === 'completed')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Completed</span>
                                @elseif($payment->status === 'pending')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Failed</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">
                                <a href="{{ route('admin.payments.show', $payment->paymentID) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Approve Booking Form -->
        @if($booking->status === 'Pending')
        <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-yellow-200">
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approve Booking
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.bookings.update-status', $booking->bookingID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="totalAmount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount (₱) *</label>
                        <input type="number" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all @error('totalAmount') border-red-500 @enderror" 
                               id="totalAmount" 
                               name="totalAmount" 
                               step="0.01" 
                               min="0"
                               value="{{ old('totalAmount', $booking->totalAmount) }}" 
                               required>
                        @error('totalAmount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="Awaiting Downpayment">

                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve Booking
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-4 space-y-3">
                @if(in_array($booking->status, ['Awaiting Downpayment', 'Confirmed', 'Completed']))
                    <a href="{{ route('admin.payments.create', $booking->bookingID) }}" 
                       style="display: block; width: 100%; padding: 12px 16px; background-color: #0EA5E9; color: white; border-radius: 8px; font-weight: 600; text-align: center; text-decoration: none;">
                        💰 Add Payment
                    </a>
                @endif

                <a href="tel:{{ $booking->customer->phonenumber ?? '' }}" 
                   style="display: block; width: 100%; padding: 12px 16px; background-color: #16a34a; color: white; border-radius: 8px; font-weight: 600; text-align: center; text-decoration: none;">
                    📞 Call Customer
                </a>

                @if($booking->status === 'Confirmed')
                <form action="{{ route('admin.bookings.return', $booking->bookingID) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg">
                        Mark Items Returned
                    </button>
                </form>
                @endif

                @if($booking->status === 'Pending')
                    <div style="background-color: #dbeafe; border: 1px solid #93c5fd; border-radius: 8px; padding: 12px; text-align: center;">
                        <p style="color: #1e40af; font-size: 14px; font-weight: 500; margin: 0;">
                            📋 Approve booking to unlock payment actions
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Update -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.bookings.update-status', $booking->bookingID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                        <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all" required>
                            <option value="Pending" {{ $booking->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Completed" {{ $booking->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Partial" {{ $booking->status === 'Partial' ? 'selected' : '' }}>Partial</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium shadow-sm">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Event Countdown -->
        <div class="bg-gradient-to-br from-[#0EA5E9] to-sky-500 rounded-xl shadow-md overflow-hidden text-white">
            <div class="px-6 py-4 bg-white/10">
                <h3 class="text-lg font-semibold">Event Countdown</h3>
            </div>
            <div class="p-6 text-center">
                
                @php
                    $eventDate = $booking->eventDATE;
                    if ($eventDate) {
                        // Force it to be a Carbon date object first!
                        $parsedDate = \Carbon\Carbon::parse($eventDate);
                        $daysUntil = (int) now()->startOfDay()->diffInDays($parsedDate->startOfDay(), false);
                    } else {
                        $daysUntil = 0;
                    }
                @endphp
                @if($eventDate && $daysUntil > 0)
                    <div class="text-7xl font-bold mb-2">{{ $daysUntil }}</div>
                    <p class="text-white/90 text-lg">days until event</p>
                @elseif($daysUntil === 0)
                    <div class="text-4xl font-bold mb-2">🎉 Today!</div>
                    <p class="text-white/90">Event is today</p>
                @else
                    <div class="text-4xl font-bold mb-2">Past Event</div>
                    <p class="text-white/90">{{ abs($daysUntil) }} days ago</p>
                @endif
            </div>
        </div>

        <!-- Delete Booking -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-red-200">
            <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.bookings.delete', $booking->bookingID) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-sm">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
