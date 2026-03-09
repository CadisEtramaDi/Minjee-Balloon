@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.show', $booking->bookingID) }}" class="text-[#0EA5E9] hover:underline font-medium">
        ← Back to Booking
    </a>
</div>

<h1 class="text-3xl font-bold text-gray-900 mb-6">Record Payment for Booking #{{ $booking->bookingID }}</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Details</h2>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Customer:</span>
                <span class="font-semibold text-gray-900">{{ $booking->customer->fname }} {{ $booking->customer->lname }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Event Date:</span>
                <span class="font-semibold text-gray-900">{{ $booking->eventDATE->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Event Time:</span>
                <span class="font-semibold text-gray-900">{{ date('h:i A', strtotime($booking->timeStart)) }} - {{ date('h:i A', strtotime($booking->timeEND)) }}</span>
            </div>
            <div class="border-t border-gray-200 pt-3 mt-3"></div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-bold text-gray-900 text-lg">₱{{ number_format($booking->totalAmount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Amount Paid:</span>
                <span class="font-semibold text-green-600">₱{{ number_format($totalPaid, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Remaining Balance:</span>
                <span class="font-bold {{ $remainingBalance > 0 ? 'text-red-600' : 'text-green-600' }} text-lg">
                    ₱{{ number_format($remainingBalance, 2) }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Form</h2>
        <form action="{{ route('admin.payments.store', $booking->bookingID) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="amountpaid" class="block text-sm font-medium text-gray-700 mb-2">Amount to Pay *</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">₱</span>
                    <input type="number" 
                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('amountpaid') border-red-500 @enderror" 
                           id="amountpaid" 
                           name="amountpaid" 
                           step="0.01" 
                           min="0" 
                           max="{{ $remainingBalance }}"
                           value="{{ old('amountpaid', $totalPaid == 0 ? $booking->totalAmount * 0.50 : $remainingBalance) }}"
                           required>
                </div>
                @error('amountpaid')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Maximum: ₱{{ number_format($remainingBalance, 2) }}</p>
            </div>

            <div>
                <label for="paymentdate" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                <input type="date" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('paymentdate') border-red-500 @enderror" 
                       id="paymentdate" 
                       name="paymentdate" 
                       value="{{ old('paymentdate', date('Y-m-d')) }}" 
                       required>
                @error('paymentdate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="paymentmethod" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('paymentmethod') border-red-500 @enderror" 
                        id="paymentmethod" 
                        name="paymentmethod" 
                        required>
                    <option value="">Select Payment Method</option>
                    <option value="cash" {{ old('paymentmethod') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="gcash" {{ old('paymentmethod') == 'gcash' ? 'selected' : '' }}>GCash</option>
                </select>
                @error('paymentmethod')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('status') border-red-500 @enderror" 
                        id="status" 
                        name="status" 
                        required>
                    <option value="completed" {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3 pt-4">
                <button type="submit" class="w-full px-6 py-3 bg-[#0EA5E9] text-white rounded-lg font-semibold hover:bg-sky-600 transition-colors">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Record Payment
                </button>
                <a href="{{ route('admin.bookings.show', $booking->bookingID) }}" 
                   class="block w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
