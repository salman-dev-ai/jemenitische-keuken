<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * pivot: ربط الكوبون بتصنيفات محددة (عند applies_to = 'categories').
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_category', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            $table->foreignId('menu_category_id')  // ← حسب اسم جدول التصنيفات
                ->constrained('menu_categories')
                ->cascadeOnDelete();

            $table->unique(['coupon_id', 'menu_category_id'], 'coupon_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_category');
    }
};