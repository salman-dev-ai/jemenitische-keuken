<?php

namespace App\Livewire;

use App\Enums\OrderType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Services\ReservationService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Lazy]
class MenuWithCart extends Component
{
    public string $selectedCategorySlug = 'all';

    public array $cart = [];

    public bool $isCartModalOpen = false;

    #[Validate('required|string|min:3|max:100')]
    public string $customer_name = '';

    #[Validate('required|string|min:8|max:30')]
    public string $customer_phone = '';

    #[Validate('nullable|email|max:150')]
    public string $customer_email = '';

    #[Validate('required|date|after_or_equal:today')]
    public string $reservation_date = '';

    #[Validate('required')]
    public string $reservation_time = '19:30';

    public string $order_type = 'dine_in';

    #[Validate('nullable|string|max:500')]
    public string $special_requests = '';

    #[Validate('required|integer|min:1|max:20')]
    public int $party_size = 2;

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->cart = session()->get('yemeni_cart', []);
    }

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
        unset($this->filteredItems);
    }

    public function addToCart(int $itemId): void
    {
        // استخدام البيانات المحمّلة في الـ Computed بدلاً من DB query جديدة
        $item = $this->filteredItems->firstWhere('id', $itemId);

        if (! $item) {
            return;
        }

        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['quantity']++;
        } else {
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->localized_name,
                'price' => (float) $item->price,
                'quantity' => 1,
            ];
        }

        session()->put('yemeni_cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function updateQuantity(int $itemId, int $delta): void
    {
        if (! isset($this->cart[$itemId])) {
            return;
        }

        $this->cart[$itemId]['quantity'] += $delta;

        if ($this->cart[$itemId]['quantity'] <= 0) {
            unset($this->cart[$itemId]);
        }

        session()->put('yemeni_cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function removeFromCart(int $itemId)
    {
        if (isset($this->cart[$itemId])) {
            unset($this->cart[$itemId]);
            session()->put('yemeni_cart', $this->cart);
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        session()->forget('yemeni_cart');
    }

    #[Computed]
    public function totalCartCount(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    #[Computed]
    public function totalCartAmount(): float
    {
        return collect($this->cart)->sum(
            fn ($item) => $item['price'] * $item['quantity']
        );
    }

    #[Computed(persist: true)]
    public function categories()
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => fn ($q) => $q->available()])
            ->orderBy('sort_order', 'asc')
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
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    public function checkout(
        ReservationService $reservationService,
        OrderService $orderService
    ): void {
        if (empty($this->cart)) {
            $this->dispatch('notify',
                title: __('messages.menu.empty'),
                message: '',
            );

            return;
        }

        $validated = $this->validate();

        $dishesSummary = collect($this->cart)->map(function ($item) {
            return sprintf(
                '%dx %s (€%.2f)',
                $item['quantity'],
                $item['name'],
                $item['price'] * $item['quantity']
            );
        })->implode(', ');

        $combinedNotes = trim(sprintf(
            'Pre-order: [%s] | Total: €%.2f | Notes: %s',
            $dishesSummary,
            $this->totalCartAmount,
            $this->special_requests
        ));

        // 1. إنشاء الحجز
        $reservation = $reservationService->createReservation([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'party_size' => $this->party_size,
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'special_requests' => $combinedNotes,
        ]);

        $orderItems = collect($this->cart)->map(
            fn ($item) => [
                'menu_item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]
        )->all();

        // 2. إنشاء الطلب المربوط بالحجز
        $orderService->createOrder([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'type' => OrderType::tryFrom($this->order_type) ?? OrderType::DINE_IN,
            'notes' => "Linked to Reservation: {$reservation->reference_code}",
        ], $orderItems);

        // 3. تنظيف حالة السلة وإغلاق النافذة
        $this->clearCart();
        $this->isCartModalOpen = false;
        $this->reset(['customer_name', 'customer_phone', 'customer_email', 'special_requests', 'party_size']);

        // 4. إشعار النجاح
        $this->dispatch('notify',
            title: __('messages.notifications.order_title'),
            message: __('messages.notifications.order_message'),
        );
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.menu-with-cart');
    }

    public function render(): View
    {
        return view('livewire.menu-with-cart');
    }
}
