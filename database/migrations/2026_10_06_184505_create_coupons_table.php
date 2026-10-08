<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة حقول الكوبون إلى جدول الطلبات.
     * 
     * نستخدم foreignId()->constrained() بدل unsignedBigInteger
     * نضع nullable() قبل constrained() لتجنب أخطاء المفتاح الأجنبي
     * نستخدم nullOnDelete() للحفاظ على الطلب عند حذف الكوبون
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // الترتيب الصحيح: nullable() ← constrained() ← nullOnDelete()
            $table->foreignId('coupon_id')
                ->nullable()
                ->after('notes')
                ->constrained('coupons')
                ->nullOnDelete();

            // تحديد طول للحقول النصية (أداء أفضل)
            $table->string('coupon_code', 50)
                ->nullable()
                ->after('coupon_id')
                ->index(); // index للاستعلامات المتكررة

            // discount_type: قيم محدودة (percentage / fixed)
            $table->string('discount_type', 20)
                ->nullable()
                ->after('coupon_code');

            // decimal للقيم المالية (لا float — لتجنب أخطاء التقريب)
            $table->decimal('discount_amount', 8, 2)
                ->nullable()
                ->default(0.00)
                ->after('discount_type');
        });
    }

    /**
     * التراجع عن الـ migration.
     * 
     * نستخدم dropConstrainedForeignId بدل dropForeign (أكثر أماناً)
     * نرتب الحذف: foreign key أولاً ثم الأعمدة
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // dropConstrainedForeignId يحذف FK + العمود معاً بأمان
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropIndex(['coupon_code']);
            $table->dropColumn(['coupon_code', 'discount_type', 'discount_amount']);
        });
    }
};