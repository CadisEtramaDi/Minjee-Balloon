<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'itemID',
        'bookingID',
        'type',
        'transaction_reason',
        'quantity',
        'available_before',
        'available_after',
        'damaged_before',
        'damaged_after',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Inventory::class, 'itemID', 'itemID');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}