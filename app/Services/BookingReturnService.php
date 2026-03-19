<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\InventoryTransaction;
use App\Models\PaymentSchedule;
use App\Events\BookingReturned;
use App\Events\BookingCompleted;
use Exception;

class BookingReturnService
{
    /**
     * Process return of items with damage assessment
     * 
     * $returnedItems format:
     * [
     *     ['booking_item_id' => 1, 'quantity' => 5, 'condition' => 'good'],
     *     ['booking_item_id' => 2, 'quantity' => 3, 'condition' => 'damaged'],
     * ]
     */
    public function processReturn(Booking $booking, array $returnedItems, $notes = null)
    {
        if ($booking->status !== 'In-Use') {
            throw new Exception("Can only process returns for In-Use bookings. Current status: {$booking->status}");
        }

        $booking->status = 'Pending-Return';
        $booking->actual_return_date = now();

        // Process each returned item with damage assessment
        foreach ($returnedItems as $returnItem) {
            $bookingItem = $booking->bookingItems()
                ->find($returnItem['booking_item_id']);

            if (!$bookingItem) {
                continue;
            }

            $inventory = $bookingItem->item;
            $condition = $returnItem['condition']; // 'good', 'damaged', 'partial_damage'

            $prev_in_use = $inventory->quantity_in_use;
            $prev_available = $inventory->quantityAvailable;
            $prev_damaged = $inventory->quantityDamaged;

            if ($condition === 'good') {
                // Items go back to Available
                $inventory->quantity_in_use -= $returnItem['quantity'];
                $inventory->quantityAvailable += $returnItem['quantity'];
            } else {
                // Items marked as Damaged
                $inventory->quantity_in_use -= $returnItem['quantity'];
                $inventory->quantityDamaged += $returnItem['quantity'];

                // Create damage charge
                $this->createDamageCharge($booking, $bookingItem, $returnItem['quantity'], $condition);
            }

            $inventory->save();

            // Log transaction
            InventoryTransaction::create([
                'itemID' => $inventory->itemID,
                'bookingID' => $booking->bookingID,
                'type' => 'stock_in',
                'transaction_reason' => 'booking_return',
                'quantity' => $returnItem['quantity'],
                'available_before' => $prev_available,
                'available_after' => $inventory->quantityAvailable,
                'damaged_before' => $prev_damaged,
                'damaged_after' => $inventory->quantityDamaged,
                'notes' => "Items returned - Condition: {$condition}. {$notes}"
            ]);

            $bookingItem->update([
                'reservation_status' => 'returned',
                'returned_at' => now(),
                'return_condition' => $condition
            ]);
        }

        // Check if all items returned
        $all_returned = $booking->bookingItems()
            ->where('reservation_status', '!=', 'returned')
            ->count() === 0;

        if ($all_returned) {
            // Only complete if all payments are collected
            $this->completeBookingIfPaid($booking);
        }

        $booking->save();
        
        event(new BookingReturned($booking));
        
        return $booking;
    }

    /**
     * Check if all required payments are completed, then mark booking as Completed
     */
    private function completeBookingIfPaid(Booking $booking)
    {
        // Get all payment schedules for this booking
        $paymentSchedules = $booking->paymentSchedules()->get();

        // Check if all payments (except damage charges) are complete
        $allPaid = true;
        foreach ($paymentSchedules as $schedule) {
            // Skip damage charges - they might be pending and that's ok for now
            if ($schedule->payment_type === 'damage_charge') {
                continue;
            }

            // Check if this payment is complete
            if ($schedule->status !== 'completed') {
                $allPaid = false;
                break;
            }
        }

        // Only complete booking if all regular payments are done
        if ($allPaid) {
            $booking->status = 'Completed';
            $booking->completed_at = now();
            $booking->save();
            event(new BookingCompleted($booking));
        }
        // If payments incomplete, keep as Pending-Return (admin will after all payments received)
    }

    /**
     * Complete booking after pending-return - called when damage payments received
     * or manually by admin
     */
    public function completeBookingManually(Booking $booking)
    {
        if ($booking->status !== 'Pending-Return' && $booking->status !== 'Completed') {
            throw new Exception("Can only complete Pending-Return or Completed bookings. Current: {$booking->status}");
        }

        // Check all payments are done
        $incompletePay = $booking->paymentSchedules()
            ->where('status', '!=', 'completed')
            ->first();

        if ($incompletePay) {
            throw new Exception("Cannot complete: {$incompletePay->payment_type} payment still pending (₱{$incompletePay->amount_required})");
        }

        $booking->status = 'Completed';
        $booking->completed_at = now();
        $booking->save();

        event(new BookingCompleted($booking));
        return $booking;
    }

    /**
     * Create damage charge for returned items
     */
    private function createDamageCharge(Booking $booking, $bookingItem, $quantity, $condition)
    {
        $inventory = $bookingItem->item;

        // Calculate damage charge (50% of rental price per item)
        $damageRate = 0.50;
        $damageCharge = ($inventory->rentalPrice * $quantity) * $damageRate;

        PaymentSchedule::create([
            'bookingID' => $booking->bookingID,
            'payment_type' => 'damage_charge',
            'amount_required' => $damageCharge,
            'status' => 'pending',
            'notes' => "Damage charge for {$quantity}x {$inventory->itemName} ({$condition})"
        ]);
    }
}
