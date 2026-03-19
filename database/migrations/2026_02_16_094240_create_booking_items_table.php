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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id('bookingItemID');
            $table->unsignedBigInteger('bookingID');
            $table->unsignedBigInteger('itemID');
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->string('reservation_status')->default('pending');
            $table->datetime('returned_at')->nullable();
            $table->string('return_condition')->nullable();
            $table->timestamps();

            $table->foreign('bookingID')->references('bookingID')->on('bookings')->onDelete('cascade');
            $table->foreign('itemID')->references('itemID')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
