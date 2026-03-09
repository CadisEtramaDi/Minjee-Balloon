<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'itemID';

    protected $fillable = [
        'itemName',
        'category',
        'quantityAvailable',
        'quantityDamaged',
        'rentalPrice',
        'purchase_cost',
        'status',
    ];

    protected $casts = [
        'rentalPrice' => 'decimal:2',
    ];

    /**
     * Get the booking items for this inventory item.
     */
    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class, 'itemID', 'itemID');
    }

    /**
     * Get all bookings that include this item.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_items', 'itemID', 'bookingID')
                    ->withPivot('quantity', 'subtotal')
                    ->withTimestamps();
    }
}
