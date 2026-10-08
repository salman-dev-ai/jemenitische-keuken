<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة عمود is_welcome إلى جدول coupons .
     *
     * ✅ boolean بدون nullable — منطق ثنائي واضح (true/false)
     * ✅ default(false) — القيمة الافتراضية لكوبون عادي
     * ✅ ->after() يجب أن يشير لعمود موجود فعلاً (تحقق من create_coupons_table)
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            $table->boolean('is_welcome')
                ->default(false)
                ->after('is_active'); // ⚠️ عدّل according to عمود موجود فعلاً
        });
    }

    /**
     * التراجع عن الـ migration.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table): void {
            $table->dropColumn('is_welcome');
        });
    }
};