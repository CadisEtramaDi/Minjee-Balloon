@extends('layouts.admin')

@section('title', 'Manage Customers')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Customers</h1>
    <p class="text-gray-600">View and manage all registered customers</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Search Customers</h2>
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-col md:flex-row gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, phone, or address..."
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent transition-all"
                >
                <button type="submit" class="px-6 py-3 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                    Search
                </button>
                <a href="{{ route('admin.customers.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Reset
                </a>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($customers->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($customers as $customer)
                        <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-lg">
                                    {{ strtoupper(substr($customer->fname, 0, 1) . substr($customer->lname, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $customer->fname }} {{ $customer->lname }}</h3>
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">#{{ $customer->customerID }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">{{ $customer->address }}</p>
                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-600">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $customer->phonenumber }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                            {{ $customer->bookings->count() }} bookings
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $customer->bookings->count() > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $customer->bookings->count() > 0 ? 'Active' : 'New' }}
                                </span>
                                <a href="{{ route('admin.customers.show', $customer->customerID) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0EA5E9] text-white rounded-md text-sm font-semibold hover:bg-sky-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(method_exists($customers, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $customers->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m4.646-4.354l2.121-2.121M18.364 9.636l2.121-2.121M9.172 9.172L7.05 7.05m2.121 2.121l-2.121 2.121m9.546-4.04l2.121 2.121m-2.121 2.121l2.121 2.121M4 12a8 8 0 1116 0 8 8 0 01-16 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No customers found</h3>
                    <p class="text-sm text-gray-500 mt-1">Create a booking to add customers to the system.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="space-y-4">
            <!-- Total Customers Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Total Customers</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $totalCustomers }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM5 20h10v-2a7 7 0 00-10 0v2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Bookings Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Active Bookings</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $activeBookings }}</p>
                    </div>
                    <div class="bg-green-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Payments Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-all border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs font-bold uppercase tracking-widest">Pending Payments</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $pendingPayments }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-2xl p-5">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Tips Section -->
        <div class="bg-white rounded-2xl p-8 mt-6 shadow-md border-l-4 border-blue-500">
            <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-xl">How to Manage</h3>
            </div>
            
            <ul class="space-y-4">
                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500 text-white font-bold group-hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">Search Customers</p>
                        <p class="text-gray-600 text-sm mt-1">Use the search bar to find customers by name, phone, or address.</p>
                    </div>
                </li>

                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-amber-500 text-white font-bold group-hover:bg-amber-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">View Customer Profile</p>
                        <p class="text-gray-600 text-sm mt-1">Open a profile to view full booking history and payments.</p>
                    </div>
                </li>

                <li class="flex items-start group">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-green-500 text-white font-bold group-hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="font-semibold text-gray-900 text-sm leading-5">Add New Customers</p>
                        <p class="text-gray-600 text-sm mt-1">Create new customers through the booking creation form.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
