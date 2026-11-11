<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //   هل يقدّم الطبق مشروباً مجانياً؟
            $table->boolean('offers_free_drink')->default(false)->after('is_spicy');
            // 🔢 العدد المجاني الأساسي لكل وحدة أو لكل سطر (حسب السياسة)
            $table->unsignedInteger('free_drink_qty')->default(1)->after('offers_free_drink');
            // 📐 سياسة العدد: per_unit (يتضاعف مع الكمية) أو per_line (ثابت للسطر)
            $table->string('free_drink_policy', 20)->default('per_unit')->after('free_drink_qty');
            // 🆔 أصناف المشروبات المسموحة لهذا الطبق (JSON بأسلوب بسيط بلا جدول ربط)
            $table->json('free_drink_item_ids')->nullable()->after('free_drink_policy');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn([
                'offers_free_drink',
                'free_drink_qty',
                'free_drink_policy',
                'free_drink_item_ids',
            ]);
        });
    }
};
