<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * إضافة:
 * - name/description (JSON) — مترجمة عبر spatie/laravel-translatable
 * - applies_to — نطاق الكوبون (all / products / categories)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // ═══ النصوص المترجمة ═══
            $table->json('name')->after('code');
            $table->json('description')->nullable()->after('name');

            // ═══ نطاق الكوبون ═══
            $table->string('applies_to', 20)
                ->default('all')
                ->after('discount_value')
                ->check("applies_to IN ('all', 'products', 'categories')");

            $table->index('applies_to', 'coupons_applies_to_idx');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex('coupons_applies_to_idx');
            $table->dropColumn(['name', 'description', 'applies_to']);
        });
    }
};