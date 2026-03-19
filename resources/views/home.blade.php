@extends('layouts.app')

@section('title', 'Home - Minjee Balloon')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F8FAFC] via-white to-sky-50">
    
    <!-- Hero Section with Balloon Background -->
    <section class="relative overflow-hidden min-h-[600px] sm:min-h-[700px]">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('images/balloon-background.jpg') }}" 
                alt="Colorful Balloons" 
                class="w-full h-full object-cover"
            />
            <!-- Gradient Overlay for better text readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/50"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-28">
            <div class="text-center">
                <div class="inline-block mb-4 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full shadow-lg border border-white/30">
                    <span class="text-sm font-semibold text-white">✨ Premium Event Decorations</span>
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 leading-tight drop-shadow-lg">
                    Transform Your Events Into
                    <span class="block bg-gradient-to-r from-sky-300 to-purple-400 bg-clip-text text-transparent drop-shadow-md">
                        Unforgettable Memories
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/90 mb-10 max-w-3xl mx-auto px-4 drop-shadow-md font-medium">
                    Elevate your special occasions with our exquisite balloon decorations. From intimate gatherings to grand celebrations, we bring your vision to life with creativity and elegance.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center px-4">
                    <a 
                        href="/create-booking" 
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#0EA5E9] to-sky-500 text-white rounded-xl font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300 shadow-xl"
                    >
                        Book Your Event Now →
                    </a>
                    <a 
                        href="/check-availability" 
                        class="w-full sm:w-auto px-8 py-4 bg-white/20 backdrop-blur-md text-white border-2 border-white/40 rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 shadow-lg"
                    >
                        Check Availability
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Why Choose <span class="text-[#0EA5E9]">Minjee Balloon</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    We provide exceptional service and quality that sets us apart
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-sky-100 to-sky-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Booking</h3>
                    <p class="text-gray-600 leading-relaxed">Quick and hassle-free booking process. Schedule your event in just a few clicks and get instant confirmation.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Quality</h3>
                    <p class="text-gray-600 leading-relaxed">Only the finest materials and balloons. We ensure every decoration meets our high standards of excellence.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Professional Team</h3>
                    <p class="text-gray-600 leading-relaxed">Experienced decorators dedicated to bringing your vision to life with creativity and precision.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Custom Designs</h3>
                    <p class="text-gray-600 leading-relaxed">Tailored decorations that match your theme perfectly. We work closely with you to realize your dream setup.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Affordable Pricing</h3>
                    <p class="text-gray-600 leading-relaxed">Competitive rates without compromising quality. Get the best value for your investment.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Fast Setup</h3>
                    <p class="text-gray-600 leading-relaxed">Efficient and timely installation. We ensure everything is perfect before your guests arrive.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Types Section -->
    <section id="services" class="py-16 sm:py-20 lg:py-24 bg-gradient-to-br from-gray-50 to-sky-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Perfect For Every <span class="text-[#0EA5E9]">Occasion</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    We specialize in creating stunning decorations for all types of events
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                <!-- Event Type 1 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-[#0EA5E9]">
                    <div class="text-center">
                        <div class="text-5xl sm:text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">💍</div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Weddings</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Beautiful & Elegant</p>
                    </div>
                </div>

                <!-- Event Type 2 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-purple-500">
                    <div class="text-center">
                        <div class="text-5xl sm:text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">🎂</div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Birthdays</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Fun & Colorful</p>
                    </div>
                </div>

                <!-- Event Type 3 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-green-500">
                    <div class="text-center">
                        <div class="text-5xl sm:text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">💼</div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Corporate</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Professional & Sleek</p>
                    </div>
                </div>

                <!-- Event Type 4 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-yellow-500">
                    <div class="text-center">
                        <div class="text-5xl sm:text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">🎓</div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Conferences</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Modern & Stylish</p>
                    </div>
                </div>

                <!-- Event Type 5 -->
                <div class="group bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-pink-500 col-span-2 sm:col-span-1">
                    <div class="text-center">
                        <div class="text-5xl sm:text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">🎉</div>
                        <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">Special Events</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Unique & Custom</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-[#0EA5E9] via-sky-600 to-purple-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE2YzAtNy43ODItNi4zLTE0LTE0LTE0UzggOC4yMTggOCAxNnM2LjMgMTQgMTQgMTQgMTQtNi4zIDE0LTE0ek0yMiA2QzE0LjI2OCA2IDggMTIuMjY4IDggMjBzNi4yNjggMTQgMTQgMTQgMTQtNi4yNjggMTQtMTRTMjkuNzMyIDYgMjIgNnoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-10"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                Ready to Create Something Amazing?
            </h2>
            <p class="text-lg sm:text-xl text-sky-100 mb-10 max-w-2xl mx-auto">
                Let's make your next event unforgettable. Book now and get a special discount on your first order!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a 
                    href="/create-booking" 
                    class="w-full sm:w-auto px-8 py-4 bg-white text-[#0EA5E9] rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105"
                >
                    Book Your Event Now
                </a>
                <a 
                    href="#services" 
                    class="w-full sm:w-auto px-8 py-4 bg-transparent text-white border-2 border-white rounded-xl font-semibold hover:bg-white hover:text-[#0EA5E9] transition-all duration-300"
                >
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white text-xl font-bold mb-4">Minjee Balloon</h3>
                    <p class="text-gray-400 leading-relaxed">Creating magical moments through beautiful balloon decorations for every occasion.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="/create-booking" class="hover:text-white transition-colors">Book Event</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Services</a></li>
                        <li><a href="/my-bookings" class="hover:text-white transition-colors">My Bookings</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="/create-booking" class="hover:text-white transition-colors">Weddings</a></li>
                        <li><a href="/create-booking" class="hover:text-white transition-colors">Birthdays</a></li>
                        <li><a href="/create-booking" class="hover:text-white transition-colors">Corporate Events</a></li>
                        <li><a href="/create-booking" class="hover:text-white transition-colors">Special Events</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📧 info@minjeeballoon.com</li>
                        <li>📱 +1 (555) 123-4567</li>
                        <li>📍 123 Event Street, City</li>
                        <li>🕒 Mon-Sat: 9AM - 6PM</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Minjee Balloon. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 0.2; }
        50% { opacity: 0.3; }
    }
    .animate-pulse {
        animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection
