<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرات.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // إضافة حقول العنوان للطلب بعد customer_phone
            $table->string('delivery_address')->nullable()->after('customer_phone');
            $table->string('delivery_city')->nullable()->after('delivery_address');
            $table->string('delivery_postal_code', 20)->nullable()->after('delivery_city');
        });
    }

    /**
     * عكس الهجرات.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // حذف حقول العنوان عند عكس الهجرة
            $table->dropColumn(['delivery_address', 'delivery_city', 'delivery_postal_code']);
        });
    }
};