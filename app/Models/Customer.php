<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customerID';

    protected $fillable = [
        'userID',
        'fname',
        'lname',
        'phonenumber',
        'address',
    ];

    /**
     * Get the user who registered this customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Get the bookings for the customer.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customerID', 'customerID');
    }
}
