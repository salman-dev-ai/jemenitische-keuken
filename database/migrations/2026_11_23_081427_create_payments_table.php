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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('paypro');
            $table->string('provider_payment_id')->unique();   // رقم العملية لدى PayPro
            $table->unsignedInteger('amount');                  // بالسنت
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('pending')->index();
            $table->string('provider_state')->nullable();       // الحالة الخام من PayPro
            $table->string('pay_method')->nullable();
            $table->text('checkout_url')->nullable();
            $table->unsignedInteger('refunded_amount')->default(0);
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
