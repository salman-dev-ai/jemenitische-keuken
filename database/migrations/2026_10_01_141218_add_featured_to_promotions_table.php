<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * إضافة:
 * - is_featured:     لتمييز بطاقة واحدة كـ "البطاقة الكبرى" (50% مثلاً)
 * - video_duration:  مدة الفيديو بالثواني (00:18 = 18)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->unsignedInteger('video_duration')->nullable()->after('video');

            $table->index('is_featured', 'promotions_is_featured_idx');
        });
    }

    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropIndex('promotions_is_featured_idx');
            $table->dropColumn(['is_featured', 'video_duration']);
        });
    }
};