<?php

namespace App\Livewire;

use App\Enums\OrderType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\OrderService;
use App\Services\ReservationService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

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

    public function mount(): void
    {
        $this->reservation_date = now()->format('Y-m-d');
        $this->cart = session()->get('yemeni_cart', []);
    }

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
    }

    public function addToCart(int $itemId): void
    {
        $item = MenuItem::available()->find($itemId);

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

    #[Computed]
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
            session()->flash('error', __('messages.menu.empty'));

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
            'Pre-order Dishes: [%s] | Pre-order Total: €%.2f | Customer Notes: %s',
            $dishesSummary,
            $this->totalCartAmount,
            $this->special_requests
        ));

        $reservation = $reservationService->createReservation([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'party_size' => 2,
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

        $order = $orderService->createOrder([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'type' => OrderType::tryFrom($this->order_type) ?? OrderType::DINE_IN,
            'notes' => "Linked to Reservation: {$reservation->reference_code}",
        ], $orderItems);

        $this->clearCart();
        $this->isCartModalOpen = false;

        session()->flash(
            'success',
            sprintf(
                '%s Reservation: %s | Order: %s',
                __('messages.reservation.success'),
                $reservation->reference_code,
                $order->order_number
            )
        );

        $this->dispatch(
            'order-confirmed',
            ['ref' => $reservation->reference_code]
        );
    }

    public function render()
    {
        return view('livewire.menu-with-cart');
    }
}
