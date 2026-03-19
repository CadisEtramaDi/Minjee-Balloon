<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $table = 'booking_items';
    protected $primaryKey = 'bookingItemID';

    protected $fillable = [
        'bookingID',
        'itemID',
        'quantity',
        'subtotal',
        'reservation_status',
        'returned_at',
        'return_condition',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'returned_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }

    public function item()
    {
        return $this->belongsTo(Inventory::class, 'itemID', 'itemID');
    }
}