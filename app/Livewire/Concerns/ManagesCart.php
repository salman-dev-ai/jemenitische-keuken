<?php

namespace App\Livewire\Concerns;

use App\Models\MenuItem;
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

    /** 📈 الحد الأقصى لكمية الطبق الواحد */
    protected const CART_MAX_QUANTITY = 50;

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

        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['quantity'] = min(
                $this->cart[$itemId]['quantity'] + 1,
                self::CART_MAX_QUANTITY
            );
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
    }

    /**
     * 🆕 تحديث كمية عنصر (بـ delta موجب أو سالب).
     */
    public function updateQuantity(int $itemId, int $delta): void
    {
        if (! isset($this->cart[$itemId])) {
            return;
        }

        $newQty = $this->cart[$itemId]['quantity'] + $delta;

        if ($newQty <= 0) {
            unset($this->cart[$itemId]);
        } elseif ($newQty <= self::CART_MAX_QUANTITY) {
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
