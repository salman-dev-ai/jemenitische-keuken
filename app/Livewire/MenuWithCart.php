<?php

namespace App\Livewire;

use App\Enums\OrderType;
use App\Livewire\Concerns\ManagesCart;
use App\Livewire\Concerns\RemembersCustomer;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Services\ReservationService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class MenuWithCart extends Component
{
    use ManagesCart;
    use RemembersCustomer;

    // ═══════════════════════════════════════════
    // 🎛️ حالة المكوّن (State)
    // ═══════════════════════════════════════════

    public string $selectedCategorySlug = 'all';

    public bool $isCartModalOpen = false;

    // ═══════════════════════════════════════════
    // 👤 حقول العميل
    // ═══════════════════════════════════════════

    public string $customer_name = '';

    public string $customer_phone = ''; 

    public string $customer_email = '';

    public bool $remember_me = true;

    public bool $isReturningCustomer = false;

    // ═══════════════════════════════════════════
    // 🚚 حقول التوصيل
    // ═══════════════════════════════════════════

    public string $delivery_address = '';

    public string $delivery_city = '';

    public string $delivery_postal_code = '';

    // ═══════════════════════════════════════════
    // 📅 حقول الحجز
    // ═══════════════════════════════════════════

    public string $reservation_date = '';

    public string $reservation_time = '19:30';

    public int $party_size = 2;

    public string $order_type = 'dine_in';

    public string $special_requests = '';

    // ═══════════════════════════════════════════
    // 🔄 دورة الحياة (Lifecycle)
    // ═══════════════════════════════════════════

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->initializeCart();
        $this->hydrateCustomerFields();
    }

    /**
     * 🆕 تعبئة حقول العميل من الكوكي إن وُجد.
     */
    protected function hydrateCustomerFields(): void
    {
        $customer = $this->loadCustomerFromCookie();

        if (! $customer) {
            return;
        }

        $this->customer_name       = $customer->name ?? '';
        $this->customer_phone      = $customer->phone ?? '';
        $this->customer_email      = $customer->email ?? '';
        $this->delivery_address    = $customer->address ?? '';
        $this->delivery_city       = $customer->city ?? '';
        $this->delivery_postal_code = $customer->postal_code ?? '';
        $this->isReturningCustomer = true;
    }

    // ═══════════════════════════════════════════
    // 🆕 زر "لست أنا؟"
    // ═══════════════════════════════════════════

    public function forgetCustomer(): void
    {
        $this->forgetCustomerCookie();

        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'delivery_address',
            'delivery_city',
            'delivery_postal_code',
            'special_requests',
        ]);

        $this->isReturningCustomer = false;
        $this->remember_me         = true;

        $this->dispatch('notify',
            title:   __('messages.remember.forgotten_title'),
            message: __('messages.remember.forgotten_message'),
        );
    }

    // ═══════════════════════════════════════════
    // 🧾 قواعد التحقق (ديناميكية حسب نوع الطلب)
    // ═══════════════════════════════════════════

      /**
     * 🧾 قواعد التحقق (ديناميكية حسب نوع الطلب).
     *
     * - حقول التوصيل إلزامية فقط عند order_type = DELIVERY.
     * - رقم الهاتف يقبل الأرقام العربية (٠-٩) والإنجليزية (0-9).
     * - Party size محدود بقيمة من config/reservations.php (config-driven).
     * 
     * @return array<string, mixed>
     */
     /**
     * 🧾 قواعد التحقق (ديناميكية حسب نوع الطلب).
     *
     * القواعد:
     *   - حقول التوصيل إلزامية فقط عند order_type = DELIVERY.
     *   - حقول الحجز إلزامية فقط عند DINE_IN أو PREORDER.
     *   - رقم الهاتف يقبل الأرقام العربية (٠-٩) والإنجليزية (0-9).
     *   - order_type يقبل فقط قيم OrderType enum (القيم القديمة تُطبَّع في updatedOrderType).
     *
     *
     * @return array<string, mixed>
     */
       /**
     * 🧾 قواعد التحقق (ديناميكية حسب نوع الطلب).
     *
     * القواعد:
     *   - حقول التوصيل إلزامية فقط عند order_type = DELIVERY.
     *   - حقول الحجز إلزامية فقط عند DINE_IN أو PREORDER.
     *   - رقم الهاتف يقبل الأرقام العربية (٠-٩) والإنجليزية (0-9).
     *   - order_type يقبل فقط قيم OrderType enum (القيم القديمة تُطبَّع في updatedOrderType).
     *
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        // ─────────────────────────────────────────────────────────────
        // 1️⃣ تحديد النمط
        // ─────────────────────────────────────────────────────────────
        $isDelivery       = $this->order_type === OrderType::DELIVERY->value;
        $needsReservation = in_array($this->order_type, [
            OrderType::DINE_IN->value,
            OrderType::PREORDER->value,
        ], true);

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ الحدود من الإعدادات (config-driven)
        // ─────────────────────────────────────────────────────────────
        $minPartySize = (int) config('reservations.party_size.min', 1);
        $maxPartySize = (int) config('reservations.party_size.max', 20);

        return [
            // ─────────────────────────────────────────────────────────
            // 3️⃣ بيانات العميل
            // ─────────────────────────────────────────────────────────
            'customer_name'         => ['required', 'string', 'min:3', 'max:100'],

            // يقبل: أرقام عربية (٠-٩) + إنجليزية (0-9) + + - ( ) . والفراغات
            'customer_phone'        => [
                'required',
                'string',
                'min:7',
                'max:30',
                'regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/',
            ],

            'customer_email'        => ['nullable', 'email', 'max:150'],

            // ─────────────────────────────────────────────────────────
            // 4️⃣ حقول التوصيل (إلزامية فقط عند DELIVERY)
            // ─────────────────────────────────────────────────────────
            'delivery_address'      => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_city'         => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'delivery_postal_code'  => [
                $isDelivery ? 'required' : 'nullable',
                'string',
                'max:30',
                // 🎯 الرمز البريدي الهولندي: 4 أرقام + مسافة اختيارية + حرفان
                //    مثال: 1012 NK أو 1012NK
                'regex:/^\d{4}\s?[A-Za-z]{2}$/',
            ],

            // ─────────────────────────────────────────────────────────
            // 5️⃣ حقول الحجز (إلزامية فقط عند DINE_IN / PREORDER)
            // ─────────────────────────────────────────────────────────
            'reservation_date'      => [
                $needsReservation ? 'required' : 'nullable',
                'date',
                'after_or_equal:today',
            ],

            // 🎯 نمط صارم: H:i أو HH:i أو H:i:s أو HH:i:s
            //    - الساعة: 0-23 (أو 00-23)
            //    - الدقيقة: 00-59
            //    - الثانية (اختياري): 00-59
            //    ⚠️ يرفض الأوقات المستحيلة مثل 99:99 أو 25:00
            'reservation_time'      => [
                $needsReservation ? 'required' : 'nullable',
                'string',
                'regex:/^([01]?\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/',
            ],

            'party_size'            => [
                $needsReservation ? 'required' : 'nullable',
                'integer',
                'min:' . $minPartySize,
                'max:' . $maxPartySize,
            ],

            'special_requests'      => ['nullable', 'string', 'max:500'],

            // ─────────────────────────────────────────────────────────
            // 6️⃣ نوع الطلب — قيم enum الصحيحة فقط
            //    (القيم القديمة تُطبَّع في updatedOrderType)
            // ─────────────────────────────────────────────────────────
            'order_type'            => [
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
     * 🆕 رسائل تحقق مخصصة.
     */
       /**
     * 🆕 رسائل تحقق مخصصة.
     *
     * تُعيد مصفوفة برسائل التحقق المخصصة للحقول التي تحتاج ترجمة خاصة.
     * تعتمد على مفاتيح قسم 'order' في ملفات lang/{ar,en,nl}/messages.php.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            // ─────────────────────────────────────────────────────────────
            // 1️⃣ حقول التوصيل (إلزامية عند DELIVERY فقط)
            // ─────────────────────────────────────────────────────────────
            'delivery_address.required'     => __('messages.order.address_required'),
            'delivery_city.required'        => __('messages.order.city_required'),
            'delivery_postal_code.required' => __('messages.order.postal_required'),

            // ─────────────────────────────────────────────────────────────
            // 2️⃣ تنسيق رقم الهاتف (يقبل الأرقام العربية والإنجليزية)
            // ─────────────────────────────────────────────────────────────
            'customer_phone.regex'          => __('messages.order.phone_format'),
        ];
    }


    

    // ═══════════════════════════════════════════
    // 💳 إتمام الطلب (Checkout)
    // ═══════════════════════════════════════════

     /**
     * 💳 إتمام الطلب (Checkout).
     *
     * ينفّذ الخطوات التالية بالترتيب:
     *   1. التحقق من أن السلة غير فارغة.
     *   2. التحقق من صحة المدخلات (validation).
     *   3. تطبيق Rate Limiting (بعد التحقق، باستخدام IP).
     *   4. تطبيع رقم الهاتف (Arabic-Indic → ASCII).
     *   5. تنفيذ DB::transaction (حجز + طلب + عميل اختياري).
     *   6. ضبط كوكي "تذكرني" بعد نجاح الـ Transaction.
     *   7. تنظيف السلة والنموذج + إرسال إشعار النجاح.
     *
     * 📌 المصادر:
     *   - https://livewire.laravel.com/docs/4.x/actions
     *   - https://laravel.com/docs/12.x/rate-limiting
     *   - https://laravel.com/docs/12.x/database#database-transactions
     *
     * @param  ReservationService $reservationService
     * @param  OrderService       $orderService
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkout(
        ReservationService $reservationService,
        OrderService $orderService
    ): void {

        $this->normalizeOrderType();
        // ─────────────────────────────────────────────────────────────
        // 1️⃣ تصفير الأخطاء السابقة
        // ─────────────────────────────────────────────────────────────
        $this->resetErrorBag();

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ فحص السلة الفارغة
        // ─────────────────────────────────────────────────────────────
        if ($this->isCartEmpty) {
            $this->dispatch(
                'notify',
                title:   __('messages.menu.empty'),
                message: '',
                type:    'warning',
            );

            return;
        }

        // ─────────────────────────────────────────────────────────────
        // 3️⃣ التحقق من المدخلات أولاً (قبل Rate Limit)
        //    السبب: Rate Limit يستخدم IP فقط، لأن customer_phone غير موثوق بعد
        // ─────────────────────────────────────────────────────────────
        try {
            $validated = $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch(
                'notify',
                title:   __('messages.order.validation_error'),
                message: $e->validator->errors()->first(),
                type:    'error',
            );

            throw $e;
        }

        // ─────────────────────────────────────────────────────────────
        // 4️⃣ Rate Limiting (بعد التحقق، باستخدام IP كمفتاح أساسي)
        //    المصدر: https://laravel.com/docs/12.x/rate-limiting
        // ─────────────────────────────────────────────────────────────
        $rateKey = 'checkout:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $msg     = sprintf(__('messages.order.rate_limit_exceeded'), $seconds);

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $msg,
                type:    'error',
            );

            $this->addError('general', $msg);

            return;
        }

        RateLimiter::hit($rateKey, 60);

        // ─────────────────────────────────────────────────────────────
        // 5️⃣ تطبيع رقم الهاتف (تحويل الأرقام العربية إلى ASCII)
        //    يمنع تخزين نفس الرقم بتنسيقين مختلفين
        // ─────────────────────────────────────────────────────────────
        $validated['customer_phone'] = $this->normalizePhone($validated['customer_phone']);

        // ─────────────────────────────────────────────────────────────
        // 6️⃣ تنفيذ العملية داخل Transaction
        // ─────────────────────────────────────────────────────────────
        try {
            $customer = null;

            DB::transaction(function () use (
                $validated,
                $reservationService,
                $orderService,
                &$customer
            ) {
                // 6.1) العميل (عند تفعيل "تذكرني")
                if ($this->remember_me) {
                    $customer = $this->upsertCustomer($this->buildCustomerExtraData());
                }

                // 6.2) تحديد نوع الطلب النهائي — يرمي استثناء عند قيمة غير صالحة
                $finalOrderType = OrderType::tryFrom($this->order_type);

                if ($finalOrderType === null) {
                    throw new OrderException(__('messages.errors.invalid_order_type'));
                }

                // 6.3) الحجز (فقط dine_in أو preorder)
                $reservation = null;

                if (in_array($finalOrderType, [OrderType::DINE_IN, OrderType::PREORDER], true)) {
                    $reservation = $reservationService->createReservation(
                        $this->buildReservationData($validated, $customer)
                    );
                }

                // 6.4) تجهيز عناصر الطلب
                $orderItems = collect($this->cart)
                    ->map(fn (array $item): array => [
                        'menu_item_id' => $item['id'],
                        'quantity'     => $item['quantity'],
                    ])
                    ->all();

                // 6.5) إنشاء الطلب
                $orderService->createOrder(
                    $this->buildOrderData($validated, $customer, $reservation, $finalOrderType),
                    $orderItems
                );
            });

            // ─────────────────────────────────────────────────────────
            // 7️⃣ بعد نجاح الـ Transaction: كوكي "تذكرني"
            //    (خارج Transaction — قاعدة صريحة)
            // ─────────────────────────────────────────────────────────
            if ($this->remember_me && $customer) {
                $this->setCustomerCookie($customer);
                $this->isReturningCustomer = true;
            }

            // ─────────────────────────────────────────────────────────
            // 8️⃣ مسح Rate Limiter بعد النجاح
            // ─────────────────────────────────────────────────────────
            RateLimiter::clear($rateKey);

            // ─────────────────────────────────────────────────────────
            // 9️⃣ تنظيف الحالة
            // ─────────────────────────────────────────────────────────
            $this->clearCart();
            $this->isCartModalOpen = false;
            $this->resetCustomerForm();

            // ─────────────────────────────────────────────────────────
            // 🔟 إشعار النجاح
            // ─────────────────────────────────────────────────────────
            $this->dispatch(
                'notify',
                title:   __('messages.notifications.order_title'),
                message: __('messages.notifications.order_message'),
                type:    'success',
            );

        } catch (OrderException | ReservationException $e) {
            // ─────────────────────────────────────────────────────────
            // 🅰️ استثناءات العمل المعروفة — رسائل آمنة للمستخدم
            // ─────────────────────────────────────────────────────────
            report($e);

            $errMsg = $e->getMessage();  // ✅ آمن: رسائلنا المخصصة فقط

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $errMsg,
                type:    'error',
            );

            $this->addError('general', $errMsg);

        } catch (\Throwable $e) {
            // ─────────────────────────────────────────────────────────
            // 🅱️ استثناءات غير متوقعة — لا نكشف getMessage() للمستخدم
            // ─────────────────────────────────────────────────────────
            report($e);

            $errMsg = __('messages.order.generic_error');

            $this->dispatch(
                'notify',
                title:   __('messages.order.error_title'),
                message: $errMsg,
                type:    'error',
            );

            $this->addError('general', $errMsg);
        }
    }

    // ═══════════════════════════════════════════
    // 🏗️ بناء البيانات (Builder Helpers)
    // ═══════════════════════════════════════════

    /**
     * 🆕 الحقول الإضافية للعميل (العنوان إن كان توصيل).
     */
    protected function buildCustomerExtraData(): array
    {
        if ($this->order_type !== OrderType::DELIVERY->value) {
            return [];
        }

        return [
            'address'     => $this->delivery_address ?: null,
            'city'        => $this->delivery_city ?: null,
            'postal_code' => $this->delivery_postal_code ?: null,
        ];
    }

    /**
     * 🆕 تجهيز بيانات الحجز.
     */
    protected function buildReservationData(array $validated, $customer): array
    {
        $dishesSummary = collect($this->cart)->map(fn ($item) => sprintf(
            '%dx %s (€%.2f)',
            $item['quantity'],
            $item['name'],
            $item['price'] * $item['quantity']
        ))->implode(', ');

        $combinedNotes = trim(sprintf(
            'Pre-order: [%s] | Total: €%.2f | Notes: %s',
            $dishesSummary,
            $this->totalCartAmount,
            $this->special_requests
        ));

        $data = [
            'customer_name'     => $validated['customer_name'],
            'customer_phone'    => $validated['customer_phone'],
            'customer_email'    => $validated['customer_email'],
            'party_size'        => $this->party_size,
            'reservation_date'  => $validated['reservation_date'],
            'reservation_time'  => $validated['reservation_time'],
            'special_requests'  => $combinedNotes,
        ];

        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        return $data;
    }

    /**
     * 🆕 تجهيز بيانات الطلب.
     */
        /**
     * ┌─────────────────────────────────────────────────────────────────────┐
     * │ 🎯 تجهيز بيانات الطلب للإرسال إلى OrderService                     │
     * └─────────────────────────────────────────────────────────────────────┘
     *
     * ⚠️ مهم جداً:
     *    - reservation_id يُمرَّر دائماً (حتى لو null) لأن OrderService
     *      يتحقق من وجوده إن كان غير null.
     *    - customer_id يُمرَّر فقط إن وُجد العميل (remember_me).
     *    - notes يحتوي على مرجع الحجز إن وُجد لتمكين تتبّع الربط.
     */
    protected function buildOrderData(
        array $validated,
        $customer,
        $reservation,
        OrderType $finalOrderType
    ): array {
        $data = [
            'customer_name'  => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'type'           => $finalOrderType,
            'notes'          => $reservation
                ? "Linked to Reservation: {$reservation->reference_code}"
                : 'Direct order',
            'delivery_address'     => $this->delivery_address ?: null,
            'delivery_city'        => $this->delivery_city ?: null,
            'delivery_postal_code' => $this->delivery_postal_code ?: null,
        ];

        // 🔗 ربط العميل (إن وُجد)
        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

        // 🔗 ربط الحجز (إن وُجد) — يُمرَّر كـ null إن لم يكن هناك حجز
        $data['reservation_id'] = $reservation?->id;

        return $data;
    }

    /**
     * 🆕 تطبيع رقم الهاتف.
     */
    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]+/', '', $phone) ?: '';
    }

    /**
     * 🆕 إعادة تعيين حقول نموذج العميل.
     */
    protected function resetCustomerForm(): void
    {
        $this->reset([
            'customer_name',
            'customer_phone',
            'customer_email',
            'special_requests',
            'party_size',
            'delivery_address',
            'delivery_city',
            'delivery_postal_code',
        ]);
    }

    // ═══════════════════════════════════════════
    // 🗂️ القائمة والتصنيفات
    // ═══════════════════════════════════════════

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
        unset($this->filteredItems);
    }

    #[Computed(persist: true)]
    public function categories()
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => fn ($q) => $q->available()])
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function filteredItems()
    {
        return MenuItem::available()
            ->with('category')
            ->when(
                $this->selectedCategorySlug !== 'all',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($c) => $c->where('slug', $this->selectedCategorySlug)
                )
            )
            ->orderBy('sort_order')
            ->get();
    }


        /**
     * 🧹 تطبيع قيمة order_type إلى قيمة OrderType enum الصحيحة.
     *
     * تُترجم القيم القديمة (camelCase من واجهات سابقة) إلى القيم الحالية.
     * مفيدة عند استدعائها يدوياً من mount() أو عند استقبال قيمة من query string.
     *
     * 🎯 القيم المدعومة:
     *    - 'dineIn'   → 'dine_in'
     *    - 'takeaway' → 'pickup'
     *
     * @return void
     */
    protected function normalizeOrderType(): void
    {
        // ─────────────────────────────────────────────────────────────
        // 1️⃣ خريطة القيم القديمة → الجديدة
        // ─────────────────────────────────────────────────────────────
        $legacyMap = [
            'dineIn'   => OrderType::DINE_IN->value,
            'takeaway' => OrderType::PICKUP->value,
        ];

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ التطبيع إذا كانت القيمة الحالية قديمة
        // ─────────────────────────────────────────────────────────────
        if (isset($legacyMap[$this->order_type])) {
            $this->order_type = $legacyMap[$this->order_type];
        }
    }

    /**
     * 🔄 Lifecycle hook — يُستدعى تلقائياً عند تحديث order_type من الواجهة.
     *
     * Livewire يستدعي `updated{PropertyName}` تلقائياً عند تغيير الخاصية.
     * هذا يضمن أن أي قيمة قديمة تُطبَّع فوراً قبل أن تصل إلى rules().
     *
     * 📌 المصدر:
     *    - https://livewire.laravel.com/docs/4.x/lifecycle-hooks#updated
     *
     * @param  mixed $value القيمة الجديدة لـ order_type
     * @return void
     */
    public function updatedOrderType(mixed $value): void
    {
        $this->normalizeOrderType();
    }

    // ═══════════════════════════════════════════
    // 🖼️ العرض
    // ═══════════════════════════════════════════

    public function placeholder(): View
    {
        return view('livewire.placeholders.menu-with-cart');
    }

    public function render(): View
    {
        return view('livewire.menu-with-cart');
    }
}