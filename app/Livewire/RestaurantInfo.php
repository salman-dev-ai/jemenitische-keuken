<?php

declare(strict_types=1);

/**
 * مكوّن Livewire: RestaurantInfo
 *
 * وظيفة الملف:
 * - تجميع "معلومات المطعم" الشاملة في صفحة واحدة للعميل:
 *   أوقات الدوام + قاموس مسببات الحساسية/العلامات الغذائية + جدول مناطق التوصيل.
 *
 * ملاحظات:
 * - كل البيانات تُجلب عبر خصائص محسوبة (Computed) وتبقى على السيرفر (لا تُرسل للمتصفح).
 * - إعادة استخدام OpeningHoursPresenter ونطاقات Allergen و DeliveryZone دون تكرار المنطق.
 * - الفئة نهائية (final) لمنع الوراثة غير المقصودة.
 */

namespace App\Livewire;

use App\Models\Allergen;
use App\Models\DeliveryZone;
use App\Models\RestaurantSetting;
use App\Support\OpeningHoursPresenter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class RestaurantInfo extends Component
{
    /**
     * إعدادات المطعم (Singleton) — مصدر الاسم وأوقات الدوام ووسائل التواصل.
     */
    #[Computed]
    public function setting(): RestaurantSetting
    {
        return RestaurantSetting::current();
    }

    /**
     * صفوف أوقات الدوام جاهزة للعرض (نفس مُنسّق بطاقة التذييل).
     *
     * @return array<int, array{label: string, js: array<int, int>, hours: string|null}>
     */
    #[Computed]
    public function hoursRows(): array
    {
        $hours = $this->setting->getAttribute('opening_hours');

        return OpeningHoursPresenter::rows(is_array($hours) ? $hours : null);
    }

    /**
     * قاموس الحساسية المفعّل فقط، مرتباً حسب الترتيب المحدد في لوحة التحكم.
     *
     * @return SupportCollection<int, Allergen>
     */
    #[Computed]
    public function allergenCatalog(): SupportCollection
    {
        return Allergen::query()->active()->ordered()->get();
    }

    /**
     * تقسيم القاموس إلى مجموعتين: مسببات الحساسية، والعلامات الغذائية.
     *
     * @return array{allergens: SupportCollection<int, Allergen>, labels: SupportCollection<int, Allergen>}
     */
    #[Computed]
    public function allergenGroups(): array
    {
        return [
            'allergens' => $this->allergenCatalog
                ->reject(fn (Allergen $allergen): bool => $allergen->isDietaryLabel())
                ->values(),
            'labels' => $this->allergenCatalog
                ->filter(fn (Allergen $allergen): bool => $allergen->isDietaryLabel())
                ->values(),
        ];
    }

    /**
     * مناطق التوصيل مرتبة أبجدياً حسب اسم المنطقة.
     *
     * @return Collection<int, DeliveryZone>
     */
    #[Computed]
    public function deliveryZones(): Collection
    {
        return DeliveryZone::query()
            ->orderBy('region_name')
            ->get();
    }

    /**
     * عرض الواجهة.
     */
    public function render(): View
    {
        return view('livewire.restaurant-info');
    }
}
