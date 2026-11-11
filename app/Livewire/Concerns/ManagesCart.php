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

        return 'yemeni_cart_'.md5($customerToken ?: 'guest');
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
        $current = (int) ($this->cart[$itemId]['quantity'] ?? 0);

        if ($current >= $max) {
            $this->notifyMaxQuantityReached($item->localized_name, $max);

            return;
        }

        $isFirstItem = empty($this->cart);

        if ($current > 0) {
            $this->cart[$itemId]['quantity'] = $current + 1;
        } else {
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->localized_name,
                'price' => (float) $item->price,
                'quantity' => 1,
            ];
        }

        $this->normalizeFreeDrinkPicks(
            $this->cart[$itemId],
            (int) $this->cart[$itemId]['quantity'],
        );

        $this->persistCart();
        $this->dispatch('cart-updated');

        $showedHint = false;
        $cfg = $this->freeDrinkCatalog['items'][$itemId] ?? null;

        if ($cfg !== null && $cfg['mode'] === 'choice') {
            $slots = $this->freeDrinkSlots(
                $cfg,
                (int) $this->cart[$itemId]['quantity'],
            );
            $used = (int) array_sum(
                (array) ($this->cart[$itemId]['free_drink']['picks'] ?? []),
            );

            if ($used < $slots) {
                $this->dispatch(
                    'notify',
                    title: __('messages.menu.free_drink_badge'),
                    message: __('messages.menu.free_drink_added_hint'),
                );

                $showedHint = true;
            }
        }

        if ($isFirstItem && ! $showedHint) {
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

        //   مزامنة المشروب مع الكمية الجديدة (تقتطع الفائض عند النقص)
        $this->normalizeFreeDrinkPicks($this->cart[$itemId], $newQty);

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

    /**
     *   كتالوج المشروبات المجانية — يُحسب مرة واحدة لكل القائمة.
     *
     * @return array{
     *     drinks: array<int, string>,
     *     items: array<int, array{mode:string, qty:int, policy:string, allowed:array<int,int>}>
     * }
     */
    #[Computed]
    public function freeDrinkCatalog(): array
    {
        // 1) المشروبات الصالحة: متاحة + تنتمي لتصنيف المشروبات
        $drinks = MenuItem::query()
            ->available()
            ->whereHas('category', fn ($q) => $q->where('is_drinks_category', true))
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (MenuItem $d) => [$d->id => $d->localized_name])
            ->all();

        // 2) الأطباق المؤهلة
        $items = MenuItem::query()
            ->available()
            ->where('offers_free_drink', true)
            ->get();

        $catalog = [];

        foreach ($items as $item) {
            // المسموح = تقاطع (ما ضبطه المسؤول) مع (المشروبات الصالحة)
            $allowed = array_values(array_intersect($item->configuredFreeDrinkIds(), array_keys($drinks)));

            // بلا مشروبات صالحة ⇒ لا شارة ولا اختيار (اتساق البطاقة مع السلة)
            if (empty($allowed)) {
                continue;
            }

            $catalog[$item->id] = [
                'mode' => count($allowed) === 1 ? 'auto' : 'choice',
                'qty' => max(1, (int) $item->free_drink_qty),
                'policy' => $item->freeDrinkPolicy(),
                'allowed' => $allowed,
            ];
        }

        return ['drinks' => $drinks, 'items' => $catalog];
    }

    /** 🔢 عدد الخانات المستحقة لطبق مع كمية (للاستخدام الداخلي). */
    protected function freeDrinkSlots(array $cfg, int $quantity): int
    {
        $qty = max(1, (int) ($cfg['qty'] ?? 1));

        return ($cfg['policy'] ?? MenuItem::FREE_DRINK_POLICY_PER_UNIT) === MenuItem::FREE_DRINK_POLICY_PER_UNIT
            ? $qty * max(1, $quantity)
            : $qty;
    }

    /**
     * 🧹 مزامنة اختيارات المشروب لعنصر سلة (لا استنساخ للإعداد).
     *    - auto   : نُخزّن الثابت تلقائياً بمقدار الخانات.
     *    - choice : نُصفّي الاختيارات مقابل المسموح، ونقتطع الفائض عند نقص المستحق.
     */
    protected function normalizeFreeDrinkPicks(array &$cartItem, int $quantity): void
    {
        $itemId = (int) ($cartItem['id'] ?? 0);
        $cfg = $this->freeDrinkCatalog['items'][$itemId] ?? null;

        // الطبق لم يعد مؤهلاً ⇒ نزع الاختيارات
        if ($cfg === null) {
            unset($cartItem['free_drink']);

            return;
        }

        $slots = $this->freeDrinkSlots($cfg, $quantity);

        // وضع الثابت: يُبنى من الإعداد الحالي
        if ($cfg['mode'] === 'auto') {
            $cartItem['free_drink'] = ['picks' => [$cfg['allowed'][0] => $slots]];

            return;
        }

        // وضع الاختيار: تصفية واقتطاع
        $picks = [];
        $remaining = $slots;

        foreach ((array) ($cartItem['free_drink']['picks'] ?? []) as $drinkId => $count) {
            $drinkId = (int) $drinkId;

            if ($remaining <= 0 || ! in_array($drinkId, $cfg['allowed'], true)) {
                continue;
            }

            $take = min((int) $count, $remaining);

            if ($take > 0) {
                $picks[$drinkId] = $take;
                $remaining -= $take;
            }
        }

        if (empty($picks)) {
            unset($cartItem['free_drink']);
        } else {
            $cartItem['free_drink'] = ['picks' => $picks];
        }
    }

    /**
     *   تغيير كمية مشروب مجاني (عدّاد +/−) بسقف إجمالي = المستحق.
     */
    public function changeFreeDrink(int $itemId, int $drinkId, int $delta): void
    {
        if (! isset($this->cart[$itemId])) {
            return;
        }

        $cfg = $this->freeDrinkCatalog['items'][$itemId] ?? null;

        // الثابت لا يتغيّر يدوياً، وغير المؤهّل لا يُقبل
        if ($cfg === null || $cfg['mode'] !== 'choice') {
            return;
        }

        if (! in_array($drinkId, $cfg['allowed'], true)) {
            return;
        }

        $slots = $this->freeDrinkSlots($cfg, (int) $this->cart[$itemId]['quantity']);

        $picks = (array) ($this->cart[$itemId]['free_drink']['picks'] ?? []);
        $current = (int) ($picks[$drinkId] ?? 0);
        $used = (int) array_sum($picks);

        // 🚫 السقف الإجمالي
        if ($delta > 0 && $used >= $slots) {
            return;
        }

        $new = max(0, $current + $delta);

        if ($new === 0) {
            unset($picks[$drinkId]);
        } else {
            $picks[$drinkId] = $new;
        }

        if (empty($picks)) {
            unset($this->cart[$itemId]['free_drink']);
        } else {
            $this->cart[$itemId]['free_drink'] = ['picks' => $picks];
        }

        $this->persistCart();
        $this->dispatch('cart-updated');
    }
}
