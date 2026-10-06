<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * هجرة إنشاء جدول promotions — بطاقات العروض في قسم التخفيضات.
 *
 * العلاقة: One-to-One مع Coupon (كل عرض له كوبون واحد فقط).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // ═══ العلاقة مع الكوبون (One-to-One) ═══
            // UNIQUE يضمن أن كل كوبون له عرض واحد فقط
            $table->foreignId('coupon_id')
                ->unique()
                ->constrained('coupons')
                ->cascadeOnDelete();

            // ═══ الصورة ═══
            $table->string('image')->nullable();

            // ═══ النصوص المترجمة (ar/en/nl) ═══
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->json('cta_text')->nullable();

            // ═══ فترة ظهور العرض ═══
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // ═══ الترتيب والحالة ═══
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // ═══ فهارس ═══
            $table->index(
                ['is_active', 'starts_at', 'ends_at'],
                'promotions_active_period_idx'
            );
            $table->index('sort_order', 'promotions_sort_order_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

 