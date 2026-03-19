<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'bookingID',
        'payment_type',
        'payment_method',
        'payment_status',
        'amountpaid',
        'paymentdate',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'paymentdate' => 'date',
        'amountpaid' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}