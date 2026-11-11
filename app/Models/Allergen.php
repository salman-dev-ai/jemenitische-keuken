<?php

namespace App\Models;

use App\Enums\AllergenType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * قاموس "معلومات الحساسية والعلامات الغذائية" (Allergie-informatie).
 *
 * 🎯 مصدر الحقيقة الوحيد للأسماء والأيقونات — تُدار بالكامل من لوحة Filament
 *    وتُربط بالأطباق عبر علاقة متعدد-لمتعدد (allergen_menu_item)،
 *    فلا يوجد أي نص حر مكرر داخل جدول الأطباق.
 */
class Allergen extends Model
{
    use HasFactory, HasTranslations;

    /**
     * القاموس الافتراضي الموحّد (EU-14 + علامات غذائية).
     * يُستخدم في Seeder وعند ترحيل الحقل النصي القديم، فلا تتكرر القائمة في مكانين.
     *
     * @var array<int, array<string, mixed>>
     */
    public const DEFAULT_CATALOG = [
        // ── مسببات الحساسية (EU-14) ─────────────────────────────
        [
            'slug' => 'gluten',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-wheat',
            'sort_order' => 1,
            'name' => ['ar' => 'جلوتين', 'en' => 'Gluten', 'nl' => 'Gluten'],
        ],
        [
            'slug' => 'crustaceans',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-shrimp',
            'sort_order' => 2,
            'name' => ['ar' => 'القشريات', 'en' => 'Crustaceans', 'nl' => 'Schaaldieren'],
        ],
        [
            'slug' => 'eggs',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-egg',
            'sort_order' => 3,
            'name' => ['ar' => 'البيض', 'en' => 'Eggs', 'nl' => 'Eieren'],
        ],
        [
            'slug' => 'fish',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-fish',
            'sort_order' => 4,
            'name' => ['ar' => 'الأسماك', 'en' => 'Fish', 'nl' => 'Vis'],
        ],
        [
            'slug' => 'peanuts',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-nut',
            'sort_order' => 5,
            'name' => ['ar' => 'الفول السوداني', 'en' => 'Peanuts', 'nl' => "Pinda's"],
        ],
        [
            'slug' => 'soybeans',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-bean',
            'sort_order' => 6,
            'name' => ['ar' => 'الصويا', 'en' => 'Soybeans', 'nl' => 'Soja'],
        ],
        [
            'slug' => 'milk',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-milk',
            'sort_order' => 7,
            'name' => ['ar' => 'الحليب', 'en' => 'Milk', 'nl' => 'Melk'],
        ],
        [
            'slug' => 'nuts',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-nut',
            'sort_order' => 8,
            'name' => ['ar' => 'المكسرات', 'en' => 'Nuts', 'nl' => 'Noten'],
        ],
        [
            'slug' => 'celery',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-leaf',
            'sort_order' => 9,
            'name' => ['ar' => 'الكرفس', 'en' => 'Celery', 'nl' => 'Selderij'],
        ],
        [
            'slug' => 'mustard',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-flask-round',
            'sort_order' => 10,
            'name' => ['ar' => 'الخردل', 'en' => 'Mustard', 'nl' => 'Mosterd'],
        ],
        [
            'slug' => 'sesame',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-circle-dot',
            'sort_order' => 11,
            'name' => ['ar' => 'السمسم', 'en' => 'Sesame', 'nl' => 'Sesam'],
        ],
        [
            'slug' => 'sulphites',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-wine',
            'sort_order' => 12,
            'name' => ['ar' => 'الكبريتيت', 'en' => 'Sulphites', 'nl' => 'Sulfiet'],
        ],
        [
            'slug' => 'lupin',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-flower',
            'sort_order' => 13,
            'name' => ['ar' => 'الترمس', 'en' => 'Lupin', 'nl' => 'Lupine'],
        ],
        [
            'slug' => 'molluscs',
            'type' => AllergenType::Allergen,
            'icon' => 'lucide-shell',
            'sort_order' => 14,
            'name' => ['ar' => 'الرخويات', 'en' => 'Molluscs', 'nl' => 'Weekdieren'],
        ],

        // ── علامات غذائية ──────────────────────────────────────
        [
            'slug' => 'halal',
            'type' => AllergenType::Label,
            'icon' => 'lucide-badge-check',
            'sort_order' => 100,
            'name' => ['ar' => 'حلال', 'en' => 'Halal', 'nl' => 'Halal'],
        ],
        [
            'slug' => 'vegan',
            'type' => AllergenType::Label,
            'icon' => 'lucide-vegan',
            'sort_order' => 101,
            'name' => ['ar' => 'نباتي صرف', 'en' => 'Vegan', 'nl' => 'Veganistisch'],
        ],
        [
            'slug' => 'vegetarian',
            'type' => AllergenType::Label,
            'icon' => 'lucide-salad',
            'sort_order' => 102,
            'name' => ['ar' => 'نباتي', 'en' => 'Vegetarian', 'nl' => 'Vegetarisch'],
        ],
        [
            'slug' => 'lactose-free',
            'type' => AllergenType::Label,
            'icon' => 'lucide-milk-off',
            'sort_order' => 103,
            'name' => ['ar' => 'خالٍ من اللاكتوز', 'en' => 'Lactose-free', 'nl' => 'Lactosevrij'],
        ],
        [
            'slug' => 'gluten-free',
            'type' => AllergenType::Label,
            'icon' => 'lucide-wheat-off',
            'sort_order' => 104,
            'name' => ['ar' => 'خالٍ من الجلوتين', 'en' => 'Gluten-free', 'nl' => 'Glutenvrij'],
        ],
        [
            'slug' => 'nut-free',
            'type' => AllergenType::Label,
            'icon' => 'lucide-nut-off',
            'sort_order' => 105,
            'name' => ['ar' => 'خالٍ من المكسرات', 'en' => 'Nut-free', 'nl' => 'Notenvrij'],
        ],
        [
            'slug' => 'sugar-free',
            'type' => AllergenType::Label,
            'icon' => 'lucide-candy',
            'sort_order' => 106,
            'name' => ['ar' => 'بدون سكر', 'en' => 'Sugar-free', 'nl' => 'Suikervrij'],
        ],
    ];

    public array $translatable = [
        'name',
    ];

    protected $fillable = [
        'slug',
        'name',
        'type',
        'icon',
        'image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => AllergenType::class,
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * الأطباق المرتبطة بهذا المسبب/العلامة (العلاقة الوسيطة).
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'allergen_menu_item')->withTimestamps();
    }

    /**
     * نطاق العناصر المفعّلة فقط (يُستخدم في الواجهة الأمامية).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * ترتيب العرض الموحّد (الترتيب ثم الرابط الثابت لضمان ثبات النتيجة).
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('slug');
    }

    /**
     * هل هذا العنصر علامة غذائية (Halal / Vegan) بدلاً من مسبب حساسية؟
     */
    public function isDietaryLabel(): bool
    {
        return $this->type instanceof AllergenType && $this->type->isDietaryLabel();
    }

    /**
     * الاسم المترجَم بلغة الموقع مع الرجوع للإنجليزية ثم العربية.
     */
    public function getLocalizedNameAttribute(): string
    {
        foreach ([app()->getLocale(), 'en', 'ar', 'nl'] as $locale) {
            $translation = $this->getTranslation('name', $locale, false);

            if (! empty($translation) && is_string($translation)) {
                return $translation;
            }
        }

        return '—';
    }

    /**
     * اسم أيقونة Lucide الجاهز للعرض (مع البادئة) أو null إن لم تُضبط.
     */
    public function iconName(): ?string
    {
        return self::normalizeIcon($this->icon);
    }

    /**
     * تنقية اسم الأيقونة وإضافة بادئة lucide- إن غابت.
     * مصدر حقيقة واحد يُستخدم في الموديل وفي التحقق داخل Filament.
     */
    public static function normalizeIcon(?string $value): ?string
    {
        $icon = Str::of((string) $value)
            ->lower()
            ->replaceMatches('/[^a-z0-9-]/', '')
            ->trim('-')
            ->toString();

        if ($icon === '') {
            return null;
        }

        return Str::startsWith($icon, 'lucide-') ? $icon : 'lucide-'.$icon;
    }

    /**
     * هل يوجد صورة مرفوعة تُعرض بدل الأيقونة؟
     */
    public function hasImage(): bool
    {
        return filled($this->image_path);
    }
}
