<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: Primary order records for takeaway/pickup or delivery.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Unique public reference (e.g. ORD-9921)

            // Customer Info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();

            // Order Type & Status
            $table->enum('type', ['pickup', 'dine_in'])->default('pickup');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

            // Financial Summary
            $table->decimal('subtotal', 8, 2);
            $table->decimal('tax', 8, 2)->default(0.00);
            $table->decimal('total', 8, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');
      
            $table->string('payment_method')->nullable(); // e.g., iDEAL, Cash, Card

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
