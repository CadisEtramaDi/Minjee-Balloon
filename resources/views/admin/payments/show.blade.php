@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors border border-gray-200">
        <span>←</span>
        <span>Back to Payments</span>
    </a>
</div>

<h1 class="text-3xl font-bold text-gray-900 mb-6">Payment Details #{{ $payment->paymentID }}</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Information</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Payment ID:</span>
                <span class="font-semibold text-gray-900">#{{ $payment->paymentID }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Amount Paid:</span>
                <span class="font-bold text-green-600 text-xl">₱{{ number_format($payment->amountpaid, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Payment Date:</span>
                <span class="font-semibold text-gray-900">{{ $payment->paymentdate->format('F d, Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Payment Method:</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    {{ ucfirst(str_replace('_', ' ', $payment->paymentmethod)) }}
                </span>
            </div>
            @if($payment->reference_number)
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Reference Number:</span>
                <span class="font-medium text-gray-900">{{ $payment->reference_number }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status:</span>
                <span>
                    @if($payment->status === 'completed')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Completed</span>
                    @elseif($payment->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Failed</span>
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                <span class="text-gray-600">Recorded On:</span>
                <span class="text-sm text-gray-500">{{ $payment->created_at->format('F d, Y h:i A') }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Information</h2>
        @if($payment->booking)
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Booking ID:</span>
                <a href="{{ route('admin.bookings.show', $payment->booking->bookingID) }}" class="text-[#0EA5E9] hover:underline font-semibold">
                    #{{ $payment->booking->bookingID }}
                </a>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Customer:</span>
                <span class="font-semibold text-gray-900">
                    {{ $payment->booking->customer->fname ?? 'N/A' }} 
                    {{ $payment->booking->customer->lname ?? '' }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Phone:</span>
                <span class="text-gray-900">{{ $payment->booking->customer->phonenumber ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Event Date:</span>
                <span class="text-gray-900">{{ $payment->booking->eventDATE ? \Carbon\Carbon::parse($payment->booking->eventDATE)->format('F d, Y') : 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Event Time:</span>
                <span class="text-gray-900">{{ $payment->booking->timeStart ? date('h:i A', strtotime($payment->booking->timeStart)) : 'N/A' }} - {{ $payment->booking->timeEND ? date('h:i A', strtotime($payment->booking->timeEND)) : 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-bold text-gray-900 text-lg">₱{{ number_format($payment->booking->totalAmount ?? 0, 2) }}</span>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">No booking information available for this payment.</p>
        </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('admin.bookings.show', $payment->booking->bookingID) }}" 
       class="inline-block px-6 py-3 bg-[#0EA5E9] text-white rounded-lg font-semibold hover:bg-sky-600 transition-colors">
        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        View Full Booking
    </a>
</div>
@endsection
