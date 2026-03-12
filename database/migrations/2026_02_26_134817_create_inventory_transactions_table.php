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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itemID');
            $table->enum('type', ['stock_in', 'stock_out', 'damage', 'restore']);
            $table->integer('quantity');
            $table->integer('available_before');
            $table->integer('available_after');
            $table->integer('damaged_before');
            $table->integer('damaged_after');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('itemID')->references('itemID')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
