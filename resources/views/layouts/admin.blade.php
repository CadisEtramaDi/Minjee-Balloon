<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')Minjee Balloon Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200" style="height: 100vh; margin: 0; display: flex; flex-direction: column; overflow: hidden;">
    <!-- Top Navigation -->
    <nav class="z-50 w-full bg-white shadow-md border-b border-gray-200" style="flex-shrink: 0;">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-[#0EA5E9]">
                        Minjee Balloon Admin
                    </a>
                </div>
                <!-- Empty placeholder for flex layout if needed, or just removed -->
            </div>
    </nav>

    <!-- Main Content -->
    <div style="display: flex; flex: 1 1 0%; min-height: 0; overflow: hidden;">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-50 shadow-md p-6 z-40 flex flex-col" style="flex-shrink: 0; overflow-y: auto;">
            <nav class="space-y-2 flex-grow">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sky-100 text-[#0EA5E9]' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>

                <button type="button" onclick="toggleBookingsMenu()"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="flex-1 text-left">Bookings</span>
                    <svg id="bookings-arrow" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="bookings-menu" class="hidden space-y-2 mt-2">
                    <a href="{{ route('admin.bookings.index') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.bookings.index') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Manage Bookings
                    </a>
                    <a href="{{ route('admin.bookings.create') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.bookings.create') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Create Booking
                    </a>
                    <a href="{{ route('admin.availability.check') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.availability.check') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Check Availability
                    </a>
                </div>

                <a href="{{ route('admin.customers.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-sky-100 text-[#0EA5E9]' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 12H9m4.646-4.354l2.121-2.121M18.364 9.636l2.121-2.121M9.172 9.172L7.05 7.05m2.121 2.121l-2.121 2.121m9.546-4.04l2.121 2.121m-2.121 2.121l2.121 2.121M4 12a8 8 0 1116 0 8 8 0 01-16 0z">
                        </path>
                    </svg>
                    Customers
                </a>

                <button type="button" onclick="toggleInventoryMenu()"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4m8-6v6">
                        </path>
                    </svg>
                    <span class="flex-1 text-left">Inventory</span>
                    <svg id="inventory-arrow" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="inventory-menu" class="hidden space-y-2 mt-2">
                    <a href="{{ route('admin.inventory.index') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.inventory.index') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Manage Inventory
                    </a>
                    <a href="{{ route('admin.inventory.stock-in') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.inventory.stock-in') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Stock In
                    </a>
                    <a href="{{ route('admin.inventory.stock-out') }}"
                        class="flex items-center px-4 py-3 ml-4 text-sm {{ request()->routeIs('admin.inventory.stock-out') ? 'text-[#0EA5E9] font-medium' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        Stock Out
                    </a>
                </div>

                <a href="{{ route('admin.payments.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-sky-100 text-[#0EA5E9]' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Payments
                </a>

                <a href="{{ route('admin.reports.sales') }}"
                    class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-sky-100 text-[#0EA5E9]' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Sales Report
                </a>
            </nav>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-colors font-medium cursor-pointer">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="p-8" style="flex: 1 1 0%; overflow-y: auto; min-height: 0;">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleBookingsMenu() {
            const menu = document.getElementById('bookings-menu');
            const arrow = document.getElementById('bookings-arrow');

            menu.classList.toggle('hidden');
            arrow.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        function toggleInventoryMenu() {
            const menu = document.getElementById('inventory-menu');
            const arrow = document.getElementById('inventory-arrow');

            menu.classList.toggle('hidden');
            arrow.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        // Keep dropdown open if on a bookings or inventory route
        document.addEventListener('DOMContentLoaded', function () {
            const onBookingsRoute = @json(request()->routeIs('admin.bookings.*', 'admin.availability.*'));
            if (onBookingsRoute) {
                const menu = document.getElementById('bookings-menu');
                const arrow = document.getElementById('bookings-arrow');
                menu.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            }

            const onInventoryRoute = @json(request()->routeIs('admin.inventory.*'));
            if (onInventoryRoute) {
                const menu = document.getElementById('inventory-menu');
                const arrow = document.getElementById('inventory-arrow');
                menu.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    </script>
</body>

</html>