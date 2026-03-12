@extends('layouts.admin')

@section('title', 'Create New Booking')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Booking</h1>
        <p class="text-gray-600">Register a new customer booking</p>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium shadow-sm border border-gray-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back
    </a>
</div>

<div class="bg-white rounded-xl shadow-md p-8 max-w-5xl  mx-auto">
    <form id="bookingForm" action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="customerID" value="new">

        <!-- Customer Section -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="fname" required placeholder="John" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('fname') border-red-500 @enderror"
                           value="{{ old('fname') }}">
                    @error('fname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="lname" required placeholder="Doe" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('lname') border-red-500 @enderror"
                           value="{{ old('lname') }}">
                    @error('lname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" name="phonenumber" required placeholder="+63 9xx-xxx-xxxx" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('phonenumber') border-red-500 @enderror"
                           value="{{ old('phonenumber') }}">
                    @error('phonenumber')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" placeholder="Street address" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent"
                           value="{{ old('address') }}">
                </div>
            </div>
        </div>

        <!-- Booking Details Section -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                    <input type="date" name="eventDATE" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('eventDATE') border-red-500 @enderror"
                           value="{{ old('eventDATE') }}">
                    @error('eventDATE')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <input type="time" name="timeStart" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('timeStart') border-red-500 @enderror"
                           value="{{ old('timeStart') }}">
                    @error('timeStart')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <input type="time" name="timeEND" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('timeEND') border-red-500 @enderror"
                           value="{{ old('timeEND') }}">
                    @error('timeEND')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Location *</label>
                <textarea name="eventLocation" required rows="3" placeholder="Venue or event location..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent @error('eventLocation') border-red-500 @enderror">{{ old('eventLocation') }}</textarea>
                @error('eventLocation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Inventory Items Section -->
        <div class="border-b pb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Items to Book</h3>
            <button type="button" onclick="toggleItemSelector()" class="text-sm px-3 py-1 bg-sky-100 text-[#0EA5E9] rounded-md hover:bg-sky-200 transition-colors font-medium border border-[#0EA5E9]">
                + Add Item
            </button>
        </div>

        <div id="itemSelector" class="hidden mb-6 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select an item from Inventory:</label>
            <select id="itemDropdown" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9]" onchange="updateMaxQty()">
                <option value="">-- Choose an Item --</option>
                @foreach($inventoryItems as $item)
                    @php $availableQty = $item->quantityAvailable - $item->quantityDamaged; @endphp
                    @if($availableQty > 0)
                        <option value="{{ $item->itemID }}" 
                                data-name="{{ $item->itemName }}" 
                                data-price="{{ $item->rentalPrice }}" 
                                data-max="{{ $availableQty }}">
                            {{ $item->itemName }} (₱{{ number_format($item->rentalPrice, 2) }} | Avail: {{ $availableQty }})
                        </option>
                    @endif
                @endforeach
            </select>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity:</label>
                <div class="flex items-center gap-3">
                    <input type="number" id="itemQuantity" min="1" max="1" value="1" 
                           class="w-28 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent">
                    <span id="maxQtyHint" class="text-xs text-gray-500"></span>
                </div>
            </div>
            <button type="button" onclick="addItemToBooking()" class="mt-3 w-full py-2 bg-gray-800 text-white rounded-lg text-sm">Add to List</button>
        </div>

        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full" id="selectedItemsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-600">Item</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-600">Price</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-600">Quantity</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-600">Total</th>
                        <th class="text-left py-3 px-4 text-xs uppercase text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="selectedItemsBody">
                    <tr id="emptyRow">
                        <td colspan="5" class="py-8 text-center text-gray-400 text-sm">No items added yet. Click "+ Add Item" to start.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>      

        <!-- Payment Section -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Amount</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount (₱)</label>
                <div id="totalAmountDisplay" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-lg font-bold text-gray-900">₱0.00</div>
                <input type="hidden" id="totalAmount" name="totalAmount" value="0">
                @error('totalAmount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Cancel
            </a>
            <button type="button" onclick="openConfirmationModal()" class="px-6 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                Create Booking
            </button>
        </div>
    </form>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-[#0EA5E9] to-sky-600 px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-bold text-white">Confirm Booking</h3>
        </div>
        
        <div class="px-6 py-4">
            <p class="text-gray-700 mb-4">Are you sure you want to create this booking?</p>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-4 mb-4">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold">Total Amount:</span> 
                    <span class="text-blue-600 font-bold">₱<span id="modalTotalAmount">0.00</span></span>
                </p>

            </div>

            <p class="text-xs text-gray-500">This action will create the booking and allocate the selected inventory items.</p>
        </div>
        
        <div class="flex gap-3 px-6 py-4 bg-gray-50 rounded-b-lg">
            <button type="button" onclick="closeConfirmationModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                Cancel
            </button>
            <button type="button" onclick="submitBookingForm(event)" class="flex-1 px-4 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                Confirm Booking
            </button>
        </div>
    </div>
</div>

<script>
    // 1. Toggle the Item Selector Visibility
    function toggleItemSelector() {
        const selector = document.getElementById('itemSelector');
        selector.classList.toggle('hidden');
    }

    // Update max quantity hint when item is selected
    function updateMaxQty() {
        const dropdown = document.getElementById('itemDropdown');
        const qtyInput = document.getElementById('itemQuantity');
        const hint = document.getElementById('maxQtyHint');
        const selectedOption = dropdown.options[dropdown.selectedIndex];

        if (selectedOption.value) {
            const max = parseInt(selectedOption.dataset.max);
            qtyInput.max = max;
            qtyInput.value = 1;
            hint.textContent = `Max available: ${max}`;
        } else {
            qtyInput.max = 1;
            qtyInput.value = 1;
            hint.textContent = '';
        }
    }

    // 2. Add Item to the Table
    function addItemToBooking() {
        const dropdown = document.getElementById('itemDropdown');
        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const qtyInput = document.getElementById('itemQuantity');
        
        if (!selectedOption.value) {
            alert('Please select an item first.');
            return;
        }

        const itemId = selectedOption.value;
        const itemName = selectedOption.dataset.name;
        const itemPrice = selectedOption.dataset.price;
        const itemMax = parseInt(selectedOption.dataset.max);
        let qty = parseInt(qtyInput.value) || 1;

        // Clamp quantity
        if (qty < 1) qty = 1;
        if (qty > itemMax) qty = itemMax;

        // Check if item already exists
        if (document.getElementById(`row-${itemId}`)) {
            alert('Item already added! Adjust the quantity in the table.');
            return;
        }

        // Remove the "Empty" placeholder row
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        const rowTotal = (qty * parseFloat(itemPrice)).toFixed(2);

        // Create new row with the selected quantity
        const rowHTML = `
            <tr id="row-${itemId}" class="border-b border-gray-100">
                <td class="py-3 px-4 font-medium text-gray-900">${itemName}</td>
                <td class="py-3 px-4 text-sm text-gray-700">₱${parseFloat(itemPrice).toFixed(2)}</td>
                <td class="py-3 px-4">
                    <input type="number" name="items[${itemId}]" 
                           class="quantity-input w-20 px-2 py-1 border border-gray-300 rounded-md" 
                           value="${qty}" min="1" max="${itemMax}" 
                           data-price="${itemPrice}" oninput="calculateTotal()">
                </td>
                <td class="py-3 px-4 text-sm font-bold text-sky-600 row-total">₱${rowTotal}</td>
                <td class="py-3 px-4 text-right">
                    <button type="button" onclick="removeRow('${itemId}')" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </td>
            </tr>`;

        document.getElementById('selectedItemsBody').insertAdjacentHTML('beforeend', rowHTML);
        
        calculateTotal();
        toggleItemSelector(); // Hide the selector after adding
        dropdown.selectedIndex = 0; // Reset dropdown
        qtyInput.value = 1;
        qtyInput.max = 1;
        document.getElementById('maxQtyHint').textContent = '';
    }

    // 3. Remove Item from the Table
    function removeRow(id) {
        const row = document.getElementById(`row-${id}`);
        if (row) row.remove();

        // If no rows left, show the "Empty" message again
        const tbody = document.getElementById('selectedItemsBody');
        if (tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="py-8 text-center text-gray-400 text-sm">No items added yet. Click "+ Add Item" to start.</td>
                </tr>`;
        }
        calculateTotal();
    }

    // 4. Calculate Grand Total
    function calculateTotal() {
        let grandTotal = 0;
        const inputs = document.querySelectorAll('.quantity-input');
        
        inputs.forEach(function(input) {
            const row = input.closest('tr');
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(input.getAttribute('data-price')) || 0;
            const rowTotal = qty * price;
            
            // Update individual row total display
            const rowTotalDisplay = row.querySelector('.row-total');
            if (rowTotalDisplay) rowTotalDisplay.textContent = '₱' + rowTotal.toFixed(2);
            
            grandTotal += rowTotal;
        });

        // Update display, hidden input, and modal
        document.getElementById('totalAmountDisplay').textContent = '₱' + grandTotal.toFixed(2);
        document.getElementById('totalAmount').value = grandTotal.toFixed(2);
        document.getElementById('modalTotalAmount').textContent = grandTotal.toFixed(2);
    }

    // 5. Modal Controls
    function openConfirmationModal() {
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    // 6. Submit Form
    function submitBookingForm(event) {
        const form = document.getElementById('bookingForm');
        const confirmBtn = event.target;

        // Visual feedback
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Creating...';

        form.submit();
    }

    // Close modal when clicking background
    window.onclick = function(event) {
        const modal = document.getElementById('confirmationModal');
        if (event.target == modal) {
            closeConfirmationModal();
        }
    }
</script>
@endsection
