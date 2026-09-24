<?php

/**
 *
 * 🎯 الغرض:
 *    إضافة قيد UNIQUE على عمود orders.order_number لمنع تكرار أرقام الطلبات
 *    على مستوى قاعدة البيانات (طبقة حماية ثانية بعد ULID في الكود).
 *
 * 🧩 يعتمد على:
 *    - جدول orders (موجود مسبقاً)
 *    - Laravel Schema Builder
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تنفيذ التغييرات عند تشغيل migration.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // قيد UNIQUE مع اسم مخصص لتفادي تعارض الأسماء التلقائية
            $table->unique('order_number', 'uniq_orders_order_number');
        });
    }

    /**
     * عكس التغييرات عند التراجع.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('uniq_orders_order_number');
        });
    }
}; 