<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: database/migrations/XXXX_create_cookie_consents_table.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    سجل الموافقات على الكوكيز (إثبات الموافقة — GDPR).
 *    صف واحد لكل زائر مجهول (uuid) يُحدَّث عند تغيير القرار.
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();

            // معرّف مجهول للزائر (UUID) يُخزَّن أيضاً في الكوكي لربط السجل.
            $table->uuid('uuid')->unique();

            // حالة كل تصنيف لحظة الموافقة.
            $table->boolean('necessary')->default(true);
            $table->boolean('analytics')->default(false);
            $table->boolean('marketing')->default(false);

            // نسخة السياسة التي وافق عليها المستخدم.
            $table->unsignedInteger('version')->default(1);

            // بصمة IP مجزأة (SHA-256) بدل حفظ العنوان الخام + وكيل المستخدم.
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 512)->nullable();

            $table->timestamps();

            $table->index(['analytics', 'marketing']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_consents');
    }
};
