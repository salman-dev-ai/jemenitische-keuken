<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: database/migrations/XXXX_change_address_to_json_in_restaurant_settings_table.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    تغيير عمود address من VARCHAR إلى JSON لدعم الترجمة (Spatie).
 *
 * 🧩 يعتمد على:
 *    - بيانات موجودة بصيغة JSON string: {"ar":"...","en":"..."}
 *
 * ⚠️ تحذيرات مهمة:
 *    - MySQL JSON column لا يقبل VARCHAR بالطريقة التقليدية — نستخدم TEXT + cast.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */
return new class extends Migration
{
    public function up(): void
    {
        // تعديل نوع العمود بصيغة MySQL خام؛ نتجاوز في بيئات الاختبار (SQLite).
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // 1️⃣ حوّل VARCHAR إلى TEXT (JSON string موجود بالفعل)
        // ─────────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `restaurant_settings` MODIFY COLUMN `address` TEXT NULL');
    }

    public function down(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE `restaurant_settings` MODIFY COLUMN `address` VARCHAR(255) NULL');
    }
};
