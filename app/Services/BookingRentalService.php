<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\InventoryTransaction;
use App\Events\BookingStarted;
use Exception;

class BookingRentalService
{
    /**
     * Start rental - Confirmed → In-Use
     * Moves items from Reserved to In-Use state
     */
    public function startRental(Booking $booking, $pickupTime = null)
    {
        if ($booking->status !== 'Confirmed') {
            throw new Exception("Can only start rental for Confirmed bookings. Current: {$booking->status}");
        }

        $pickup = $pickupTime ?? now();
        $booking->rental_start_date = $pickup;
        $booking->rental_end_date = $this->calculateReturnDate($pickup);
        $booking->status = 'In-Use';
        $booking->save();

        // Move items from Reserved to In-Use
        foreach ($booking->bookingItems as $bookingItem) {
            $inventory = $bookingItem->item;

            $prev_reserved = $inventory->quantity_reserved;
            $prev_in_use = $inventory->quantity_in_use;

            // Move from Reserved to In-Use
            $inventory->quantity_reserved -= $bookingItem->quantity;
            $inventory->quantity_in_use += $bookingItem->quantity;
            $inventory->save();

            // Log transaction
            InventoryTransaction::create([
                'itemID' => $inventory->itemID,
                'bookingID' => $booking->bookingID,
                'type' => 'stock_out',
                'transaction_reason' => 'booking_start',
                'quantity' => $bookingItem->quantity,
                'available_before' => $inventory->quantityAvailable,
                'available_after' => $inventory->quantityAvailable,
                'damaged_before' => $inventory->quantityDamaged,
                'damaged_after' => $inventory->quantityDamaged,
                'notes' => "Started rental: {$bookingItem->quantity}x {$inventory->itemName} now In-Use"
            ]);

            $bookingItem->update(['reservation_status' => 'in_use']);
        }

        event(new BookingStarted($booking));
        
        return $booking;
    }

    /**
     * Calculate return date (default: next day 10 AM)
     */
    public function calculateReturnDate($pickupTime)
    {
        return $pickupTime
            ->addDay()
            ->setHour(10)
            ->setMinute(0)
            ->setSecond(0);
    }
}
