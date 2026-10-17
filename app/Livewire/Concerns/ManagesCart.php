<?php

namespace App\Livewire\Concerns;

use App\Models\MenuItem;
use App\Models\RestaurantSetting;
use Livewire\Attributes\Computed;

/**
 * Trait لعمليات سلة الطلبات.
 */
trait ManagesCart
{
    /** 🛒 السلة */
    public array $cart = [];

    /** 🗝️ مفتاح الجلسة لحفظ السلة */
    protected const CART_SESSION_KEY = 'yemeni_cart';

    /**
     * 📈 الحد الأقصى لكمية الطبق الواحد.
     *
     * يُقرأ من إعدادات المطعم (الأدمن: الحد الأقصى للكمية لكل طبق)
     * وليس رقماً ثابتاً — فأي تغيير في الأدمن يُطبَّق فوراً على صفحة المستخدم.
     */
    #[Computed]
    public function maxQuantityPerItem(): int
    {
        return max(1, RestaurantSetting::maxQuantityPerItem());
    }

    protected function cartSessionKey(): string
    {
        $customerToken = request()->cookie('remember_customer_token');

        return 'yemeni_cart_' . md5($customerToken ?: 'guest');
    }

    public function initializeCart(): void
    {
        $this->cart = session()->get($this->cartSessionKey(), []);
    }

    /**
     * 🆕 إضافة عنصر إلى السلة.
     */
    public function addToCart(int $itemId): void
    {
        $item = MenuItem::available()->find($itemId);

        if (! $item) {
            return;
        }

        $max = $this->maxQuantityPerItem();
        $current = $this->cart[$itemId]['quantity'] ?? 0;

        // 🚫 وصل العميل للحد الأقصى → نُخبره بدل التجاهل الصامت
        if ($current >= $max) {
            $this->notifyMaxQuantityReached($item->localized_name, $max);

            return;
        }

        // 🎈 هل هذه أول إضافة للسلة؟ (نُظهر ترحيباً تفاعلياً للعميل في أول طبق فقط)
        $isFirstItem = empty($this->cart);

        if ($current > 0) {
            $this->cart[$itemId]['quantity'] = $current + 1;
        } else {
            $this->cart[$itemId] = [
                'id'       => $item->id,
                'name'     => $item->localized_name,
                'price'    => (float) $item->price,
                'quantity' => 1,
            ];
        }

        $this->persistCart();
        $this->dispatch('cart-updated');

        if ($isFirstItem) {
            $this->dispatch(
                'notify',
                title: __('cart.first_add.title'),
                message: __('cart.first_add.message', ['name' => $item->localized_name]),
            );
        }
    }

    /**
     * 🆕 تحديث كمية عنصر (بـ delta موجب أو سالب).
     */
    public function updateQuantity(int $itemId, int $delta): void
    {
        if (! isset($this->cart[$itemId])) {
            return;
        }

        $max = $this->maxQuantityPerItem();
        $newQty = $this->cart[$itemId]['quantity'] + $delta;

        if ($newQty <= 0) {
            unset($this->cart[$itemId]);
        } elseif ($newQty > $max) {
            $this->notifyMaxQuantityReached($this->cart[$itemId]['name'], $max);

            return;
        } else {
            $this->cart[$itemId]['quantity'] = $newQty;
        }

        $this->persistCart();
        $this->dispatch('cart-updated');
    }

    /**
     * 🆕 إزالة عنصر من السلة.
     */
    public function removeFromCart(int $itemId): void
    {
        if (! isset($this->cart[$itemId])) {
            return;
        }

        unset($this->cart[$itemId]);
        $this->persistCart();
        $this->dispatch('cart-updated');
    }

    /**
     * 🆕 تفريغ السلة بالكامل.
     */
    public function clearCart(): void
    {
        $this->cart = [];
        session()->forget(self::CART_SESSION_KEY);
        session()->forget($this->cartSessionKey());
        $this->dispatch('cart-updated');
    }

    /**
     * 🆕 حفظ السلة في الجلسة.
     */
    protected function persistCart(): void
    {
        session()->put($this->cartSessionKey(), $this->cart);
    }

    /**
     * 🔔 إخبار العميل بأنه وصل للحد الأقصى لكمية الطبق.
     */
    protected function notifyMaxQuantityReached(string $itemName, int $max): void
    {
        $this->dispatch(
            'notify',
            title: __('cart.max_quantity.title'),
            message: __('cart.max_quantity.message', ['name' => $itemName, 'max' => $max]),
        );
    }

    #[Computed]
    public function totalCartCount(): int
    {
        return (int) array_sum(array_column($this->cart, 'quantity'));
    }

    #[Computed]
    public function totalCartAmount(): float
    {
        return (float) collect($this->cart)->sum(
            fn ($item) => $item['price'] * $item['quantity']
        );
    }

    #[Computed]
    public function isCartEmpty(): bool
    {
        return empty($this->cart);
    }
}
