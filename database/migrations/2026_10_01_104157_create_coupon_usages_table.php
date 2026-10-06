<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * هجرة إنشاء جدول coupon_usages — سجل استخدام الكوبونات لكل عميل.
 *
 * الحالات (status):
 * - claimed:   العميل حصل على الكوبون ولم يُستخدم بعد
 * - used:      تم استخدامه في طلب مؤكَّد
 * - expired:   انتهت صلاحيته وهو في حساب العميل
 * - cancelled: أُلغي الطلب فحُرِّر الكوبون
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();

            // ═══ العلاقات ═══
            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            // order_id nullable — الكوبون قد يُحفظ قبل الطلب
            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            // ═══ الحالة والتوقيت ═══
            $table->string('status', 20)->default('claimed');
            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();

            // ═══ Snapshot وقت الاستخدام ═══
            // نحفظ قيمة الخصم الفعلية (قد تتغير قواعد الكوبون لاحقًا)
            $table->decimal('discount_amount', 10, 2)->nullable();

            // ═══ فهارس ═══
            // للتحقق السريع: هل العميل استخدم الكوبون؟
            $table->index(
                ['coupon_id', 'customer_id', 'status'],
                'coupon_usages_customer_check_idx'
            );

            $table->index('status', 'coupon_usages_status_idx');
            $table->index('customer_id', 'coupon_usages_customer_idx');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};