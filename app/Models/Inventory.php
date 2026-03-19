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
        'quantity_reserved',
        'quantity_in_use',
        'quantityDamaged',
        'rentalPrice',
        'purchase_cost',
        'status',
    ];

    protected $casts = [
        'rentalPrice' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
    ];

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class, 'itemID', 'itemID');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_items', 'itemID', 'bookingID')
                    ->withPivot('quantity', 'subtotal', 'reservation_status')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'itemID', 'itemID');
    }

    // Calculated property for total available (excluding reserved and in-use)
    public function getAvailableStockAttribute()
    {
        return $this->quantityAvailable - $this->quantity_reserved - $this->quantity_in_use;
    }
}