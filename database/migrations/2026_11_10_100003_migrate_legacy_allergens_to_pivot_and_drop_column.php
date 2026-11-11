<?php

use App\Enums\AllergenType;
use Database\Seeders\AllergenSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 🎯 ترحيل البيانات القديمة ثم إزالة العمود:
     * 1) زرع القاموس الافتراضي (AllergenSeeder) ليطابق معظم الأسماء القديمة.
     * 2) قراءة menu_items.allergens (نص حر مترجم) وربط كل طبق بالعناصر المناسبة
     *    في الجدول الوسيط — وإنشاء عنصر جديد لأي نص قديم غير معروف في القاموس.
     * 3) حذف العمود القديم حتى يبقى مصدر حقيقة واحد (بدون تكرار).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('menu_items', 'allergens')) {
            return;
        }

        (new AllergenSeeder)->run();

        $lookup = $this->buildLookup();

        DB::table('menu_items')
            ->select('id', 'allergens')
            ->whereNotNull('allergens')
            ->orderBy('id')
            ->chunkById(200, function ($menuItems) use (&$lookup): void {
                foreach ($menuItems as $menuItem) {
                    $allergenIds = [];

                    foreach ($this->extractLabels($menuItem->allergens) as $label) {
                        $allergenIds[] = $this->resolveAllergenId($label, $lookup);
                    }

                    $allergenIds = array_values(array_unique(array_filter($allergenIds)));

                    if ($allergenIds === []) {
                        continue;
                    }

                    $now = now();

                    $rows = array_map(fn (int $allergenId): array => [
                        'allergen_id' => $allergenId,
                        'menu_item_id' => $menuItem->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ], $allergenIds);

                    DB::table('allergen_menu_item')->insertOrIgnore($rows);
                }
            });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropColumn('allergens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * ⚠️ الرجوع تقريبي: يُعيد العمود النصي مع الأسماء الإنجليزية الحالية
     *    (الوسيط لا يحفظ اللغة، لذا لا يمكن استرجاع الترجمة الأصلية حرفياً).
     */
    public function down(): void
    {
        if (Schema::hasColumn('menu_items', 'allergens')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->json('allergens')->nullable()->after('description');
        });

        $rows = DB::table('allergen_menu_item')
            ->join('allergens', 'allergens.id', '=', 'allergen_menu_item.allergen_id')
            ->select('allergen_menu_item.menu_item_id', 'allergens.name')
            ->orderBy('allergen_menu_item.id')
            ->get();

        $grouped = [];

        foreach ($rows as $row) {
            $names = (array) json_decode((string) $row->name, true);
            $label = $names['en'] ?? reset($names);

            if (is_string($label) && $label !== '') {
                $grouped[$row->menu_item_id]['en'][] = $label;
            }
        }

        foreach ($grouped as $menuItemId => $payload) {
            DB::table('menu_items')
                ->where('id', $menuItemId)
                ->update(['allergens' => json_encode($payload, JSON_UNESCAPED_UNICODE)]);
        }
    }

    /**
     * يقبل الشكلين الممكنين للعمود القديم:
     *  - ["Gluten", "Nuts"]                          (قائمة أسماء)
     *  - {"ar": ["Gluten"], "en": ["Gluten","Nuts"]} (خريطة لغات)
     *
     * @return array<int, string>
     */
    private function extractLabels(?string $raw): array
    {
        $decoded = json_decode((string) $raw, true);

        if (! is_array($decoded)) {
            return [];
        }

        $labels = [];

        foreach ($decoded as $value) {
            $values = is_array($value) ? $value : [$value];

            foreach ($values as $candidate) {
                if (is_string($candidate) && trim($candidate) !== '') {
                    $labels[] = trim($candidate);
                }
            }
        }

        return array_values(array_unique($labels));
    }

    /**
     * خريطة بحث: (الاسم بأي لغة أو الـ slug بحروف صغيرة) => معرّف العنصر.
     *
     * @return array<string, int>
     */
    private function buildLookup(): array
    {
        $lookup = [];

        foreach (DB::table('allergens')->select('id', 'slug', 'name')->get() as $row) {
            $id = (int) $row->id;
            $lookup[Str::lower($row->slug)] = $id;

            foreach ((array) json_decode((string) $row->name, true) as $translation) {
                if (is_string($translation) && trim($translation) !== '') {
                    $lookup[Str::lower(trim($translation))] = $id;
                }
            }
        }

        return $lookup;
    }

    /**
     * إيجاد معرّف العنصر لاسم قديم، وإنشائه إن لم يوجد في القاموس
     * (نص حر قديم — يُحفظ كما هو ليترجمه المسؤول لاحقاً من لوحة التحكم).
     *
     * @param  array<string, int>  $lookup
     */
    private function resolveAllergenId(string $label, array &$lookup): ?int
    {
        $key = Str::lower($label);

        if (isset($lookup[$key])) {
            return $lookup[$key];
        }

        $slug = Str::slug($label);

        if ($slug === '') {
            return null;
        }

        // ضمان تفرّد الرابط الثابت بدون الاصطدام بعنصر قائم
        $candidate = $slug;
        $suffix = 2;

        while (DB::table('allergens')->where('slug', $candidate)->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        $id = (int) DB::table('allergens')->insertGetId([
            'slug' => $candidate,
            'name' => json_encode(['ar' => $label, 'en' => $label, 'nl' => $label], JSON_UNESCAPED_UNICODE),
            'type' => AllergenType::Allergen->value,
            'icon' => null,
            'image_path' => null,
            'is_active' => true,
            'sort_order' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lookup[$key] = $id;

        return $id;
    }
};
