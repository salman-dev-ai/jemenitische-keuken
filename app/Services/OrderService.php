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
 * 🧩 يعتمد على:
 *    - App\Models\MenuItem, Order, Reservation, RestaurantSetting
 *    - App\Enums\OrderType, OrderStatus
 *    - App\Exceptions\OrderException
 *
 * 💰 منطق الضريبة:
 *    - الأسعار في menu_items.price لا تشمل BTW.
 *    - vat_rate يُقرأ من DB (RestaurantSetting) — يعدّله الأدمن من Filament.
 *    - subtotal = sum(unit_price × qty)
 *    - tax      = subtotal × (vat_rate / 100)
 *    - total    = subtotal + tax
 *
 * 🔐 الأمان:
 *    - التحقق من type و status و payment_status قبل الحفظ.
 *    - lockForUpdate على menu_items لمنع تغيير السعر أثناء الطلب.
 *
 * ⚠️ تحذيرات مهمة:
 *    - لا تعتمد على config('orders.*') — كل الإعدادات من DB.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Exceptions\OrderException;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * 📦 إنشاء طلب جديد مع كل عناصره.
     *
     * @param  array<string, mixed> $orderData
     * @param  array<int, array{menu_item_id: int|string, quantity: int|string}> $items
     * @return Order
     *
     * @throws OrderException
     */
    public function createOrder(array $orderData, array $items): Order
    {
        // ─────────────────────────────────────────────────────────────
        // 1️⃣ السلة غير فارغة
        // ─────────────────────────────────────────────────────────────
        if (empty($items)) {
            throw OrderException::emptyCart();
        }

        // ─────────────────────────────────────────────────────────────
        // 2️⃣ تحقق من type (قبل Transaction)
        // ─────────────────────────────────────────────────────────────
         
        $type = $this->resolveType($orderData['type'] ?? null);

        // ─────────────────────────────────────────────────────────────
        // 3️⃣ تحقق من الحجز (إن وُجد)
        // ─────────────────────────────────────────────────────────────
        $this->validateReservation($orderData['reservation_id'] ?? null);

        return DB::transaction(function () use ($orderData, $items, $type) {
            // ─────────────────────────────────────────────────────────
            // 4️⃣ قفل الأطباق لمنع تغيير الأسعار أثناء الطلب
            // ─────────────────────────────────────────────────────────
            $menuItemIds = collect($items)
                ->pluck('menu_item_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $menuItems = MenuItem::query()
                ->whereIn('id', $menuItemIds)
                ->available()
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // ─────────────────────────────────────────────────────────
            // 5️⃣ تجهيز العناصر + حساب المجموع
            // ─────────────────────────────────────────────────────────
            [$preparedItems, $subtotal] = $this->prepareItems($items, $menuItems);

            // ─────────────────────────────────────────────────────────
            // 6️⃣ حساب الضريبة (ديناميكية من Filament)
            // ─────────────────────────────────────────────────────────
            $vatRate = RestaurantSetting::vatRate();   // مثال: 0.09
            $tax     = round($subtotal * $vatRate, 2);
            $total   = round($subtotal + $tax, 2);

            // ─────────────────────────────────────────────────────────
            // 7️⃣ تجهيز بيانات الطلب
            // ─────────────────────────────────────────────────────────
            $finalOrderData = Arr::only($orderData, [
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

            $finalOrderData['type']           = $type;
            $finalOrderData['order_number']   = $this->generateOrderNumber();
            $finalOrderData['subtotal']       = $subtotal;
            $finalOrderData['tax']            = $tax;
            $finalOrderData['total']          = $total;
            $finalOrderData['status']         = $this->resolveStatus($orderData['status'] ?? null);
            $finalOrderData['payment_status'] = $this->resolvePaymentStatus($orderData['payment_status'] ?? null);

            // ─────────────────────────────────────────────────────────
            // 8️⃣ الإنشاء
            // ─────────────────────────────────────────────────────────
            $order = Order::create($finalOrderData);
            $order->items()->createMany($preparedItems);

            // ─────────────────────────────────────────────────────────
            // 9️⃣ تسجيل للتدقيق (debug فقط)
            // ─────────────────────────────────────────────────────────
            Log::debug('Order created', [
                'id'           => $order->id,
                'order_number' => $order->order_number,
                'customer_id'  => $order->customer_id,
                'type'         => $order->type->value,
                'subtotal'     => $subtotal,
                'vat_rate'     => $vatRate,
                'tax'          => $tax,
                'total'        => $total,
                'items_count'  => count($preparedItems),
            ]);

            return $order;
        });
    }

    /**
     * 🧾 تجهيز العناصر وحساب المجموع الفرعي.
     *
     * @param  array<int, array{menu_item_id: int|string, quantity: int|string}> $items
     * @param  \Illuminate\Support\Collection<int, MenuItem> $menuItems
     * @return array{0: array<int, array<string, mixed>>, 1: float}
     *
     * @throws OrderException
     */
    protected function prepareItems(array $items, $menuItems): array
    {
        $subtotal      = 0.0;
        $preparedItems = [];
        $maxQuantity   = RestaurantSetting::maxQuantityPerItem();

        foreach ($items as $item) {
            $menuItemId = (int) ($item['menu_item_id'] ?? 0);

            if ($menuItemId < 1) {
                throw OrderException::invalidMenuItemId();
            }

            if (! $menuItems->has($menuItemId)) {
                throw OrderException::menuItemUnavailable($menuItemId);
            }

            $menuItem = $menuItems->get($menuItemId);
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

        return [$preparedItems, $subtotal];
    }

    /**
     * 🔗 التحقق من وجود الحجز إن وُجد reservation_id.
     *
     * @param  int|null $reservationId
     * @return void
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
     * 🔄 تحويل type إلى Enum صالح — يقبل OrderType أو string.
     *
     * يتبع نفس نمط resolveStatus() — يقبل الكائن الجاهز أو النص.
     *
     * @param  mixed $type
     * @return OrderType
     *
     * @throws OrderException
     */
    protected function resolveType(mixed $type): OrderType
    {
        // ✅ الحالة 1: كائن OrderType جاهز
        if ($type instanceof OrderType) {
            return $type;
        }

        // ✅ الحالة 2: string يُحوَّل عبر tryFrom
        $resolved = is_string($type) ? OrderType::tryFrom($type) : null;

        if ($resolved === null) {
            throw OrderException::invalidType();
        }

        return $resolved;
    }
    /**
     * 🔄 تحويل status إلى Enum صالح — يرمي استثناء عند قيمة خاطئة.
     *
     * @param  mixed $status
     * @return OrderStatus
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
     * 💳 تحويل payment_status إلى قيمة صالحة من whitelist.
     *
     * @param  mixed $paymentStatus
     * @return string
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
     *
     * @return string
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid()->toBase32());
    }
}