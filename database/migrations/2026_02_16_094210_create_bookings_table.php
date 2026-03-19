<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('bookingID');
            $table->unsignedBigInteger('customerID');
            $table->date('eventDATE');
            $table->string('eventLocation', 150);
            $table->time('timeStart');
            $table->time('timeEND');
            $table->enum('status', ['Pending', 'Confirmed', 'In-Use', 'Pending-Return', 'Completed', 'Cancelled'])->default('Pending');
            $table->decimal('totalAmount', 10, 2)->default(0);
            $table->decimal('down_payment_required', 10, 2)->nullable();
            $table->decimal('down_payment_paid', 10, 2)->default(0);
            $table->string('down_payment_method')->nullable();
            $table->datetime('rental_start_date')->nullable();
            $table->datetime('rental_end_date')->nullable();
            $table->datetime('actual_return_date')->nullable();
            $table->decimal('cancellation_penalty', 10, 2)->default(0);
            $table->datetime('confirmed_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->timestamps();
            $table->foreign('customerID')->references('customerID')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
