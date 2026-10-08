<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * إضافة حقول الكوبون (snapshot) إلى جدول الطلبات.
 *
 * الهدف:
 *   - حفظ معلومات الكوبون المطبَّق وقت إنشاء الطلب،
 *     حتى لو تغيّر الكوبون أو حُذف لاحقاً.
 *
 * القواعد:
 *   - coupon_id: nullable (الطلبات بدون كوبون)
 *   - nullOnDelete: إذا حُذف الكوبون، يبقى الطلب مع snapshot
 *   - coupon_code: محفوظ كنص للبحث السريع
 *   - discount_type: قيمة Enum كنص (20 حرفاً)
 *   - discount_amount: decimal(8,2) — دقة مالية، لا float
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // ✅ nullable() قبل constrained() — إلزامي
            $table->foreignId('coupon_id')
                ->nullable()
                ->after('notes')
                ->constrained('coupons')
                ->nullOnDelete();

            // ✅ snapshot كود الكوبون (مفهرس للبحث)
            $table->string('coupon_code', 50)
                ->nullable()
                ->after('coupon_id')
                ->index();

            // ✅ نوع الخصم (percentage / fixed)
            $table->string('discount_type', 20)
                ->nullable()
                ->after('coupon_code');

            // ✅ القيمة المالية (decimal لا float)
            $table->decimal('discount_amount', 8, 2)
                ->nullable()
                ->default(0.00)
                ->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // ✅ dropConstrainedForeignId يحذف FK + العمود معاً
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropIndex(['coupon_code']);
            $table->dropColumn(['coupon_code', 'discount_type', 'discount_amount']);
        });
    }
};
