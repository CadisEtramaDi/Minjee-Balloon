@extends('layouts.admin')

@section('title', 'Check Availability')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Check Availability</h1>
        <p class="text-gray-600">Check balloon availability for specific dates</p>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-sm border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Search Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Search Bookings</h2>
            
            <form method="GET" action="{{ route('admin.availability.check') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                    <input type="date" name="date" required value="{{ request('date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time (Optional)</label>
                        <input type="time" name="start_time" value="{{ request('start_time') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time (Optional)</label>
                        <input type="time" name="end_time" value="{{ request('end_time') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent">
                    </div>
                </div>

                <button type="submit" class="w-full px-6 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                    Search Bookings
                </button>
            </form>
        </div>

        <!-- Results -->
        @if(request('date'))
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Bookings for {{ \Carbon\Carbon::parse(request('date'))->format('F d, Y') }}
                    </h2>
                    @if(request('start_time') && request('end_time'))
                        <p class="text-gray-600 text-sm mt-1">
                            Time: {{ request('start_time') }} - {{ request('end_time') }}
                        </p>
                    @endif
                </div>

                @if($bookings && $bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">ID</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Customer</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Time</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Location</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-sm font-medium text-gray-900">#{{ $booking->bookingID }}</td>
                                        <td class="py-3 px-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->customer->fname }} {{ $booking->customer->lname }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->customer->phonenumber }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->timeStart)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->timeEND)->format('H:i') }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600">
                                            {{ Str::limit($booking->eventLocation, 30) }}
                                        </td>
                                        <td class="py-3 px-4 text-sm">
                                            @php
                                                $statusClass = match($booking->status) {
                                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                                    'Confirmed' => 'bg-green-100 text-green-800',
                                                    'Completed' => 'bg-blue-100 text-blue-800',
                                                    'Cancelled' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $booking->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Date is Available!</h3>
                        <p class="text-gray-600 mb-6">No conflicting bookings on this date.</p>
                        <a href="{{ route('admin.bookings.create') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Booking
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Select a Date</h3>
                <p class="text-gray-600">Choose a date above to check availability and view existing bookings.</p>
            </div>
        @endif
    </div>

    <!-- Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Use</h3>
            
            <div class="space-y-4 text-sm">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-700 font-semibold text-sm">1</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Select Date</p>
                        <p class="text-gray-600">Choose the date you want to check</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-700 font-semibold text-sm">2</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">View Bookings</p>
                        <p class="text-gray-600">See all existing bookings for that day</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-700 font-semibold text-sm">3</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Create Booking</p>
                        <p class="text-gray-600">Add new booking if date is available</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-700">
                    <strong>Tip:</strong> Only shows non-cancelled bookings. Cancelled bookings are not considered conflicts.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
