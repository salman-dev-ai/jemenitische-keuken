<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * إضافة عمود video لجدول promotions.
 * - nullable: الفيديو اختياري
 * - يُستخدم لعرض مقطع ترويجي في صفحة العرض التفصيلية
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->string('video')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('video');
        });
    }
};