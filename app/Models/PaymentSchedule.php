<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSchedule extends Model
{
    protected $primaryKey = 'scheduleID';
    protected $table = 'payment_schedules';

    protected $fillable = [
        'bookingID',
        'payment_type',
        'amount_required',
        'amount_paid',
        'status',
        'paid_at',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount_required' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}
