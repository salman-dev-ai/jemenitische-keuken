<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: database/migrations/XXXX_add_tracking_ids_to_restaurant_settings_table.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    إضافة معرّفات أدوات التتبّع (تُدار من Filament) إلى جدول إعدادات المطعم:
 *    - google_analytics_id (GA4 Measurement ID)
 *    - meta_pixel_id (Meta Pixel ID)
 *    تُحمَّل هذه السكربتات في الواجهة فقط بعد موافقة المستخدم (opt-in).
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->string('google_analytics_id')->nullable()
                ->after('max_quantity_per_item')
                ->comment('معرّف Google Analytics 4 (مثال: G-XXXXXXXXXX)');

            $table->string('meta_pixel_id')->nullable()
                ->after('google_analytics_id')
                ->comment('معرّف Meta Pixel (مثال: 1234567890)');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn(['google_analytics_id', 'meta_pixel_id']);
        });
    }
};
