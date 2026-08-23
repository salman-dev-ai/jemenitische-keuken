<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\MenuItem;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected float $vatRate = 0.09;

    public function createOrder(array $orderData, array $items): Order
    {
        if (empty($items)) {
            throw new Exception(__('messages.errors.empty_cart', default: 'لا يمكن إنشاء طلب بدون أطباق.'));
        }

        return DB::transaction(function () use ($orderData, $items) {
            $menuItemIds = collect($items)->pluck('menu_item_id')->all();

            // ✅ استعلام واحد لجلب جميع الأطباق مرة واحدة
            $menuItems = MenuItem::query()
                ->whereIn('id', $menuItemIds)
                ->available()
                ->keyBy('id')
                ->get();

            $subtotal = 0.0;
            $preparedItems = [];

            foreach ($items as $item) {
                $menuItemId = $item['menu_item_id'];

                if (! $menuItems->has($menuItemId)) {
                    throw new Exception(
                        sprintf(__('messages.errors.menu_item_unavailable', default: 'الطبق المطلوب غير متاح حالياً.'), $menuItemId)
                    );
                }

                $menuItem = $menuItems->get($menuItemId);

                $quantity = (int) $item['quantity'];
                if ($quantity < 1) {
                    continue;
                }

                $unitPrice = (float) $menuItem->price;
                $itemTotal = round($unitPrice * $quantity, 2);

                $subtotal += $itemTotal;

                $preparedItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ];
            }

            if (empty($preparedItems)) {
                throw new Exception(__('messages.errors.empty_cart', default: 'السلة فارغة.'));
            }

            $tax = round($subtotal * $this->vatRate, 2);
            $total = round($subtotal + $tax, 2);

            $orderData['order_number'] = $this->generateOrderNumber();
            $orderData['subtotal'] = $subtotal;
            $orderData['tax'] = $tax;
            $orderData['total'] = $total;
            $orderData['status'] = $orderData['status'] ?? OrderStatus::PENDING;

            $order = Order::create($orderData);
            $order->items()->createMany($preparedItems);

            return $order;
        });
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
