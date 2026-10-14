<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Enums\OrderType;
use Illuminate\Validation\Rule;

/**
 * Trait: التحقق من نموذج الحجز/الطلب وتطبيع نوع الطلب.
 *
 * 🎯 المسؤولية: قواعد التحقق (تتغير حسب نوع الطلب) ورسائل الخطأ
 *    المخصصة، وتحويل القيم القديمة لنوع الطلب إلى القيم الحالية.
 *
 * ⚠️ يعتمد على خصائص المكوّن: $order_type, $customer_*, $delivery_*,
 *    $reservation_*, $party_size, $special_requests.
 *
 * ⚠️ Livewire يستدعي rules() و messages() تلقائياً عند validate()،
 *    فلا تُعرِّف دالتين بنفس الاسم في Trait آخر داخل المكوّن.
 */
trait ValidatesCheckout
{
    /**
     * قواعد التحقق الديناميكية:
     *   - التوصيل: العنوان والمدينة والرمز البريدي مطلوبة فقط عند "توصيل".
     *   - الحجز: التاريخ والوقت وعدد الأشخاص مطلوبة فقط عند "dine_in" أو "preorder".
     *   - حدود عدد الأشخاص تُقرأ من config/reservations.php.
     *
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $isDelivery       = $this->order_type === OrderType::DELIVERY->value;
        $needsReservation = in_array($this->order_type, [
            OrderType::DINE_IN->value,
            OrderType::PREORDER->value,
        ], true);

        $minPartySize = (int) config('reservations.party_size.min', 1);
        $maxPartySize = (int) config('reservations.party_size.max', 20);

        return [
            // ─── بيانات العميل ───
            'customer_name'  => ['required', 'string', 'min:3', 'max:100'],
            'customer_phone' => [
                'required',
                'string',
                'min:7',
                'max:30',
                'regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/',
            ],
            'customer_email' => ['nullable', 'email', 'max:150'],

            // ─── التوصيل ───
            'delivery_address' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_city' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_postal_code' => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:30',
                'regex:/^\d{4}\s?[A-Za-z]{2}$/',
            ],

            // ─── الحجز ───
            'reservation_date' => [
                $needsReservation ? 'required' : 'nullable',
                'date',
                'after_or_equal:today',
            ],
            'reservation_time' => [
                $needsReservation ? 'required' : 'nullable',
                'string',
                'regex:/^([01]?\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/',
            ],
            'party_size' => [
                $needsReservation ? 'required' : 'nullable',
                'integer',
                'min:' . $minPartySize,
                'max:' . $maxPartySize,
            ],

            'special_requests' => ['nullable', 'string', 'max:500'],

            // ─── نوع الطلب ───
            'order_type' => [
                'required',
                'string',
                Rule::in([
                    OrderType::DINE_IN->value,
                    OrderType::PICKUP->value,
                    OrderType::PREORDER->value,
                    OrderType::DELIVERY->value,
                ]),
            ],
        ];
    }

    /**
     * رسائل الخطأ المخصصة (مأخوذة من ملفات الترجمة).
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'delivery_address.required'     => __('messages.order.address_required'),
            'delivery_city.required'        => __('messages.order.city_required'),
            'delivery_postal_code.required' => __('messages.order.postal_required'),
            'customer_phone.regex'          => __('messages.order.phone_format'),
        ];
    }

    /**
     * تحويل القيم القديمة لنوع الطلب ('dineIn', 'takeaway')
     * إلى قيم الـ Enum الحالية للحفاظ على التوافق مع القوالب القديمة.
     */
    protected function normalizeOrderType(): void
    {
        $legacyMap = [
            'dineIn'   => OrderType::DINE_IN->value,
            'takeaway' => OrderType::PICKUP->value,
        ];

        if (isset($legacyMap[$this->order_type])) {
            $this->order_type = $legacyMap[$this->order_type];
        }
    }

    /**
     * 🔄 Livewire hook — يُستدعى عند تغيير نوع الطلب من القائمة المنسدلة.
     */
    public function updatedOrderType(mixed $value): void
    {
        $this->normalizeOrderType();
    }
}
