<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرة لإنشاء جدول معرض الصور الملكي المتكامل
     */
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();

            // الفئة التراثية (مندي ومظبي، فخاريات صنعانية، ديوان يماني، ضيافة وبخور)
            $table->string('category')->default('mandi')->index();
            // enum / slugs: ['mandi', 'pots', 'majlis', 'coffee', 'bread']

            // العناوين والوصف والشارات بدعم 3 لغات (AR / EN / NL) كـ JSON
            $table->json('title');
            // مثال: {"ar": "وليمة مندي اللحم الملكي", "en": "Royal Lamb Mandi Feast", "nl": "Koninklijke Lams Mandi"}

            $table->json('description')->nullable();
            // مثال: {"ar": "لحم مطهو في برميل الحطب التقليدي...", "en": "Slow wood-pit cooked lamb...", "nl": "Langzaam gegaard..."}

            $table->json('badge')->nullable();
            // مثال: {"ar": "سيد المائدة 👑", "en": "Royal Master 👑", "nl": "Koninklijk 👑"}

            // روابط ومسارات الصور
            $table->string('image_path'); // مسار الصورة الأصلية عالية الدقة
            $table->string('thumbnail_path')->nullable(); // مسار الصورة المصغرة للتحميل السريع
            $table->json('alt_text')->nullable(); // نصوص الـ SEO البديلة باللغات الثلاث

            // خصائص التحكم من لوحة الإدارة (Admin Controls)
            $table->integer('sort_order')->default(0)->index(); // ترتيب العرض
            $table->boolean('is_featured')->default(false)->index(); // مميز في الصفحة الرئيسية
            $table->boolean('is_active')->default(true)->index(); // مفعّل / معطّل
            $table->unsignedBigInteger('views_count')->default(0); // عدد المشاهدات والنقرات

            // التواريخ والحذف الناعم
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
