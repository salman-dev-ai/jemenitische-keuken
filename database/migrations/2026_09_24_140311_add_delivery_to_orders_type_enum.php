<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: database/migrations/XXXX_add_delivery_to_orders_type_enum.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    إضافة قيمة 'delivery' إلى ENUM العمود orders.type.
 *
 * 🧩 يعتمد على:
 *    - OrderType::DELIVERY->value = 'delivery'
 *
 * ⚠️ تحذيرات مهمة:
 *    - يستخدم SQL خام لتعديل ENUM (أكثر موثوقية من ->change()).
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE `orders` 
             MODIFY COLUMN `type` 
             ENUM('pickup', 'dine_in', 'preorder', 'delivery') 
             NOT NULL DEFAULT 'pickup'"
        );
    }

    public function down(): void
    {
        DB::statement(
            "ALTER TABLE `orders` 
             MODIFY COLUMN `type` 
             ENUM('pickup', 'dine_in', 'preorder') 
             NOT NULL DEFAULT 'pickup'"
        );
    }
};