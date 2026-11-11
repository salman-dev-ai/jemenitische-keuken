<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * إضافة حقول السيو (SEO) إلى جدول إعدادات المطعم.
 *
 * كل هذه الحقول قابلة للإدارة بالكامل من لوحة Filament، وتُقرأ في واجهة الموقع
 * لبناء وسوم الميتا، وبيانات المشاركة الاجتماعية، والبيانات المنظّمة (JSON-LD).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            // عنوان ووصف السيو — JSON ليكونا قابلين للترجمة (ar/en/nl)
            $table->json('meta_title')->nullable()->after('name');
            $table->json('meta_description')->nullable()->after('meta_title');

            // صورة المشاركة الاجتماعية (Open Graph / Twitter) — مسار نسبي داخل public
            $table->string('og_image')->nullable()->after('meta_description');

            // نطاق السعر المعروض في نتائج البحث (مثال: €€)
            $table->string('price_range', 8)->nullable()->after('og_image');

            // أنواع المطبخ (مثال: ["Jemenitisch","Midden-Oosters"]) — مصفوفة JSON
            $table->json('serves_cuisine')->nullable()->after('price_range');

            // روابط حسابات التواصل الاجتماعي (sameAs) — مصفوفة JSON
            $table->json('social_links')->nullable()->after('serves_cuisine');

            // الإحداثيات الجغرافية المستخدمة في البيانات المنظّمة والبحث المحلي
            $table->decimal('latitude', 10, 7)->nullable()->after('social_links');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

            // رمز تحقّق Google Search Console (يُعرض كوسم meta)
            $table->string('google_site_verification', 120)->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'og_image',
                'price_range',
                'serves_cuisine',
                'social_links',
                'latitude',
                'longitude',
                'google_site_verification',
            ]);
        });
    }
};
