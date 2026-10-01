<?php

/**
 * مكوّن Livewire: ShowDeliveryZones
 *
 * وظيفة الملف:
 * - عرض قائمة بجميع مناطق التوصيل في الواجهة الأمامية.
 * - جلب البيانات من موديل DeliveryZone وترتيبها حسب اسم المنطقة.
 *
 * ملاحظات:
 * - استخدام Livewire\Attributes\Computed لجلب البيانات بشكل كسول (Lazy) وتخزينها على السيرفر فقط.
 * - الفئة نهائية (final) لمنع الوراثة غير المقصودة.
 * - فرض أنواع صارمة (strict_types) لتقليل الأخطاء.
 * - لا توجد خصائص عامة (public properties) لتقليل حجم البيانات المُرسَل بين السيرفر والمتصفح.
 */

declare(strict_types=1);                              // فرض أنواع صارمة على مستوى الملف

namespace App\Livewire;

use App\Models\DeliveryZone;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class ShowDeliveryZones extends Component   // final لمنع الوراثة غير المقصودة
{
    /**
     * خاصية محسوبة (Computed) تُرجع جميع مناطق التوصيل مرتبة حسب اسم المنطقة.
     *
     * @return Collection<int, DeliveryZone>
     */
    #[Computed]                                       // تُخزَّن النتيجة مؤقتًا على السيرفر وتُعاد حسابها عند الحاجة
    public function deliveryZones(): Collection
    {
        return DeliveryZone::query()
            ->orderBy('region_name')                  // ترتيب أبجدي حسب اسم المنطقة
            ->get();
    }

    /**
     * عرض الواجهة.
     */
    public function render(): View
    {
        return view('livewire.show-delivery-zones'); // استدعاء ملف العرض
    }
}