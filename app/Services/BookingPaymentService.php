<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\InventoryTransaction;
use App\Models\PaymentSchedule;
use App\Events\BookingConfirmed;
use App\Events\BookingCancelled;
use Exception;

class BookingPaymentService
{
    /**
     * Process a payment and auto-transition booking if down payment received
     */
    public function processPayment(Booking $booking, $amount, $paymentMethod)
    {
        // If Pending status and down payment received, auto-confirm
        if ($booking->status === 'Pending') {
            $downPaymentRequired = $booking->down_payment_required;
            
            if ($amount >= $downPaymentRequired) {
                return $this->confirmBooking($booking);
            }
        }

        return $booking;
    }

    /**
     * Confirm booking and reserve inventory
     */
    public function confirmBooking(Booking $booking)
    {
        if ($booking->status !== 'Pending') {
            throw new Exception("Can only confirm Pending bookings. Current: {$booking->status}");
        }

        $booking->status = 'Confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        // Reserve inventory
        $this->reserveInventory($booking);

        event(new BookingConfirmed($booking));
        
        return $booking;
    }

    /**
     * Reserve inventory items for confirmed booking
     */
    public function reserveInventory(Booking $booking)
    {
        foreach ($booking->bookingItems as $bookingItem) {
            $inventory = $bookingItem->item;

            $prev_available = $inventory->quantityAvailable;
            $prev_reserved = $inventory->quantity_reserved;

            // Move from Available to Reserved
            $inventory->quantityAvailable -= $bookingItem->quantity;
            $inventory->quantity_reserved += $bookingItem->quantity;
            $inventory->save();

            // Log transaction
            InventoryTransaction::create([
                'itemID' => $inventory->itemID,
                'bookingID' => $booking->bookingID,
                'type' => 'stock_out',
                'transaction_reason' => 'booking_reserve',
                'quantity' => $bookingItem->quantity,
                'available_before' => $prev_available,
                'available_after' => $inventory->quantityAvailable,
                'damaged_before' => $inventory->quantityDamaged,
                'damaged_after' => $inventory->quantityDamaged,
                'notes' => "Reserved {$bookingItem->quantity}x {$inventory->itemName} for booking #{$booking->bookingID}"
            ]);

            // Update booking item reservation status
            $bookingItem->update(['reservation_status' => 'reserved']);
        }
    }

    /**
     * Cancel booking with penalty and release inventory
     */
    public function cancelBooking(Booking $booking, $reason = null)
    {
        // Check if can be cancelled
        if (!in_array($booking->status, ['Pending', 'Confirmed', 'In-Use'])) {
            throw new Exception("Cannot cancel booking with status: {$booking->status}");
        }

        $originalStatus = $booking->status;

        // Calculate cancellation penalty (20% of down payment)
        $penalty = $booking->down_payment_required * 0.20;
        $booking->cancellation_penalty = $penalty;

        // If booking was Confirmed or In-Use, release reserved inventory BEFORE changing status
        if (in_array($originalStatus, ['Confirmed', 'In-Use'])) {
            $this->releaseInventory($booking);
        }

        $booking->status = 'Cancelled';
        $booking->cancelled_at = now();

        // Create penalty payment schedule
        PaymentSchedule::create([
            'bookingID' => $booking->bookingID,
            'payment_type' => 'cancellation_penalty',
            'amount_required' => $penalty,
            'status' => 'pending',
            'notes' => "Cancellation penalty: 20% of down payment"
        ]);

        $booking->save();
        
        event(new BookingCancelled($booking));
    }

    /**
     * Release reserved inventory items
     */
    public function releaseInventory(Booking $booking)
    {
        foreach ($booking->bookingItems as $bookingItem) {
            if ($bookingItem->reservation_status !== 'reserved') {
                continue;
            }

            $inventory = $bookingItem->item;

            // Move from reserved back to available
            $prev_available = $inventory->quantityAvailable;
            $inventory->quantity_reserved -= $bookingItem->quantity;
            $inventory->quantityAvailable += $bookingItem->quantity;
            $inventory->save();

            // Log transaction
            InventoryTransaction::create([
                'itemID' => $inventory->itemID,
                'bookingID' => $booking->bookingID,
                'type' => 'stock_in',
                'transaction_reason' => 'booking_cancel',
                'quantity' => $bookingItem->quantity,
                'available_before' => $prev_available,
                'available_after' => $inventory->quantityAvailable,
                'damaged_before' => $inventory->quantityDamaged,
                'damaged_after' => $inventory->quantityDamaged,
                'notes' => "Released {$bookingItem->quantity}x {$inventory->itemName} - booking cancelled"
            ]);

            $bookingItem->update(['reservation_status' => 'released']);
        }
    }
}
