<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Services/OrderService.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    خدمة إنشاء الطلبات — تحقق، قفل الأطباق، حساب الإجماليات، الحفظ.
 *
 * 💰 منطق الضريبة (مع دعم الكوبون):
 *    - subtotal = Σ(unit_price × qty)
 *    - discount = min(coupon.discount, subtotal)
 *    - tax      = (subtotal - discount) × (vat_rate / 100)
 *    - total    = (subtotal - discount) + tax
 *    ⚠️ الحسابات تتم داخل PricingService (لا تكرار).
 *
 * 🔐 الأمان:
 *    - كل العملية داخل DB::transaction (attempts: 3).
 *    - lockForUpdate على menu_items لمنع تغيير السعر.
 *    - claim + redeem داخل نفس الـ transaction (Atomicity).
 *
 * 🎫 تدفّق الكوبون:
 *    1. Client يُدخل الكود في السلة → Livewire::applyCoupon()
 *       → CouponService::validate() → CouponResult
 *    2. عند تأكيد الطلب → OrderService::createOrder(couponCode)
 *       → يتحقق مجدداً (لضمان عدم استنفاد الكوبون)
 *       → يحسب discount عبر PricingService
 *       → يحفظ snapshot الكوبون في الطلب
 *       → claim + redeem
 *
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Services;

use App\DTOs\CouponResult;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Exceptions\OrderException;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Dependency Injection — بدل new داخل الدوال.
     */
    public function __construct(
        private readonly CouponService $couponService,
        private readonly PricingService $pricingService,
    ) {}

    // ══════════════════════════════════════════════════════════════
    // 🎯 الدالة الرئيسية
    // ══════════════════════════════════════════════════════════════

    /**
     * 📦 إنشاء طلب جديد مع كوبون اختياري.
     *
     * @param  array<string, mixed>  $orderData
     * @param  array<int, array{menu_item_id: int|string, quantity: int|string}>  $items
     * @param  string|null  $couponCode  كود الكوبون (اختياري)
     *
     * @throws OrderException
     */
    public function createOrder(
        array $orderData,
        array $items,
        ?string $couponCode = null,
    ): Order {
        // ─────────────────────────────────────────────────────────
        // 1️⃣ تحققات أساسية (بدون DB)
        // ─────────────────────────────────────────────────────────
        if (empty($items)) {
            throw OrderException::emptyCart();
        }

        $type = $this->resolveType($orderData['type'] ?? null);
        $this->validateReservation($orderData['reservation_id'] ?? null);

        // ─────────────────────────────────────────────────────────
        // 2️⃣ العملية كاملة داخل transaction (Atomicity)
        // ─────────────────────────────────────────────────────────
        return DB::transaction(function () use (
            $orderData,
            $items,
            $type,
            $couponCode,
        ): Order {
            // 2.1 ─── قفل الأطباق لمنع تغيير الأسعار ───
            $menuItemIds = collect($items)
                ->pluck('menu_item_id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            /** @var Collection<int, MenuItem> $menuItems */
            $menuItems = MenuItem::query()
                ->whereIn('id', $menuItemIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 2.2 ─── تجهيز العناصر + subtotal ───
            [$preparedItems, $subtotal] = $this->prepareItems($items, $menuItems);

            // 2.3 ─── التحقق من الكوبون (إن وُجد) ───
            $couponResult = CouponResult::none();
            $customer     = null;

            if ($couponCode !== null && $couponCode !== '') {
                $customer = $this->resolveCouponCustomer($orderData, $couponCode);

                // تحويل العناصر لصيغة CouponService::validate()
                $couponItems = $this->buildCouponItems($preparedItems, $menuItems);

                // ⚠️ validate() لا ترمي — تُرجع CouponResult
                $couponResult = $this->couponService->validate(
                    $couponCode,
                    $customer,
                    $couponItems,
                );

                if (! $couponResult->isValid) {
                    // رمي استثناء باستخدام رسالة CouponResult
                    throw new OrderException(
                        $couponResult->errorMessage ?? __('coupons.errors.invalid_code'),
                    );
                }
            }

            // 2.4 ─── حساب التسعير الكامل عبر PricingService ───
            $discountAmount = $couponResult->isValid
                ? $couponResult->discountAmount
                : 0.0;

            /** @var \App\DTOs\PricingBreakdown $pricing */
            $pricing = $this->pricingService->calculate($subtotal, $discountAmount);

            // 2.5 ─── تجهيز بيانات الطلب ───
            $finalOrderData = $this->buildFinalOrderData($orderData, $type, $pricing);

            // 2.6 ─── snapshot الكوبون ───
            if ($couponResult->isValid && $couponResult->coupon !== null) {
                $finalOrderData['coupon_id']       = $couponResult->coupon->id;
                $finalOrderData['coupon_code']     = $couponResult->coupon->code;
                $finalOrderData['discount_type']   = $couponResult->coupon->discount_type->value;
                $finalOrderData['discount_amount'] = $pricing->discount;
            }

            // 2.7 ─── إنشاء الطلب + العناصر ───
            $order = Order::create($finalOrderData);
            $order->items()->createMany($preparedItems);

            // 2.8 ─── claim + redeem داخل نفس transaction ───
            if (
                $couponResult->isValid
                && $couponResult->coupon !== null
                && $customer !== null
            ) {
                $usage = $this->couponService->claim($customer, $couponResult->coupon);
                $this->couponService->redeem($usage, $order, $pricing->discount);
            }

            // 2.9 ─── تسجيل تدقيقي ───
            Log::debug('Order created', [
                'id'              => $order->id,
                'order_number'    => $order->order_number,
                'customer_id'     => $order->customer_id,
                'type'            => $order->type->value,
                'subtotal'        => $pricing->subtotal,
                'discount_amount' => $pricing->discount,
                'vat_rate'        => $pricing->vatRate,
                'tax'             => $pricing->vat,
                'total'           => $pricing->total,
                'items_count'     => count($preparedItems),
                'coupon_code'     => $couponResult->coupon?->code,
            ]);

            return $order;
        }, attempts: 3);
    }

    // ══════════════════════════════════════════════════════════════
    // 🔧 Builder Helpers
    // ══════════════════════════════════════════════════════════════

    /**
     * 🎫 حلّ العميل صاحب الكوبون (مع التحقق من وجوده).
     *
     * @throws OrderException
     */
    protected function resolveCouponCustomer(array $orderData, string $couponCode): Customer
    {
        $customerId = $orderData['customer_id'] ?? null;

        if ($customerId === null) {
            throw new OrderException(
                __('coupons.errors.customer_required'),
            );
        }

        $customer = Customer::find($customerId);

        if ($customer === null) {
            throw new OrderException(
                __('coupons.errors.customer_not_found'),
            );
        }

        return $customer;
    }

    /**
     * 🎫 تحويل العناصر المُجهَّزة إلى صيغة CouponService::validate().
     *
     * المطلوب: array{id, price, quantity, category_id}
     *
     * @param  array<int, array{menu_item_id: int, quantity: int, unit_price: float, total_price: float}>  $preparedItems
     * @param  Collection<int, MenuItem>  $menuItems
     *
     * @return array<int, array{id: int, price: float, quantity: int, category_id: ?int}>
     */
    protected function buildCouponItems(array $preparedItems, Collection $menuItems): array
    {
        return collect($preparedItems)
            ->map(function (array $item) use ($menuItems): array {
                $menuItem = $menuItems->get($item['menu_item_id']);

                return [
                    'id'          => $item['menu_item_id'],
                    'price'       => (float) $item['unit_price'],
                    'quantity'    => (int) $item['quantity'],
                    'category_id' => $menuItem?->menu_category_id,
                ];
            })
            ->all();
    }

    /**
     * 📋 تجهيز البيانات النهائية للطلب.
     *
     * @param  array<string, mixed>  $orderData
     * @param  \App\DTOs\PricingBreakdown  $pricing
     *
     * @return array<string, mixed>
     */
    protected function buildFinalOrderData(
        array $orderData,
        OrderType $type,
        \App\DTOs\PricingBreakdown $pricing,
    ): array {
        $data = Arr::only($orderData, [
            'customer_id',
            'reservation_id',
            'customer_name',
            'customer_phone',
            'customer_email',
            'delivery_address',
            'delivery_city',
            'delivery_postal_code',
            'payment_method',
            'notes',
        ]);

        $data['type']           = $type;
        $data['order_number']   = $this->generateOrderNumber();
        $data['subtotal']       = $pricing->subtotal;
        $data['tax']            = $pricing->vat;
        $data['total']          = $pricing->total;
        $data['status']         = $this->resolveStatus($orderData['status'] ?? null);
        $data['payment_status'] = $this->resolvePaymentStatus($orderData['payment_status'] ?? null);

        return $data;
    }

    // ══════════════════════════════════════════════════════════════
    // 🔧 Core Helpers
    // ══════════════════════════════════════════════════════════════

    /**
     * 🧾 تجهيز العناصر وحساب المجموع الفرعي.
     *
     * @param  array<int, array{menu_item_id: int|string, quantity: int|string}>  $items
     * @param  Collection<int, MenuItem>  $menuItems
     *
     * @return array{0: array<int, array<string, mixed>>, 1: float}
     *
     * @throws OrderException
     */
    protected function prepareItems(array $items, Collection $menuItems): array
    {
        $subtotal = 0.0;
        $preparedItems = [];
        $maxQuantity = RestaurantSetting::maxQuantityPerItem();

        foreach ($items as $item) {
            $menuItemId = (int) ($item['menu_item_id'] ?? 0);

            if ($menuItemId < 1) {
                throw OrderException::invalidMenuItemId();
            }

            if (! $menuItems->has($menuItemId)) {
                throw OrderException::menuItemUnavailable($menuItemId);
            }

            /** @var MenuItem $menuItem */
            $menuItem = $menuItems->get($menuItemId);

            // ✅ التحقق من التوفر
            if (! $menuItem->is_available) {
                throw OrderException::menuItemUnavailable($menuItemId);
            }

            $quantity = (int) ($item['quantity'] ?? 0);

            if ($quantity < 1 || $quantity > $maxQuantity) {
                throw OrderException::invalidQuantity($menuItem->name, $quantity);
            }

            $unitPrice = (float) $menuItem->price;
            $itemTotal = round($unitPrice * $quantity, 2);
            $subtotal += $itemTotal;

            $preparedItems[] = [
                'menu_item_id' => $menuItem->id,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'total_price'  => $itemTotal,
            ];
        }

        return [$preparedItems, round($subtotal, 2)];
    }

    /**
     * 🔗 التحقق من وجود الحجز إن وُجد reservation_id.
     *
     * @throws OrderException
     */
    protected function validateReservation(?int $reservationId): void
    {
        if (empty($reservationId)) {
            return;
        }

        if (! Reservation::where('id', $reservationId)->exists()) {
            throw OrderException::invalidReservation();
        }
    }

    /**
     * 🔄 تحويل type إلى Enum صالح.
     *
     * @throws OrderException
     */
    protected function resolveType(mixed $type): OrderType
    {
        if ($type instanceof OrderType) {
            return $type;
        }

        $resolved = is_string($type) ? OrderType::tryFrom($type) : null;

        if ($resolved === null) {
            throw OrderException::invalidType();
        }

        return $resolved;
    }

    /**
     * 🔄 تحويل status إلى Enum صالح.
     *
     * @throws OrderException
     */
    protected function resolveStatus(mixed $status): OrderStatus
    {
        if ($status === null) {
            return OrderStatus::PENDING;
        }

        if ($status instanceof OrderStatus) {
            return $status;
        }

        $resolved = is_string($status) ? OrderStatus::tryFrom($status) : null;

        if ($resolved === null) {
            throw OrderException::invalidStatus();
        }

        return $resolved;
    }

    /**
     * 💳 تحويل payment_status إلى قيمة صالحة.
     */
    protected function resolvePaymentStatus(mixed $paymentStatus): string
    {
        $allowed = ['pending', 'paid', 'failed', 'refunded'];

        if (! is_string($paymentStatus) || ! in_array($paymentStatus, $allowed, true)) {
            return 'pending';
        }

        return $paymentStatus;
    }

    /**
     * 🎫 توليد رقم طلب فريد.
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid()->toBase32());
    }
}
