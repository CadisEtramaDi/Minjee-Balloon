<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Inventory;
use Exception;

class BookingAvailabilityService
{
    /**
     * Check if items are available for the given date and time
     * Deny overbooking by checking against confirmed/in-use bookings
     */
    public function canBookItems($items, $eventDate, $timeStart, $timeEnd)
    {
        foreach ($items as $item) {
            if (!$this->isItemAvailable($item['itemID'], $item['quantity'], $eventDate, $timeStart, $timeEnd)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if single item is available for the specified time range
     */
    public function isItemAvailable($itemID, $requestedQty, $eventDate, $timeStart, $timeEnd)
    {
        $inventory = Inventory::findOrFail($itemID);

        // Simple availability: just check quantity available
        if ($inventory->quantityAvailable < $requestedQty) {
            return false;
        }

        // Check for conflicting bookings on same date
        $conflicting = $this->findConflictingBookings(
            $itemID,
            $eventDate,
            $timeStart,
            $timeEnd
        );

        $totalInUse = 0;
        foreach ($conflicting as $booking) {
            foreach ($booking->bookingItems as $item) {
                if ($item->itemID == $itemID) {
                    $totalInUse += $item->quantity;
                }
            }
        }

        // Check if requested + in-use exceeds available
        return ($totalInUse + $requestedQty) <= $inventory->quantityAvailable;
    }

    /**
     * Find all confirmed/in-use bookings that overlap with the given date/time
     */
    public function findConflictingBookings($itemID, $eventDate, $timeStart, $timeEnd)
    {
        return Booking::whereIn('status', ['Confirmed', 'In-Use'])
            ->whereDate('eventDATE', $eventDate)
            ->with(['bookingItems' => function ($query) use ($itemID) {
                $query->where('itemID', $itemID);
            }])
            ->get()
            ->filter(function ($booking) {
                return $booking->bookingItems->count() > 0;
            });
    }

    /**
     * Get quantity available for a specific item on a specific date
     */
    public function getAvailableQuantity($itemID, $eventDate)
    {
        $inventory = Inventory::findOrFail($itemID);
        
        $conflicting = $this->findConflictingBookings($itemID, $eventDate, '00:00', '23:59');
        $inUse = 0;
        foreach ($conflicting as $booking) {
            foreach ($booking->bookingItems as $item) {
                if ($item->itemID == $itemID) {
                    $inUse += $item->quantity;
                }
            }
        }

        return $inventory->quantityAvailable - $inUse;
    }
}
