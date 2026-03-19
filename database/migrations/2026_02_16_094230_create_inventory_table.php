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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('itemID');
            $table->string('itemName', 100);
            $table->enum('category', ['Tables', 'Dining Wares', 'Catering Equipment', 'Entertainment']);
            $table->integer('quantityAvailable')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('quantity_in_use')->default(0);
            $table->decimal('rentalPrice', 10, 2);
            $table->enum('status', ['Available', 'Damaged', 'Unavailable'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
