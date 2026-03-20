@extends('layouts.admin')

@section('title', 'Booking Summary')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Bookings
    </a>
    <a href="{{ route('admin.bookings.edit', $booking->bookingID) }}" class="inline-flex items-center px-4 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Edit Booking
    </a>
</div>

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

        <!-- Booking Actions Card - NEW AUTOMATED WORKFLOW -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Booking Actions
                </h3>
            </div>
            
            <div class="p-6">
                @if($booking->status === 'Pending')
                    <!-- PENDING STATUS: Waiting for down payment -->
                    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-yellow-800 font-semibold">💰 Waiting for 50% Down Payment</p>
                        <p class="text-yellow-700 text-sm mt-1">Status will automatically change to Confirmed once payment is received.</p>
                    </div>

                    <!-- Pending Payments List -->
                    @if($pendingPayments && count($pendingPayments) > 0)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-blue-900 mb-3">Payment Required:</h4>
                        @foreach($pendingPayments as $payment)
                        <div class="flex justify-between items-center text-blue-800 mb-2">
                            <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}:</span>
                            <span class="font-bold text-lg">₱{{ number_format($payment->amount_required, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Record Payment Form -->
                    <form method="POST" action="{{ route('admin.bookings.payment', $booking->bookingID) }}" class="space-y-4 mb-6">
                        @csrf
                        <h4 class="font-semibold text-gray-900 text-center mb-4">💳 Record Payment</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                            <input type="number" name="amount_paid" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="500.00" value="{{ $booking->down_payment_required }}">
                            <p class="text-xs text-gray-500 mt-1">50% Down Payment: ₱{{ number_format($booking->down_payment_required, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">-- Select Payment Method --</option>
                                <option value="gcash">💳 GCash</option>
                                <option value="company_check">🏢 Company Check</option>
                                <option value="cod">📦 COD (Cash on Delivery)</option>
                                <option value="cash">💵 Cash</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                            <select name="payment_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="down_payment">Down Payment (50%)</option>
                                <option value="balance_payment">Balance Payment</option>
                                <option value="full_payment">Full Payment</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number (Optional)</label>
                            <input type="text" name="reference_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="GC123456789">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Customer name, reference..." rows="2"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition-colors">
                            ✅ Record Payment
                        </button>
                    </form>

                    <!-- Cancel Booking Button -->
                    <form method="POST" action="{{ route('admin.bookings.cancel', $booking->bookingID) }}" onsubmit="return confirm('Cancel this booking? A 20% penalty will be charged on the down payment.');">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition-colors">
                            ❌ Cancel Booking
                        </button>
                    </form>

                @elseif($booking->status === 'Confirmed')
                    <!-- CONFIRMED STATUS: Ready for pickup -->
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                        <p class="text-green-800 font-semibold">✅ Booking Confirmed!</p>
                        <p class="text-green-700 text-sm mt-1">Items are reserved. Ready for customer pickup.</p>
                    </div>

                    <!-- Balance Due (if applicable) -->
                    @if($pendingPayments && count($pendingPayments) > 0)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-blue-900 mb-3">Balance Due:</h4>
                        @foreach($pendingPayments as $payment)
                        <div class="flex justify-between items-center text-blue-800">
                            <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}:</span>
                            <span class="font-bold text-lg">₱{{ number_format($payment->amount_required, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Start Rental Button -->
                    <form method="POST" action="{{ route('admin.bookings.start-rental', $booking->bookingID) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                            🚀 Start Rental (Pickup)
                        </button>
                    </form>

                    <!-- Record Balance Payment Form (for COD) - only show if balance remains -->
                    @if($remainingBalance > 0)
                    <form method="POST" action="{{ route('admin.bookings.payment', $booking->bookingID) }}" class="space-y-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        @csrf
                        <h4 class="font-semibold text-gray-900 text-center">Record Balance/COD Payment</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                            <input type="number" name="amount_paid" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="750.00" value="{{ $booking->totalAmount * 0.50 }}">
                            <p class="text-xs text-gray-500 mt-1">Remaining Balance: ₱{{ number_format($booking->totalAmount * 0.50, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="cod">📦 COD (Cash on Delivery)</option>
                                <option value="cash">💵 Cash</option>
                                <option value="gcash">💳 GCash</option>
                                <option value="company_check">🏢 Company Check</option>
                            </select>
                        </div>

                        <input type="hidden" name="payment_type" value="balance_payment">

                        <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 rounded-lg transition-colors">
                            💳 Record Balance Payment
                        </button>
                    </form>
                    @else
                    <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-green-800 font-semibold">✅ All payments completed!</p>
                    </div>
                    @endif

                    <!-- Cancel Booking -->
                    <form method="POST" action="{{ route('admin.bookings.cancel', $booking->bookingID) }}" onsubmit="return confirm('Cancel this booking? 20% penalty will be charged.');">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg transition-colors">
                            ❌ Cancel Booking
                        </button>
                    </form>

                @elseif($booking->status === 'In-Use')
                    <!-- IN-USE STATUS: Items with customer -->
                    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                        <p class="text-blue-800 font-semibold">⏳ Items In Use</p>
                        <p class="text-blue-700 text-sm mt-1">Return due: <strong>{{ \Carbon\Carbon::parse($booking->rental_end_date)->format('F d, Y h:i A') }}</strong></p>
                    </div>

                    <!-- Record Balance/COD Payment Form (for In-Use) - only show if balance remains -->
                    @if($remainingBalance > 0)
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <h4 class="font-semibold text-yellow-900 mb-3 text-center italic text-sm">Outstanding Balance: ₱{{ number_format($remainingBalance, 2) }}</h4>
                        <form method="POST" action="{{ route('admin.bookings.payment', $booking->bookingID) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                                <input type="number" name="amount_paid" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Amount" value="{{ $remainingBalance }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="cash">💵 Cash</option>
                                    <option value="gcash">💳 GCash</option>
                                    <option value="company_check">🏢 Company Check</option>
                                    <option value="cod">📦 COD</option>
                                </select>
                            </div>

                            <input type="hidden" name="payment_type" value="balance_payment">

                            <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 rounded-lg transition-colors">
                                💳 Record Payment
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Process Return Form -->
                    <form method="POST" action="{{ route('admin.bookings.process-return', $booking->bookingID) }}" class="space-y-4" id="returnForm" onsubmit="return validateReturnQuantities()">
                        @csrf
                        <h4 class="font-semibold text-gray-900 mb-4">Process Item Return & Damage Assessment</h4>
                       
                        @foreach($booking->bookingItems as $item)
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-semibold text-gray-900">{{ $item->item->itemName }}</h5>
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm">Total Qty: {{ $item->quantity }}</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">✅ Good Qty</label>
                                    <input type="number" name="returned_items[{{ $item->bookingItemID }}][qty_good]" 
                                           min="0" max="{{ $item->quantity }}" value="0"
                                           data-item="{{ $item->bookingItemID }}"
                                           data-total="{{ $item->quantity }}"
                                           class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm item-qty" placeholder="0"
                                           onchange="updateItemTotal({{ $item->bookingItemID }})">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">⚠️ Partial Damage Qty</label>
                                    <input type="number" name="returned_items[{{ $item->bookingItemID }}][qty_partial_damage]" 
                                           min="0" max="{{ $item->quantity }}" value="0"
                                           data-item="{{ $item->bookingItemID }}"
                                           data-total="{{ $item->quantity }}"
                                           class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm item-qty" placeholder="0"
                                           onchange="updateItemTotal({{ $item->bookingItemID }})">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">❌ Damaged Qty</label>
                                    <input type="number" name="returned_items[{{ $item->bookingItemID }}][qty_damaged]" 
                                           min="0" max="{{ $item->quantity }}" value="0"
                                           data-item="{{ $item->bookingItemID }}"
                                           data-total="{{ $item->quantity }}"
                                           class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm item-qty" placeholder="0"
                                           onchange="updateItemTotal({{ $item->bookingItemID }})">
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <small class="text-gray-600 text-xs">Split the {{ $item->quantity }} items across conditions above.</small>
                                <small class="font-semibold text-sm" id="total_{{ $item->bookingItemID }}">
                                    <span class="text-gray-600">Total: </span><span class="returned-total">0</span>/{{ $item->quantity }}
                                </small>
                            </div>

                            <input type="hidden" name="returned_items[{{ $item->bookingItemID }}][booking_item_id]" value="{{ $item->bookingItemID }}">
                            <input type="hidden" name="returned_items[{{ $item->bookingItemID }}][total_quantity]" value="{{ $item->quantity }}">
                        </div>
                        @endforeach

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Damage details, scratches, broken parts, etc." rows="3"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition-colors">
                            ✅ Process Return & Complete Check-in
                        </button>
                    </form>

                    <script>
                        function updateItemTotal(itemId) {
                            const inputs = document.querySelectorAll(`input[data-item="${itemId}"]`);
                            let total = 0;
                            inputs.forEach(input => {
                                total += parseInt(input.value) || 0;
                            });
                            const totalElement = document.querySelector(`#total_${itemId} .returned-total`);
                            const maxQty = inputs[0].getAttribute('data-total');
                            
                            if (total > maxQty) {
                                totalElement.textContent = total;
                                totalElement.parentElement.style.color = '#eb5757'; // Red
                            } else if (total === parseInt(maxQty)) {
                                totalElement.textContent = total;
                                totalElement.parentElement.style.color = '#27ae60'; // Green
                            } else {
                                totalElement.textContent = total;
                                totalElement.parentElement.style.color = '#f39c12'; // Orange
                            }
                        }

                        function validateReturnQuantities() {
                            let isValid = true;
                            const itemElements = document.querySelectorAll('[data-item]');
                            const processedItems = new Set();

                            itemElements.forEach(element => {
                                const itemId = element.getAttribute('data-item');
                                if (processedItems.has(itemId)) return;
                                processedItems.add(itemId);

                                const inputs = document.querySelectorAll(`input[data-item="${itemId}"]`);
                                const maxQty = parseInt(inputs[0].getAttribute('data-total'));
                                let total = 0;

                                inputs.forEach(input => {
                                    total += parseInt(input.value) || 0;
                                });

                                if (total !== maxQty) {
                                    isValid = false;
                                    alert(`❌ Item ${itemId}: Returned quantity (${total}) must equal rented quantity (${maxQty}). Please adjust the quantities.`);
                                    return false;
                                }
                            });

                            if (!isValid) return false;
                            return confirm('Confirm return and mark items as processed?');
                        }
                    </script>

                @elseif($booking->status === 'Pending-Return')
                    <!-- PENDING-RETURN STATUS: Awaiting final payments -->
                    <div class="mb-6 p-4 bg-indigo-50 border-l-4 border-indigo-400 rounded">
                        <p class="text-indigo-800 font-semibold">⏳ Pending Final Payments</p>
                        <p class="text-indigo-700 text-sm mt-1">Items have been returned. Awaiting payment for any damage charges or balance.</p>
                        <p class="text-indigo-700 text-sm mt-2"><strong>Returned at:</strong> {{ \Carbon\Carbon::parse($booking->actual_return_date)->format('F d, Y h:i A') }}</p>
                    </div>

                    <!-- Show pending payments -->
                    @if($pendingPayments && count($pendingPayments) > 0)
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <h4 class="font-semibold text-yellow-900 mb-3">⏳ Pending Payments:</h4>
                        @foreach($pendingPayments as $payment)
                        <div class="flex justify-between items-center text-yellow-800 mb-2">
                            <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}:</span>
                            <span class="font-bold text-lg">₱{{ number_format($payment->amount_required, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Record Damage/Outstanding Payment -->
                    @if($remainingBalance > 0 || ($pendingPayments && count($pendingPayments) > 0))
                    <form method="POST" action="{{ route('admin.bookings.payment', $booking->bookingID) }}" class="space-y-4 mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        @csrf
                        <h4 class="font-semibold text-gray-900 text-center">Record Outstanding Payment</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                            <input type="number" name="amount_paid" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Amount">
                            @if($remainingBalance > 0)
                            <p class="text-xs text-gray-500 mt-1">Remaining Balance: ₱{{ number_format($remainingBalance, 2) }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">-- Select Method --</option>
                                <option value="cash">💵 Cash</option>
                                <option value="gcash">💳 GCash</option>
                                <option value="company_check">🏢 Company Check</option>
                                <option value="cod">📦 COD</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                            <select name="payment_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">-- Select Type --</option>
                                @foreach($pendingPayments as $payment)
                                <option value="{{ $payment->payment_type }}">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg transition-colors">
                            💳 Record Payment
                        </button>
                    </form>
                    @else
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-green-800 font-semibold">✅ All payments complete!</p>
                        <p class="text-green-700 text-sm mt-2">All items have been returned and all payments collected. Booking can now be marked as Completed.</p>
                    </div>
                    @endif

                @elseif($booking->status === 'Completed')
                    <!-- COMPLETED STATUS: Done -->
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                        <p class="text-green-800 font-semibold">✅ Booking Completed!</p>
                        <p class="text-green-700 text-sm mt-1">All items returned and verified.</p>
                        <p class="text-green-700 text-sm mt-2"><strong>Completed at:</strong> {{ $booking->completed_at ? \Carbon\Carbon::parse($booking->completed_at)->format('F d, Y h:i A') : 'N/A' }}</p>
                    </div>

                    <div class="p-4 bg-green-100 rounded-lg">
                        <p class="text-green-800 font-semibold">🎉 This booking is complete!</p>
                    </div>

                @elseif($booking->status === 'Cancelled')
                    <!-- CANCELLED STATUS: Cancelled -->
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <p class="text-red-800 font-semibold">❌ Booking Cancelled</p>
                        <p class="text-red-700 text-sm mt-1">Cancelled at: {{ $booking->cancelled_at ? \Carbon\Carbon::parse($booking->cancelled_at)->format('F d, Y h:i A') : 'N/A' }}</p>
                        @if($booking->cancellation_penalty > 0)
                        <p class="text-red-700 text-sm mt-2"><strong>Cancellation Penalty:</strong> ₱{{ number_format($booking->cancellation_penalty, 2) }}</p>
                        @endif
                    </div>
                @endif
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
                <table class="w-full min-w-[650px]">
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
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                @if($payment->payment_status === 'completed')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Completed</span>
                                @elseif($payment->payment_status === 'pending')
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

        <!-- Approve Booking Form - removed (view-only summary) -->
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
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
    </div>
</div>
<script>
    // Prevent double form submission on ALL forms
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (form.dataset.submitted === 'true') {
                e.preventDefault();
                return false;
            }
            form.dataset.submitted = 'true';
            if (submitBtn) {
            setTimeout(function() {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.innerHTML = 'Processing...';
            }, 10);
            }
        });
    });
</script>
@endsection
