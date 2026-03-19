<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $primaryKey = 'bookingID';
    protected $table = 'bookings';

    protected $fillable = [
        'customerID',
        'eventDATE',
        'eventLocation',
        'timeStart',
        'timeEND',
        'status',
        'totalAmount',
        'down_payment_required',
        'down_payment_paid',
        'down_payment_method',
        'rental_start_date',
        'rental_end_date',
        'actual_return_date',
        'cancellation_penalty',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'eventDATE' => 'date',
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'totalAmount' => 'decimal:2',
        'down_payment_required' => 'decimal:2',
        'down_payment_paid' => 'decimal:2',
        'cancellation_penalty' => 'decimal:2',
    ];

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bookingID', 'bookingID');
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class, 'bookingID', 'bookingID');
    }

    public function items()
    {
        return $this->belongsToMany(Inventory::class, 'booking_items', 'bookingID', 'itemID')
                    ->withPivot('quantity', 'subtotal', 'reservation_status', 'returned_at', 'return_condition')
                    ->withTimestamps();
    }

    public function paymentSchedules()
    {
        return $this->hasMany(PaymentSchedule::class, 'bookingID', 'bookingID');
    }

    // Calculated Properties
    public function getRemainingBalanceAttribute()
    {
        return $this->totalAmount - $this->down_payment_paid;
    }

    public function getIsDownpaymentCompleteAttribute()
    {
        return $this->down_payment_paid >= $this->down_payment_required;
    }

    public function getTotalPendingPaymentAttribute()
    {
        return $this->paymentSchedules()
            ->where('status', 'pending')
            ->sum('amount_required');
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['Pending', 'Confirmed', 'In-Use']);
    }
}