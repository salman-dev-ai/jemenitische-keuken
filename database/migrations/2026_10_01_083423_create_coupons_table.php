<?php

declare(strict_types=1);

/**
 * هجرة إنشاء جدول coupons — يخزّن كوبونات الخصم مع قواعد الاستخدام.
 * يُنفَّذ مرة واحدة عند `php artisan migrate`، ويعتمد على App\Enums\DiscountType.
 * يستخدم string + check بدل enum() لضمان التوافق مع MySQL/PostgreSQL/SQLite.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ الهجرة — إنشاء الجدول مع الفهارس والقيود.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            // المفتاح الأساسي
            $table->id();

            // كود الكوبون — فريد وقابل للبحث
            $table->string('code', 50)->unique();

            // نوع الخصم — قيد check يمنع القيم غير الصالحة
            // نستخدم string بدل enum() لسهولة التعديل لاحقاً وتوافق أفضل
            $table->string('discount_type', 20)
                ->check("discount_type IN ('percentage', 'fixed')");

            // قيمة الخصم — 10 أرقام إجمالية، 2 عشرية (حتى 99,999,999.99)
            $table->decimal('discount_value', 10, 2);

            // تاريخ انتهاء الصلاحية — NULL يعني لا ينتهي
            $table->timestamp('expires_at')->nullable();

            // الحد الأدنى لقيمة الطلب لتفعيل الكوبون
            $table->decimal('min_order_total', 10, 2)->default(0);

            // الحد الأقصى لعدد مرات الاستخدام الكلي — NULL يعني غير محدود
            $table->unsignedInteger('max_uses')->nullable();

            // الحد الأقصى لعدد مرات الاستخدام لكل عميل
            $table->unsignedInteger('max_uses_per_customer')->default(1);

            // عدد مرات الاستخدام الحالي — unsigned لمنع القيم السالبة
            $table->unsignedInteger('used_count')->default(0);

            // حالة التفعيل — boolean مع فهرس للأداء
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
            $table->softDeletes();

            // فهرس مركّب للاستعلامات الشائعة:
            // WHERE is_active = 1 AND expires_at > NOW()
            $table->index(['is_active', 'expires_at'], 'coupons_active_expires_idx');

            // فهرس لتقارير الاستخدام: ORDER BY used_count DESC
            $table->index('used_count', 'coupons_used_count_idx');
        });
    }

    /**
     * التراجع عن الهجرة — حذف الجدول بالكامل.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};