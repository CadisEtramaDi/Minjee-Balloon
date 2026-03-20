@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Payment Management</h1>
            <a href="{{ route('admin.reports.sales') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Sales Report
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">All Payments</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Payment ID</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Booking ID</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Customer</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Amount Paid</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Payment Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Payment Method</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium text-gray-900">#{{ $payment->paymentID }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.bookings.show', $payment->bookingID) }}"
                                        class="text-[#0EA5E9] hover:underline font-medium">
                                        #{{ $payment->bookingID }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-gray-900">
                                    {{ $payment->booking->customer->fname }}
                                    {{ $payment->booking->customer->lname }}
                                </td>
                                <td class="py-3 px-4 text-green-600 font-semibold">₱{{ number_format($payment->amountpaid, 2) }}
                                </td>
                                <td class="py-3 px-4 text-gray-900">{{ $payment->paymentdate->format('M d, Y') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->payment_status === 'completed')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Completed</span>
                                    @elseif($payment->payment_status === 'pending')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Failed</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.payments.show', $payment->paymentID) }}"
                                        class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 text-lg">No payments found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="mt-6">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection