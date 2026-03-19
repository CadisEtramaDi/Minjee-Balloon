<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Services\BookingPaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(BookingPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Record a payment for a booking (Web form)
     * POST /admin/bookings/{id}/payment
     */
    public function recordPaymentWeb(Request $request, $bookingID)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:gcash,cod,company_check,cash',
            'payment_type' => 'required|in:down_payment,full_payment,balance_payment',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking = Booking::find($bookingID);

        if (!$booking) {
            return back()->with('error', 'Booking not found');
        }

        try {
            // Create payment record with status as 'completed'
            $payment = Payment::create([
                'bookingID' => $bookingID,
                'payment_type' => $validated['payment_type'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'completed',  // Explicitly set to completed
                'amountpaid' => $validated['amount_paid'],
                'paymentdate' => now()->toDateString(),
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
            ]);

            // Update payment schedule
            $paymentSchedule = PaymentSchedule::where('bookingID', $bookingID)
                ->where('payment_type', $validated['payment_type'])
                ->where('status', 'pending')
                ->first();

            if ($paymentSchedule) {
                $paymentSchedule->amount_paid += $validated['amount_paid'];
                
                if ($paymentSchedule->amount_paid >= $paymentSchedule->amount_required) {
                    $paymentSchedule->status = 'completed';
                    $paymentSchedule->paid_at = now();
                    $paymentSchedule->payment_method = $validated['payment_method'];
                }

                $paymentSchedule->save();
            }

            // Process payment - THIS WILL AUTO-TRANSITION BOOKING STATUS!
            $booking = $this->paymentService->processPayment(
                $booking,
                $validated['amount_paid'],
                $validated['payment_method']
            );

            $message = "Payment recorded: ₱" . number_format($validated['amount_paid'], 2);
            if ($booking->status === 'Confirmed') {
                $message .= " ✅ Booking CONFIRMED! Inventory reserved!";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Record a payment for a booking
     * POST /api/bookings/{bookingID}/payments
     */
    public function recordPayment(Request $request, $bookingID)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:gcash,cod,company_check,cash',
            'payment_type' => 'required|in:down_payment,full_payment,balance_payment',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Validate payment amount for down payment
        if ($validated['payment_type'] === 'down_payment') {
            $remainingDownPayment = $booking->down_payment_required - $booking->down_payment_paid;

            if ($validated['amount_paid'] > $remainingDownPayment) {
                return response()->json([
                    'message' => 'Payment amount exceeds required down payment',
                    'required' => $remainingDownPayment,
                    'submitted' => $validated['amount_paid']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        try {
            // Create payment record
            $payment = Payment::create([
                'bookingID' => $bookingID,
                'payment_type' => $validated['payment_type'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'completed',
                'amountpaid' => $validated['amount_paid'],
                'paymentdate' => now()->toDateString(),
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
            ]);

            // Update payment schedule
            $paymentSchedule = PaymentSchedule::where('bookingID', $bookingID)
                ->where('payment_type', $validated['payment_type'])
                ->where('status', 'pending')
                ->first();

            if ($paymentSchedule) {
                $paymentSchedule->amount_paid += $validated['amount_paid'];
                
                if ($paymentSchedule->amount_paid >= $paymentSchedule->amount_required) {
                    $paymentSchedule->status = 'completed';
                    $paymentSchedule->paid_at = now();
                    $paymentSchedule->payment_method = $validated['payment_method'];
                }

                $paymentSchedule->save();
            }

            // Process payment and check if down payment complete
            $booking = $this->paymentService->processPayment(
                $booking,
                $validated['amount_paid'],
                $validated['payment_method']
            );

            return response()->json([
                'message' => 'Payment recorded successfully',
                'payment' => $payment,
                'booking_status' => $booking->status,
                'booking' => $booking->load('paymentSchedules'),
                'down_payment_complete' => $booking->is_downpayment_complete
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error recording payment: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all payments for a booking
     * GET /api/bookings/{bookingID}/payments
     */
    public function getBookingPayments($bookingID)
    {
        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $payments = Payment::where('bookingID', $bookingID)
            ->orderBy('paymentdate', 'desc')
            ->get();

        $paymentSchedules = PaymentSchedule::where('bookingID', $bookingID)
            ->get();

        return response()->json([
            'bookingID' => $bookingID,
            'totalAmount' => $booking->totalAmount,
            'downPaymentRequired' => $booking->down_payment_required,
            'downPaymentPaid' => $booking->down_payment_paid,
            'remainingBalance' => $booking->remaining_balance,
            'totalPendingPayment' => $booking->total_pending_payment,
            'payments' => $payments,
            'paymentSchedules' => $paymentSchedules,
        ]);
    }

    /**
     * Get all payments by status
     * GET /api/payments?status=completed
     */
    public function getByStatus(Request $request)
    {
        $status = $request->query('payment_status');

        $query = Payment::query();

        if ($status) {
            $query->where('payment_status', $status);
        }

        $payments = $query->with('booking')
            ->orderBy('paymentdate', 'desc')
            ->paginate(15);

        return response()->json($payments);
    }

    /**
     * Get payment breakdown for a booking
     * GET /api/bookings/{bookingID}/payment-breakdown
     */
    public function getPaymentBreakdown($bookingID)
    {
        $booking = Booking::find($bookingID);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $breakdown = [
            'bookingID' => $bookingID,
            'status' => $booking->status,
            'totalAmount' => (float) $booking->totalAmount,
            'downPayment' => [
                'required' => (float) $booking->down_payment_required,
                'paid' => (float) $booking->down_payment_paid,
                'remaining' => (float) ($booking->down_payment_required - $booking->down_payment_paid),
                'complete' => $booking->is_downpayment_complete,
            ],
            'balancePayment' => [
                'required' => (float) ($booking->totalAmount * 0.50),
                'paid' => (float) ($booking->down_payment_paid > $booking->down_payment_required 
                    ? $booking->down_payment_paid - $booking->down_payment_required 
                    : 0),
                'remaining' => (float) ($booking->remaining_balance),
            ],
            'penalties' => [
                'cancellation' => (float) $booking->cancellation_penalty,
            ],
            'damages' => [
                'total' => (float) $booking->paymentSchedules()
                    ->where('payment_type', 'damage_charge')
                    ->sum('amount_required'),
            ],
            'totalPending' => (float) $booking->total_pending_payment,
        ];

        return response()->json($breakdown);
    }

    /**
     * List all payments (Web view)
     * GET /admin/payments
     */
    public function index(Request $request)
    {
        $query = Payment::with('booking.customer');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('booking.customer', function($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('paymentdate', 'desc')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show single payment (Web view)
     * GET /admin/payments/{paymentID}
     */
    public function show($paymentID)
    {
        $payment = Payment::with('booking.customer')->findOrFail($paymentID);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show create payment form (Web view)
     * GET /admin/bookings/{id}/payment/create
     */
    public function create($bookingID)
    {
        $booking = Booking::with('payments', 'paymentSchedules')->findOrFail($bookingID);
        
        $totalPaid = $booking->payments->sum('amountpaid');
        $remainingBalance = $booking->totalAmount - $totalPaid;
        
        $pendingPayments = $booking->paymentSchedules()
            ->where('status', '!=', 'completed')
            ->get();
            
        return view('admin.payments.create', compact('booking', 'totalPaid', 'remainingBalance', 'pendingPayments'));
    }

    /**
     * Store new payment (Web form submission)
     * POST /admin/bookings/{id}/payment
     */
    public function store(Request $request, $bookingID)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:gcash,cod,company_check,cash',
            'payment_type' => 'required|in:down_payment,balance_payment,full_payment,damage_charge',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking = Booking::findOrFail($bookingID);

        try {
            // Create payment
            $payment = Payment::create([
                'bookingID' => $bookingID,
                'payment_type' => $validated['payment_type'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'completed',
                'amountpaid' => $validated['amount_paid'],
                'paymentdate' => now()->toDateString(),
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
            ]);

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Payment recorded successfully: ₱' . number_format($validated['amount_paid'], 2));
        } catch (\Exception $e) {
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Sales Report (Web view)
     * GET /admin/reports/sales
     */
    public function salesReport(Request $request)
    {
        $query = Payment::where('payment_status', 'completed');

        // Filter by month if provided
        if ($request->filled('month')) {
            $query->whereMonth('paymentdate', $request->month);
        }

        // Filter by year if provided
        if ($request->filled('year')) {
            $query->whereYear('paymentdate', $request->year);
        }

        $totalSales = (clone $query)->sum('amountpaid');
        $totalPayments = (clone $query)->count();
        $totalTransactions = $totalPayments; // Same as total payments
        
        $payments = $query->with('booking.customer')
            ->orderBy('paymentdate', 'desc')
            ->paginate(30);

        // Summary by payment method
        $salesByMethod = Payment::where('payment_status', 'completed')
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amountpaid) as total')
            ->get();

        // Summary by payment type
        $salesByType = Payment::where('payment_status', 'completed')
            ->groupBy('payment_type')
            ->selectRaw('payment_type, COUNT(*) as count, SUM(amountpaid) as total')
            ->get();

        // Daily sales for trend chart
        $dailySales = Payment::where('payment_status', 'completed')
            ->selectRaw('DATE(paymentdate) as date, SUM(amountpaid) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return view('admin.reports.sales', compact(
            'payments',
            'totalSales',
            'totalPayments',
            'totalTransactions',
            'salesByMethod',
            'salesByType',
            'dailySales'
        ));
    }
}