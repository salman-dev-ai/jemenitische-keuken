<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    /**
     * نسبة ضريبة المبيعات الهولندية (BTW 9% للوجبات والمشروبات    )
     */
    protected float $vatRate = 0.09;

    /**
     * إنشاء طلب جديد ومعالجة عناصره داخل المعاملة
     */
    public function createOrder(array $orderData, array $items): Order
    {
        if (empty($items)) {
            throw new Exception("لا يمكن إنشاء طلب بدون أطباق.");
        }

        return DB::transaction(function () use ($orderData, $items) {
            $subtotal = 0.0;
            $preparedItems = [];

            // 1. حساب الأسعار والمجموع الفرعي استناداً لأحدث أسعار المنيو في قاعدة البيانات
            foreach ($items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);

                if (! $menuItem->is_active) {
                    throw new Exception("الطبق ({$menuItem->name}) غير متاح للطلب حالياً.");
                }

                $quantity = (int) $item['quantity'];
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

            // 2. حساب الضريبة والإجمالي النهائي
            $tax = round($subtotal * $this->vatRate, 2);
            $total = round($subtotal + $tax, 2);

            // 3. تجهيز بيانات الطلب
            $orderData['order_number'] = $this->generateOrderNumber();
            $orderData['subtotal'] = $subtotal;
            $orderData['tax'] = $tax;
            $orderData['total'] = $total;
            $orderData['status'] = $orderData['status'] ?? OrderStatus::PENDING;

            // 4. حفظ الطلب والعناصر
            $order = Order::create($orderData);
            $order->items()->createMany($preparedItems);

            return $order;
        });
    }

    /**
     * توليد رقم طلب فريد ومرتب
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
