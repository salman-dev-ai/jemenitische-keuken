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

    protected function rules(): array
    {
        $isDelivery = $this->order_type === OrderType::DELIVERY->value;

        return [
            // بيانات العميل
            'customer_name'         => 'required|string|min:3|max:100',
            'customer_phone'        => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'customer_email'        => 'nullable|email|max:150',

            // حقول التوصيل (إلزامية فقط عند DELIVERY)
            'delivery_address'      => [$isDelivery ? 'required' : 'nullable', 'string', 'max:255'],
            'delivery_city'         => [$isDelivery ? 'required' : 'nullable', 'string', 'max:255'],
            'delivery_postal_code'  => [$isDelivery ? 'required' : 'nullable', 'string', 'max:20'],

            // حقول الحجز
            'reservation_date'      => 'required|date|after_or_equal:today',
            'reservation_time'      => 'required|date_format:H:i',
            'party_size'            => 'required|integer|min:1|max:20',
            'special_requests'      => 'nullable|string|max:500',

            // نوع الطلب
            'order_type'            => ['required', Rule::enum(OrderType::class)],
        ];
    }

    /**
     * 🆕 رسائل تحقق مخصصة.
     */
    protected function messages(): array
    {
        return [
            'delivery_address.required'     => __('messages.remember.address_required'),
            'delivery_city.required'        => __('messages.remember.city_required'),
            'delivery_postal_code.required' => __('messages.remember.postal_required'),
        ];
    }

    // ═══════════════════════════════════════════
    // 💳 إتمام الطلب (Checkout)
    // ═══════════════════════════════════════════

    public function checkout(
        ReservationService $reservationService,
        OrderService $orderService
    ): void {
        $this->resetErrorBag();

        if ($this->isCartEmpty) {
            $this->dispatch('notify',
                title:   __('messages.menu.empty'),
                message: '',
            );

            return;
        }

        // 🛡️ Rate Limiting
        $rateKey = 'checkout:'.request()->ip().':'.sha1($this->customer_phone);

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $this->addError('checkout', __('messages.order.rate_limit_exceeded'));

            return;
        }

        RateLimiter::hit($rateKey, 60);

        $validated = $this->validate();

        // 🧹 تطبيع رقم الهاتف
        $validated['customer_phone'] = $this->normalizePhone($validated['customer_phone']);

        try {
            $customer = null;

            DB::transaction(function () use ($validated, $reservationService, $orderService, &$customer) {
                // 1. العميل (إن كان "تذكرني" مفعّل)
                if ($this->remember_me) {
                    $customer = $this->upsertCustomer($this->buildCustomerExtraData());
                }

                // 2. نوع الطلب
                $finalOrderType = OrderType::tryFrom($this->order_type) ?? OrderType::DINE_IN;

                // 3. الحجز (فقط dine_in أو preorder)
                $reservation = null;
                if (in_array($finalOrderType, [OrderType::DINE_IN, OrderType::PREORDER], true)) {
                    $reservation = $reservationService->createReservation(
                        $this->buildReservationData($validated, $customer)
                    );
                }

                // 4. الطلب
                $orderItems = collect($this->cart)->map(fn ($item) => [
                    'menu_item_id' => $item['id'],
                    'quantity'     => $item['quantity'],
                ])->all();

                $orderService->createOrder(
                    $this->buildOrderData($validated, $customer, $reservation, $finalOrderType),
                    $orderItems
                );
            });

            // 5. الكوكي بعد نجاح الـ Transaction
            if ($this->remember_me && $customer) {
                $this->setCustomerCookie($customer);
                $this->isReturningCustomer = true;
            }

            RateLimiter::clear($rateKey);

            // 6. تنظيف الحالة
            $this->clearCart();
            $this->isCartModalOpen = false;
            $this->resetCustomerForm();

            // 7. إشعار النجاح
            $this->dispatch('notify',
                title:   __('messages.notifications.order_title'),
                message: __('messages.notifications.order_message'),
            );

        } catch (Exception $e) {
            report($e);
            $this->addError('checkout', __('messages.order.generic_error'));
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

        if ($customer) {
            $data['customer_id'] = $customer->id;
        }

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