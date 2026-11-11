<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول sliders يُدار خارج هذه الحزمة؛ نتجاوز التعديل إن لم يكن موجوداً
        // حتى لا تفشل بيئات الاختبار (SQLite) التي تبني القاعدة من الصفر.
        if (! Schema::hasTable('sliders')) {
            return;
        }

        Schema::table('sliders', function (Blueprint $table) {
            $table->json('eyebrow')->nullable()->change();
            $table->json('title')->nullable()->change();
            $table->json('subtitle')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sliders')) {
            return;
        }

        Schema::table('sliders', function (Blueprint $table) {
            $table->json('eyebrow')->nullable(false)->change();
            $table->json('title')->nullable(false)->change();
            $table->json('subtitle')->nullable(false)->change();
        });
    }
};
