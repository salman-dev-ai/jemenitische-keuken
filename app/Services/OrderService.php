<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Exceptions\OrderException;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    protected float $vatRate;

    protected int $maxQuantityPerItem;

    public function __construct()
    {
        $this->vatRate = (float) config('orders.vat_rate', 0.09);
        $this->maxQuantityPerItem = (int) config('orders.max_quantity_per_item', 50);
    }

    /**
     * إنشاء طلب جديد مع كل عناصره.
     *
     * @throws OrderException
     */
    public function createOrder(array $orderData, array $items): Order
    {
        if (empty($items)) {
            throw OrderException::emptyCart();
        }

        // 1. التحقق من reservation_id إن وُجد
        $this->validateReservation($orderData['reservation_id'] ?? null);

        return DB::transaction(function () use ($orderData, $items) {
            // 2. جلب الأطباق بقفل لمنع تغيير السعر
            $menuItemIds = collect($items)->pluck('menu_item_id')->map(fn ($id) => (int) $id)->all();

            $menuItems = MenuItem::query()
                ->whereIn('id', $menuItemIds)
                ->available()
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 3. تجهيز العناصر + حساب الإجماليات
            [$preparedItems, $subtotal] = $this->prepareItems($items, $menuItems);

            if (empty($preparedItems)) {
                throw OrderException::emptyCart();
            }

            // 4. حساب الضريبة والإجمالي
            $tax   = round($subtotal * $this->vatRate, 2);
            $total = round($subtotal + $tax, 2);

            // 5. تجهيز بيانات الطلب
            $finalOrderData = Arr::only($orderData, [
                'customer_id',
                'reservation_id',
                'customer_name',
                'customer_phone',
                'customer_email',
                'type',
                'delivery_address',
                'delivery_city',
                'delivery_postal_code',
                'payment_method',
                'notes',
            ]);

            $finalOrderData['order_number']   = $this->generateOrderNumber();
            $finalOrderData['subtotal']       = $subtotal;
            $finalOrderData['tax']            = $tax;
            $finalOrderData['total']          = $total;
            $finalOrderData['status']         = $this->resolveStatus($orderData['status'] ?? null);
            $finalOrderData['payment_status'] = $orderData['payment_status'] ?? 'pending';

            // 6. الإنشاء
            $order = Order::create($finalOrderData);
            $order->items()->createMany($preparedItems);

            // 7. تسجيل للتدقيق
            Log::info('Order created', [
                'id'           => $order->id,
                'order_number' => $order->order_number,
                'customer_id'  => $order->customer_id,
                'type'         => $order->type,
                'total'        => $order->total,
                'items_count'  => count($preparedItems),
            ]);

            return $order;
        });
    }

    /**
     * تجهيز العناصر وحساب المجموع.
     *
     * @return array{0: array, 1: float}
     * @throws OrderException
     */
    protected function prepareItems(array $items, $menuItems): array
    {
        $subtotal      = 0.0;
        $preparedItems = [];

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

            if ($quantity < 1 || $quantity > $this->maxQuantityPerItem) {
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
     * التحقق من وجود الحجز إن وُجد reservation_id.
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
     * تحويل الحالة إلى Enum صالح.
     */
    protected function resolveStatus(mixed $status): OrderStatus
    {
        if ($status instanceof OrderStatus) {
            return $status;
        }

        if (is_string($status) && $resolved = OrderStatus::tryFrom($status)) {
            return $resolved;
        }

        return OrderStatus::PENDING;
    }

    /**
     * توليد رقم طلب فريد باستخدام ULID.
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid()->toBase32());
    }
}