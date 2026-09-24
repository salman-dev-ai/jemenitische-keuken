<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Models/RestaurantSetting.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    نموذج إعدادات المطعم — جدول Singleton (صف واحد فقط).
 *    يوفر accessors للقراءة (vatRate, maxQuantityPerItem) + Cache تلقائي.
 *
 * 🧩 يعتمد على:
 *    - Spatie\Translatable\HasTranslations (لحقل name فقط)
 *    - Illuminate\Support\Facades\Cache
 *
 * 🔐 الأمان:
 *    - Cache يُبطَّل تلقائياً عند أي saved/deleted.
 *    - لا يوفر métodos لإنشاء صفوف إضافية.
 *
 * 📌 طريقة الاستخدام:
 *    $settings = RestaurantSetting::current();
 *    $rate     = RestaurantSetting::vatRate();          // 0.09
 *    $max      = RestaurantSetting::maxQuantityPerItem(); // 50
 *
 * ⚠️ تحذيرات مهمة:
 *    - address غير مترجم (string) — عنوان واحد لجميع اللغات.
 *    - vat_rate يُخزَّن كنسبة مئوية (9.00 = 9%) ويُحوَّل عند القراءة.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class RestaurantSetting extends Model
{
    use HasFactory;
    use HasTranslations;

    // ─────────────────────────────────────────────────────────────
    // 🈯 الحقول القابلة للترجمة
    //    - name: JSON في DB → مترجم
    //    - address: string في DB → غير مترجم
    // ─────────────────────────────────────────────────────────────
    public array $translatable = ['name', 'address'];

    // ─────────────────────────────────────────────────────────────
    // 📝 الحقول القابلة للتعيين الجماعي
    // ─────────────────────────────────────────────────────────────
    protected $fillable = [
        'name',
        'phone',
        'whatsapp',
        'email',
        'address',
        'city',
        'postal_code',
        'google_maps_link',
        'opening_hours',
        'accepts_reservations',
        'accepts_online_orders',
        'vat_rate',
        'max_quantity_per_item',
    ];

    /**
     * 🎭 تحويلات الأنواع.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'opening_hours'         => 'array',
            'accepts_reservations'  => 'boolean',
            'accepts_online_orders' => 'boolean',
            'vat_rate'              => 'decimal:2',
            'max_quantity_per_item' => 'integer',
        ];
    }

    /**
     * 🎬 أحداث النموذج — إبطال Cache تلقائياً عند أي تعديل.
     */
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('restaurant_settings'));
        static::deleted(fn () => Cache::forget('restaurant_settings'));
    }

    /**
     * 📥 جلب الصف الوحيد (Singleton) مع Cache دائم.
     *
     * - يُنشئ صفاً افتراضياً إذا لم يوجد.
     * - Cache يُبطَّل تلقائياً عند أي تعديل.
     * - يعيد دائماً كائن RestaurantSetting (لا null أبداً).
     *
     * @return self
     */
    public static function current(): self
    {
        return Cache::rememberForever('restaurant_settings', function () {
            return static::query()->firstOrCreate(
                ['id' => 1],
                [
                    'name' => [
                        'ar' => 'المطبخ اليمني',
                        'en' => 'Yemeni Kitchen',
                        'nl' => 'Jemenitische Keuken',
                    ],
                    'phone'                 => '+31 20 000 0000',
                    'email'                 => 'info@example.com',
                    'whatsapp'              => '+31612345678',
                    'address'               => 'Damrak 45',
                    'city'                  => 'Amsterdam',
                    'postal_code'           => '1012 NK',
                    'vat_rate'              => 9.00,
                    'max_quantity_per_item' => 50,
                ],
            );
        });
    }

    /**
     * 💰 معدّل الضريبة (BTW) — كنسبة عشرية.
     *
     * مثال: 9.00 في DB → 0.09
     *
     * @return float
     */
    public static function vatRate(): float
    {
        return round(((float) static::current()->vat_rate) / 100, 4);
    }

    /**
     * 🔢 الحد الأقصى للكمية لكل طبق.
     *
     * @return int
     */
    public static function maxQuantityPerItem(): int
    {
        return (int) static::current()->max_quantity_per_item;
    }

    /**
     * 🏷️ الاسم حسب اللغة الحالية.
     *
     * - يعتمد على Spatie Translatable.
     * - Fallback: العربية → الإنجليزية.
     *
     * @return string
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->getTranslation('name', $locale)
            ?: $this->getTranslation('name', 'ar')
            ?: $this->getTranslation('name', 'en')
            ?: '';
    }
}