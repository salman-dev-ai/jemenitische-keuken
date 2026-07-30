<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: Pivot details for individual products attached to a specific order.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->restrictOnDelete();

            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2); // Snapshot price at order time
            $table->decimal('total_price', 8, 2); // quantity * unit_price
            $table->json('options')->nullable(); // Special modifications e.g. "No onions"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
