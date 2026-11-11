<?php

namespace Database\Seeders;

use App\Models\Allergen;
use Illuminate\Database\Seeder;

/**
 * Responsibility: زرع القاموس الافتراضي لمعلومات الحساسية والعلامات الغذائية.
 *
 * ℹ️ العمليات idempotent (لا تُكرّر الصفوف) ويمكن تشغيلها أكثر من مرة.
 * ⚠️ حالة التفعيل (is_active) لا تُفرض عند التحديث حتى لا يُلغي الزرع
 *    تعطيلاً يدوياً أجراه المسؤول.
 */
class AllergenSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Allergen::DEFAULT_CATALOG as $item) {
            $allergen = Allergen::firstOrNew(['slug' => $item['slug']]);

            $allergen->fill([
                'name' => $item['name'],
                'type' => $item['type'],
                'icon' => $item['icon'],
                'sort_order' => $item['sort_order'],
            ]);

            if (! $allergen->exists) {
                $allergen->is_active = true;
            }

            $allergen->save();
        }
    }
}
