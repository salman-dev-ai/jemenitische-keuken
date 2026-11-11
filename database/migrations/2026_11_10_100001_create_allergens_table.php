<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: قاموس موحّد لمعلومات الحساسية والعلامات الغذائية
     * (الاسم المترجم + الأيقونة/الصورة + الحالة) تُدار بالكامل من Filament.
     */
    public function up(): void
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();

            // مفتاح ثابت للبرمجة والترحيل (لا يظهر للعميل)
            $table->string('slug', 100)->unique();

            // الاسم المترجم (ar / en / nl)
            $table->json('name');

            // allergen = مسبب حساسية | label = علامة غذائية (حلال، نباتي...)
            $table->string('type', 20)->default('allergen');

            // اسم أيقونة Lucide (مثال: lucide-wheat) أو صورة مرفوعة كبديل
            $table->string('icon')->nullable();
            $table->string('image_path')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // فهرس مركّب لاستعلامات الواجهة الأمامية (المفعّل مرتّباً)
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergens');
    }
};
