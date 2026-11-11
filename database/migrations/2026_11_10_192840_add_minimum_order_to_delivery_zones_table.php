<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('delivery_zones', function (Blueprint $table) {
            // الحد الأدنى للطلب لكل منطقة توصيل (القيمة الافتراضية 0 = لا يوجد حد أدنى).
            $table->decimal('minimum_order', 8, 2)->default(0)->after('delivery_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->dropColumn('minimum_order');
        });
    }
};
