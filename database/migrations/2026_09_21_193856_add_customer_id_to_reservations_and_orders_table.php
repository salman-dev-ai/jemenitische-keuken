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
        Schema::table('reservations', function (Blueprint $table) {
            // إضافة المفتاح الخارجي customer_id لجدول reservations
            // بعد عمود 'id' وقابل للقيمة الفارغة، مع حذف متتابع بقيمة null
            $table->foreignId('customer_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('customers')
                  ->onDelete('set null');
        });

        Schema::table('orders', function (Blueprint $table) {
            // إضافة المفتاح الخارجي customer_id لجدول orders
            // بعد عمود 'id' وقابل للقيمة الفارغة، مع حذف متتابع بقيمة null
            $table->foreignId('customer_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('customers')
                  ->onDelete('set null');
        });
    }

    /**
     * عكس الهجرات.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // حذف المفتاح الخارجي ثم العمود من جدول reservations
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            // حذف المفتاح الخارجي ثم العمود من جدول orders
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};