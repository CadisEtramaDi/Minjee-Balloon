@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Bookings</h1>
        <p class="text-gray-600">View and manage all customer bookings</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.availability.check') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
            Check Availability
        </a>
        <a href="{{ route('admin.bookings.create') }}" class="px-4 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
            + Create Booking
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone, address..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-6 py-3 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium shadow-sm">
                Filter
            </button>
            <a href="{{ route('admin.bookings.index') }}" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">ID</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Customer</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Event Date/Time</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Amount</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Created</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-semibold text-gray-900">#{{ $booking->bookingID }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ $booking->customer->fname }} {{ $booking->customer->lname }}</div>
                                <div class="text-sm text-gray-500 mt-1">📞 {{ $booking->customer->phonenumber }}</div>
                                <div class="text-xs text-gray-400 mt-1">📍 {{ Str::limit($booking->customer->address, 30) }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->eventDATE)->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500 mt-1">🕐 {{ date('g:i A', strtotime($booking->timeStart)) }} - {{ date('g:i A', strtotime($booking->timeEND)) }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($booking->totalAmount > 0)
                                    <span class="font-semibold text-gray-900">₱{{ number_format($booking->totalAmount, 2) }}</span>
                                @else
                                    <span class="text-yellow-600 text-xs italic bg-yellow-50 px-2 py-1 rounded">Not set</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'paid' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                {{ $booking->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.bookings.show', $booking->bookingID) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#0EA5E9] text-white text-sm rounded-lg hover:bg-sky-600 transition-colors font-medium shadow-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings found</h3>
            <p class="text-gray-500">Try adjusting your filters or search criteria.</p>
        </div>
    @endif
</div>
@endsection
