<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إنشاء جدول الشرائح الإعلانية.
     * الهجرة القديمة 2026_09_20_202644 كانت تعمل ALTER فقط دون إنشاء الجدول،
     * لذا فقد كان الجدول مفقوداً وتسبب بانهيار القائمة الجانبية في لوحة التحكم.
     */
    public function up(): void
    {
        // نتجاوز الإنشاء إن كان الجدول موجوداً بالفعل حتى لا تفشل البيئات القائمة.
        if (Schema::hasTable('sliders')) {
            return;
        }

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->json('eyebrow')->nullable();
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
