<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Services\BookingAvailabilityService;
use App\Services\BookingPaymentService;
use App\Services\BookingRentalService;
use App\Services\BookingReturnService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    protected $availabilityService;
    protected $paymentService;
    protected $rentalService;
    protected $returnService;

    public function __construct(
        BookingAvailabilityService $availabilityService,
        BookingPaymentService $paymentService,
        BookingRentalService $rentalService,
        BookingReturnService $returnService
    ) {
        $this->availabilityService = $availabilityService;
        $this->paymentService = $paymentService;
        $this->rentalService = $rentalService;
        $this->returnService = $returnService;
    }

    // =========================================================================
    // WEB METHODS (return blade views)
    // =========================================================================

    /**
     * Delete a pending booking (web)
     * DELETE /admin/bookings/{id}
     */
    public function deleteWeb($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'Pending') {
            return back()->with('error', 'Can only delete Pending bookings. Use Cancel to cancel confirmed bookings.');
        }

        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully!');
    }

    /**
     * Cancel booking with penalty (web)
     * POST /admin/bookings/{id}/cancel
     */
    public function cancelWeb($id)
    {
        $booking = Booking::findOrFail($id);

        try {
            $this->paymentService->cancelBooking($booking);

            return back()->with('success', 'Booking cancelled! Penalty applied: ₱' . number_format($booking->cancellation_penalty, 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Error cancelling booking: ' . $e->getMessage());
        }
    }

    /**
     * Record payment for a booking (web)
     * POST /admin/bookings/{id}/payment
     */
    public function recordPaymentWeb(Request $request, $id)
    {
        $request->validate([
            'amount_paid'      => 'required|numeric|min:0.01',
            'payment_method'   => 'required|in:gcash,cod,company_check,cash',
            'payment_type'     => 'required|in:down_payment,balance_payment,full_payment,damage_charge',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $booking = Booking::findOrFail($id);
        
        // Prevent double form submission (server-side)
        // Check if identical payment was made in the last minute
        $recentPayment = Payment::where('bookingID', $id)
            ->where('payment_type', $request->payment_type)
            ->where('amountpaid', $request->amount_paid)
            ->where('created_at', '>=', now()->subMinute())
            ->first();
            
        if ($recentPayment) {
            return back()->with('error', 'A payment of ₱' . number_format($request->amount_paid, 2) . ' was already recorded moments ago.');
        }

        try {
            Payment::create([
                'bookingID'        => $id,
                'payment_type'     => $request->payment_type,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'completed',
                'amountpaid'       => $request->amount_paid,
                'paymentdate'      => now()->toDateString(),
                'reference_number' => $request->reference_number,
                'notes'            => $request->notes,
            ]);

            // Update matching payment schedule
            $schedule = PaymentSchedule::where('bookingID', $id)
                ->where('payment_type', $request->payment_type)
                ->where('status', 'pending')
                ->first();

            if ($schedule) {
                $schedule->amount_paid += $request->amount_paid;
                if ($schedule->amount_paid >= $schedule->amount_required) {
                    $schedule->status         = 'completed';
                    $schedule->paid_at        = now();
                    $schedule->payment_method = $request->payment_method;
                }
                $schedule->save();
            }

            // Auto-transition booking status via service
            $booking = $this->paymentService->processPayment(
                $booking,
                $request->amount_paid,
                $request->payment_method
            );

            // If booking is Pending-Return, check if all payments now complete
            if ($booking->status === 'Pending-Return') {
                // Check if all payment schedules are now completed
                $incompletePay = $booking->paymentSchedules()
                    ->where('status', '!=', 'completed')
                    ->first();

                if (!$incompletePay) {
                    // All payments are done! Complete the booking
                    $booking = $this->returnService->completeBookingManually($booking);
                    $message = "Payment recorded! ✅ Booking is now COMPLETED - all payments received!";
                    return redirect()->route('admin.bookings.index')->with('success', $message);
                }
            }

            $message = "Payment of ₱{$request->amount_paid} recorded!";
            if ($booking->status === 'Confirmed') {
                $message .= ' ✅ Booking is now CONFIRMED and inventory reserved!';
            }

            return redirect()->route('admin.bookings.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Update booking status (web)
     * PUT /admin/bookings/{id}/status
     */
    public function updateStatusWeb(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
            'totalAmount' => 'nullable|numeric|min:0',
        ]);

        $booking->status = $request->status;
        
        if ($request->has('totalAmount')) {
            $booking->totalAmount = $request->totalAmount;
            
            if ($request->status === 'Awaiting Downpayment') {
                $booking->down_payment_required = $request->totalAmount * 0.50;
                
                // Update or create payment schedules
                $dpSchedule = PaymentSchedule::firstOrNew([
                    'bookingID' => $booking->bookingID,
                    'payment_type' => 'down_payment'
                ]);
                $dpSchedule->amount_required = $booking->down_payment_required;
                if (!$dpSchedule->exists) $dpSchedule->status = 'pending';
                $dpSchedule->save();
                
                $balSchedule = PaymentSchedule::firstOrNew([
                    'bookingID' => $booking->bookingID,
                    'payment_type' => 'balance_payment'
                ]);
                $balSchedule->amount_required = $booking->totalAmount * 0.50;
                if (!$balSchedule->exists) $balSchedule->status = 'pending';
                $balSchedule->save();
            }
        }

        $booking->save();

        return back()->with('success', 'Booking status updated successfully!');
    }

    /**
     * Start rental — Confirmed → In-Use (web)
     * POST /admin/bookings/{id}/start-rental
     */
    public function startRentalWeb(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'Confirmed') {
            return back()->with('error', 'Only Confirmed bookings can start rental. Current status: ' . $booking->status);
        }

        try {
            $booking = $this->rentalService->startRental($booking, now());

            return back()->with('success', '✅ Rental started! Items are now In-Use. Return due: ' . $booking->rental_end_date);
        } catch (\Exception $e) {
            return back()->with('error', 'Error starting rental: ' . $e->getMessage());
        }
    }

    /**
     * Process return of items (web)
     * POST /admin/bookings/{id}/process-return
     */
    public function processReturnWeb(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'In-Use') {
            return back()->with('error', 'Only In-Use bookings can be returned. Current status: ' . $booking->status);
        }

        $request->validate([
            'returned_items'                           => 'required|array|min:1',
            'returned_items.*.booking_item_id'         => 'required|integer',
            'returned_items.*.qty_good'                => 'required|integer|min:0',
            'returned_items.*.qty_partial_damage'      => 'required|integer|min:0',
            'returned_items.*.qty_damaged'             => 'required|integer|min:0',
            'returned_items.*.total_quantity'          => 'required|integer|min:1',
            'notes'                                    => 'nullable|string|max:500',
        ]);

        try {
            // Validate quantities match for each item
            foreach ($request->returned_items as $item) {
                $total = (int)$item['qty_good'] + (int)$item['qty_partial_damage'] + (int)$item['qty_damaged'];
                $required = (int)$item['total_quantity'];
                
                if ($total !== $required) {
                    return back()->with('error', "Item quantity mismatch! Returned {$total} but {$required} were rented. Please adjust all items to match exactly.");
                }
            }

            // Transform the new format into a list of items with conditions
            $transformedItems = [];
            foreach ($request->returned_items as $item) {
                // Add good items
                if ($item['qty_good'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_good'],
                        'condition' => 'good'
                    ];
                }
                // Add partial damage items
                if ($item['qty_partial_damage'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_partial_damage'],
                        'condition' => 'partial_damage'
                    ];
                }
                // Add damaged items
                if ($item['qty_damaged'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_damaged'],
                        'condition' => 'damaged'
                    ];
                }
            }

            $booking = $this->returnService->processReturn(
                $booking,
                $transformedItems,
                $request->notes ?? null
            );

            $message = 'Items returned and assessed!';
            if ($booking->status === 'Completed') {
                $message = '✅ Items returned - Booking is COMPLETED! All payments collected.';
            } elseif ($booking->status === 'Pending-Return') {
                $message = '⏳ Items returned and stored. Status: Pending-Return (awaiting final payment confirmation).';
            }

            $damageCharges = $booking->paymentSchedules()
                ->where('payment_type', 'damage_charge')
                ->where('status', 'pending')
                ->get();

            if ($damageCharges->count() > 0) {
                $message .= ' ⚠️ Damage charges: ₱' . number_format($damageCharges->sum('amount_required'), 2);
            }

            $pendingPayments = $booking->paymentSchedules()
                ->where('status', '!=', 'completed')
                ->get();

            if ($pendingPayments->count() > 0) {
                $totalDue = $pendingPayments->sum('amount_required');
                $message .= " 💳 Total due: ₱" . number_format($totalDue, 2);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing return: ' . $e->getMessage());
        }
    }

    /**
     * List all bookings (web)
     * GET /admin/bookings
     */
    public function index()
    {
        $bookings = Booking::with('customer', 'paymentSchedules')
            ->latest()
            ->paginate(20);

        $statusSummary = [
            'Pending' => Booking::where('status', 'Pending')->count(),
            'Confirmed' => Booking::where('status', 'Confirmed')->count(),
            'In-Use' => Booking::where('status', 'In-Use')->count(),
            'Pending-Return' => Booking::where('status', 'Pending-Return')->count(),
            'Completed' => Booking::where('status', 'Completed')->count(),
            'Cancelled' => Booking::where('status', 'Cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'statusSummary'));
    }

    /**
     * Show booking creation form (web)
     * GET /admin/bookings/create
     */
    public function create()
    {
        $customers = Customer::all();
        $inventoryItems = Inventory::where('status', 'Available')
            ->orWhere('status', 'In-Use')
            ->orderBy('itemName')
            ->get();

        return view('admin.bookings.create', compact('customers', 'inventoryItems'));
    }

    /**
     * Store a new booking (web)
     * POST /admin/bookings
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'fname'         => 'required|string|max:100',
            'lname'         => 'required|string|max:100',
            'phonenumber'   => 'required|string|max:20',
            'address'       => 'nullable|string|max:255',
            'eventDATE'     => 'required|date|after_or_equal:today',
            'eventLocation' => 'required|string|max:255',
            'timeStart'     => 'required|date_format:H:i',
            'timeEND'       => 'required|date_format:H:i',
        ]);

        try {
            // Create or find customer
            $customer = Customer::firstOrCreate(
                [
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'phonenumber' => $request->phonenumber,
                ],
                [
                    'address' => $request->address,
                ]
            );

            // Transform items format: items[itemId] => quantity to items.*.itemID format
            $items = [];
            foreach ($request->input('items', []) as $itemId => $quantity) {
                $items[] = [
                    'itemID' => $itemId,
                    'quantity' => (int)$quantity,
                ];
            }

            if (empty($items)) {
                return back()->withInput()->with('error', 'Please add at least one item to the booking.');
            }

            // Check availability
            if (!$this->availabilityService->canBookItems(
                $items,
                $request->eventDATE,
                $request->timeStart,
                $request->timeEND
            )) {
                return back()->withInput()->with('error', 'One or more items are not available for the selected date/time');
            }

            $totalAmount = 0;

            // Create booking
            $booking = Booking::create([
                'customerID'             => $customer->customerID,
                'eventDATE'              => $request->eventDATE,
                'eventLocation'          => $request->eventLocation,
                'timeStart'              => $request->timeStart,
                'timeEND'                => $request->timeEND,
                'status'                 => 'Pending',
                'totalAmount'            => 0,
            ]);

            // Add booking items
            foreach ($items as $item) {
                $inventory = Inventory::find($item['itemID']);
                if (!$inventory) {
                    throw new \Exception("Inventory item {$item['itemID']} not found");
                }

                $subtotal = $inventory->rentalPrice * $item['quantity'];
                $totalAmount += $subtotal;

                BookingItem::create([
                    'bookingID' => $booking->bookingID,
                    'itemID'    => $item['itemID'],
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $subtotal,
                ]);
            }

            // Update booking amount
            $booking->totalAmount = $totalAmount;
            $booking->down_payment_required = $totalAmount * 0.50;
            $booking->save();

            // Create payment schedules
            PaymentSchedule::create([
                'bookingID'       => $booking->bookingID,
                'payment_type'    => 'down_payment',
                'amount_required' => $booking->down_payment_required,
                'status'          => 'pending',
            ]);

            PaymentSchedule::create([
                'bookingID'       => $booking->bookingID,
                'payment_type'    => 'balance_payment',
                'amount_required' => $totalAmount * 0.50,
                'status'          => 'pending',
            ]);

            return redirect()->route('admin.bookings.show', $booking->bookingID)
                ->with('success', '✅ Booking created successfully! Down payment required: ₱' . number_format($booking->down_payment_required, 2));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating booking: ' . $e->getMessage());
        }
    }

    /**
     * Show booking details (web)
     * GET /admin/bookings/{id}
     */
    public function showWeb($id)
    {
        $booking = Booking::with('customer', 'bookingItems.item', 'payments', 'paymentSchedules')
            ->findOrFail($id);

        // Calculate total paid and remaining balance
        $totalPaid = $booking->paymentSchedules()
            ->where('status', 'completed')
            ->sum('amount_required');
        
        $remainingBalance = $booking->totalAmount - $totalPaid;
        
        $pendingPayments = $booking->paymentSchedules()
            ->where('status', '!=', 'completed')
            ->get();

        return view('admin.bookings.show', compact('booking', 'totalPaid', 'remainingBalance', 'pendingPayments'));
    }

    /**
     * Show booking edit form (web)
     * GET /admin/bookings/{id}/edit
     */
    public function edit($id)
    {
        $booking = Booking::with('bookingItems.item')->findOrFail($id);

        $totalPaid = $booking->payments->sum('amountpaid');
        $isPartialPayment = ($booking->totalAmount > 0 && $totalPaid > 0 && $totalPaid < $booking->totalAmount);

        if ($booking->status !== 'Pending' && !$isPartialPayment) {
            return back()->with('error', 'Can only edit Pending bookings or bookings with Partial payments.');
        }

        $customers = Customer::all();
        $inventoryItems = Inventory::where('status', 'Available')
            ->orWhere('status', 'In-Use')
            ->orderBy('itemName')
            ->get();
            
        $remainingBalance = $booking->totalAmount - $totalPaid;

        return view('admin.bookings.edit', compact('booking', 'customers', 'inventoryItems', 'totalPaid', 'remainingBalance'));
    }

    /**
     * Show booking availability calendar (web)
     * GET /admin/availability
     */
    public function availabilityPage()
    {
        return view('admin.bookings.availability');
    }

    /**
     * Check item availability (ajax)
     * GET /admin/check-availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'itemID'     => 'required|exists:inventory,itemID',
            'eventDATE'  => 'required|date',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            $inventory = Inventory::find($request->itemID);
            $available = $this->availabilityService->getAvailableQuantity(
                $request->itemID,
                $request->eventDATE
            );

            if ($available >= $request->quantity) {
                return response()->json([
                    'available' => true,
                    'quantity'  => $available,
                    'message'   => "✅ {$available} units available",
                ]);
            } else {
                return response()->json([
                    'available' => false,
                    'quantity'  => $available,
                    'message'   => "❌ Only {$available} units available (need {$request->quantity})",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message'   => 'Error checking availability: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // API METHODS (return JSON)
    // =========================================================================

    /**
     * Get booking events for fullcalendar
     * GET /api/availability/events
     */
    public function getCalendarEvents(Request $request)
    {
        $query = Booking::whereIn('status', ['Confirmed', 'In-Use', 'Pending'])
            ->with('bookingItems.item', 'customer');

        if ($request->has('start')) {
            $query->where('eventDATE', '>=', date('Y-m-d', strtotime($request->start)));
        }
        if ($request->has('end')) {
            $query->where('eventDATE', '<=', date('Y-m-d', strtotime($request->end)));
        }

        $bookings = $query->get();

        $events = $bookings->map(function ($booking) {
            $color = '#3b82f6'; // blue for Confirmed
            if ($booking->status === 'In-Use') $color = '#22c55e'; // green
            elseif ($booking->status === 'Pending') $color = '#f59e0b'; // amber

            $itemsList = $booking->bookingItems->map(function ($bItem) {
                return $bItem->quantity . 'x ' . ($bItem->item ? $bItem->item->itemName : 'Unknown');
            })->toArray();

            $dateStr = \Carbon\Carbon::parse($booking->eventDATE)->format('Y-m-d');
            $startTime = $dateStr . 'T' . $booking->timeStart;
            $endTime = $dateStr . 'T' . $booking->timeEND;

            return [
                'id' => $booking->bookingID,
                'title' => 'Booking #' . $booking->bookingID . ' (' . count($itemsList) . ' Items)',
                'start' => $startTime,
                'end' => $endTime,
                'color' => $color,
                'extendedProps' => [
                    'status' => $booking->status,
                    'items' => $itemsList,
                    'customerName' => $booking->customer ? ($booking->customer->fname . ' ' . $booking->customer->lname) : 'Unknown',
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Create a new booking (API)
     * POST /api/bookings
     */
    public function store(Request $request)
    {
        $request->validate([
            'customerID'    => 'required|exists:customers,customerID',
            'eventDATE'     => 'required|date|after_or_equal:today',
            'eventLocation' => 'required|string|max:255',
            'timeStart'     => 'required|date_format:H:i',
            'timeEnd'       => 'required|date_format:H:i',
            'items'         => 'required|array|min:1',
            'items.*.itemID' => 'required|exists:inventory,itemID',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Check availability
            if (!$this->availabilityService->canBookItems(
                $request->items,
                $request->eventDATE,
                $request->timeStart,
                $request->timeEnd
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more items are not available for the selected date/time',
                ], 400);
            }

            $totalAmount = 0;

            // Create booking
            $booking = Booking::create([
                'customerID'             => $request->customerID,
                'eventDATE'              => $request->eventDATE,
                'eventLocation'          => $request->eventLocation,
                'timeStart'              => $request->timeStart,
                'timeEND'                => $request->timeEnd,
                'status'                 => 'Pending',
                'totalAmount'            => 0, // Will calculate
            ]);

            // Add booking items
            foreach ($request->items as $item) {
                $inventory = Inventory::find($item['itemID']);
                $subtotal = $inventory->rentalPrice * $item['quantity'];
                $totalAmount += $subtotal;

                BookingItem::create([
                    'bookingID' => $booking->bookingID,
                    'itemID'    => $item['itemID'],
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $subtotal,
                ]);
            }

            // Update booking amount
            $booking->totalAmount = $totalAmount;
            $booking->down_payment_required = $totalAmount * 0.50;
            $booking->save();

            // Create payment schedules
            PaymentSchedule::create([
                'bookingID'       => $booking->bookingID,
                'payment_type'    => 'down_payment',
                'amount_required' => $booking->down_payment_required,
                'status'          => 'pending',
            ]);

            PaymentSchedule::create([
                'bookingID'       => $booking->bookingID,
                'payment_type'    => 'balance_payment',
                'amount_required' => $totalAmount * 0.50,
                'status'          => 'pending',
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Booking created successfully',
                'bookingID' => $booking->bookingID,
                'status'   => $booking->status,
                'booking'  => $booking,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get booking details
     * GET /api/bookings/{bookingID}
     */
    public function show($bookingID)
    {
        $booking = Booking::with('customer', 'bookingItems.item', 'payments', 'paymentSchedules')->find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json(['success' => true, 'booking' => $booking]);
    }

    /**
     * Get all bookings for a customer
     * GET /api/customers/{customerID}/bookings
     */
    public function getCustomerBookings($customerID)
    {
        $bookings = Booking::where('customerID', $customerID)->with('payments', 'paymentSchedules')->get();
        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /**
     * Get bookings by status
     * GET /api/bookings/status/{status}
     */
    public function getByStatus($status)
    {
        $bookings = Booking::where('status', ucfirst($status))->with('customer', 'payments')->paginate(20);
        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /**
     * Cancel a booking (API)
     * PUT /api/bookings/{bookingID}/cancel
     */
    public function cancel($bookingID)
    {
        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        if (!$booking->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cannot be cancelled in current status: ' . $booking->status,
            ], 400);
        }

        try {
            $this->paymentService->cancelBooking($booking);

            return response()->json([
                'success'              => true,
                'message'              => 'Booking cancelled successfully',
                'status'               => $booking->status,
                'cancellation_penalty' => $booking->cancellation_penalty,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get pending payments for booking
     * GET /api/bookings/{bookingID}/pending-payments
     */
    public function getPendingPayments($bookingID)
    {
        $payments = PaymentSchedule::where('bookingID', $bookingID)
            ->where('status', 'pending')
            ->get();

        return response()->json(['success' => true, 'pending_payments' => $payments]);
    }

    /**
     * Get booking items
     * GET /api/bookings/{bookingID}/items
     */
    public function getBookingItems($bookingID)
    {
        $booking = Booking::with('bookingItems.item')->find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json(['success' => true, 'items' => $booking->bookingItems]);
    }

    /**
     * Start rental (API)
     * PUT /api/bookings/{bookingID}/start-rental
     */
    public function startRental($bookingID)
    {
        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        if ($booking->status !== 'Confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Only Confirmed bookings can start rental. Current status: ' . $booking->status,
            ], 400);
        }

        try {
            $booking = $this->rentalService->startRental($booking, now());

            return response()->json([
                'success'       => true,
                'message'       => 'Rental started successfully',
                'bookingID'     => $booking->bookingID,
                'status'        => $booking->status,
                'rental_end_date' => $booking->rental_end_date,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process return (API)
     * PUT /api/bookings/{bookingID}/process-return
     */
    public function processReturn(Request $request, $bookingID)
    {
        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        if ($booking->status !== 'In-Use') {
            return response()->json([
                'success' => false,
                'message' => 'Only In-Use bookings can be returned. Current status: ' . $booking->status,
            ], 400);
        }

        $request->validate([
            'items'        => 'required|array|min:1',
            'items.*.booking_item_id' => 'required|integer',
            'items.*.qty_good' => 'required|integer|min:0',
            'items.*.qty_partial_damage' => 'required|integer|min:0',
            'items.*.qty_damaged' => 'required|integer|min:0',
            'notes'        => 'nullable|string|max:500',
        ]);

        try {
            $transformedItems = [];
            foreach ($request->items as $item) {
                if ($item['qty_good'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_good'],
                        'condition' => 'good'
                    ];
                }
                if ($item['qty_partial_damage'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_partial_damage'],
                        'condition' => 'partial_damage'
                    ];
                }
                if ($item['qty_damaged'] > 0) {
                    $transformedItems[] = [
                        'booking_item_id' => $item['booking_item_id'],
                        'quantity' => $item['qty_damaged'],
                        'condition' => 'damaged'
                    ];
                }
            }

            $booking = $this->returnService->processReturn(
                $booking,
                $transformedItems,
                $request->notes ?? null
            );

            return response()->json([
                'success'  => true,
                'message'  => 'Items returned successfully',
                'bookingID' => $booking->bookingID,
                'status'   => $booking->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get rental details for a booking
     * GET /api/bookings/{bookingID}/rental-details
     */
    public function getRentalDetails($bookingID)
    {
        $booking = Booking::with('bookingItems.item')->find($bookingID);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'rental_start' => $booking->rental_start_date,
            'rental_end' => $booking->rental_end_date,
            'status' => $booking->status,
        ]);
    }

    /**
     * Get all in-use bookings
     * GET /api/rentals/in-use
     */
    public function getInUseBookings()
    {
        $bookings = Booking::where('status', 'In-Use')
            ->with('customer', 'bookingItems.item')
            ->paginate(20);

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /**
     * Get all pending-return bookings
     * GET /api/rentals/pending-return
     */
    public function getPendingReturns()
    {
        $bookings = Booking::where('status', 'Pending-Return')
            ->with('customer', 'paymentSchedules')
            ->paginate(20);

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }

    /**
     * Get all completed bookings
     * GET /api/rentals/completed
     */
    public function getCompleted()
    {
        $bookings = Booking::where('status', 'Completed')
            ->with('customer', 'payments')
            ->paginate(20);

        return response()->json(['success' => true, 'bookings' => $bookings]);
    }
}
