<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: database/migrations/XXXX_add_order_settings_to_restaurant_settings_table.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    إضافة إعدادات الطلب إلى جدول restaurant_settings (singleton):
 *    - vat_rate (نسبة BTW كنسبة مئوية، مثال: 9.00)
 *    - max_quantity_per_item (حد الكمية لكل طبق)
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->decimal('vat_rate', 5, 2)
                ->default(9.00)
                ->after('accepts_online_orders')
                ->comment('نسبة BTW (مثال: 9.00 = 9%)');

            $table->unsignedSmallInteger('max_quantity_per_item')
                ->default(50)
                ->after('vat_rate')
                ->comment('الحد الأقصى للكمية لكل طبق');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn(['vat_rate', 'max_quantity_per_item']);
        });
    }
};