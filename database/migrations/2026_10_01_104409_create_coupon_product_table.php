<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * pivot: ربط الكوبون بمنتجات محددة (عند applies_to = 'products').
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            $table->foreignId('menu_item_id')   // ← عدّل الاسم إن كان product_id
                ->constrained('menu_items')
                ->cascadeOnDelete();

            // منع التكرار
            $table->unique(['coupon_id', 'menu_item_id'], 'coupon_product_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_product');
    }
};