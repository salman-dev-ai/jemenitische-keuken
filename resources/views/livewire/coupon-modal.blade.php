<?php

use App\Models\Customer;
use App\Models\Promotion;
use App\Services\CouponService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, on};

state([
    'show' => false,
    'name' => '',
    'phone' => '',
    'promotionId' => null,
]);

on(['open-coupon-modal' => function (int $promotionId) {
    $this->promotionId = $promotionId;
    $this->name = '';
    $this->phone = '';

    $customer = $this->currentCustomer();

    if ($customer) {
        $this->claimFor($customer);
        return;
    }

    $this->show = true;
}]);

$submitGuest = function () {
    $validated = $this->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
    ]);

    $customer = Customer::create([
        'name' => $validated['name'],
        'phone' => $validated['phone'],
        'remember_token' => Str::random(60),
    ]);

    Cookie::queue('remember_customer_token', $customer->remember_token, 60 * 24 * 365);

    $this->claimFor($customer);
};

$claimFor = function (Customer $customer) {
    $promotion = Promotion::with('coupon')->findOrFail($this->promotionId);

    app(CouponService::class)->claim($customer, $promotion->coupon);

    $this->show = false;

    $this->redirect(route('cart'), navigate: true);
};

$currentCustomer = function (): ?Customer {
    $token = request()->cookie('remember_customer_token');

    return $token ? Customer::where('remember_token', $token)->first() : null;
};

?>

<div>
    @if ($show)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
            wire:click.self="$set('show', false)"
        >
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">

                <h3 class="mb-2 text-xl font-bold text-amber-900">
                    {{ __('offers.modal.title') }}
                </h3>
                <p class="mb-6 text-sm text-gray-600">
                    {{ __('offers.modal.subtitle') }}
                </p>

                <form wire:submit="submitGuest" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.name') }}
                        </label>
                        <input type="text" wire:model="name"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                        @error('name') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.phone') }}
                        </label>
                        <input type="tel" wire:model="phone"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                        @error('phone') <span class="mt-1 text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="$set('show', false)"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                            {{ __('offers.modal.cancel') }}
                        </button>
                        <button type="submit"
                                class="flex-1 rounded-lg bg-amber-800 px-4 py-2.5 font-medium text-white hover:bg-amber-900"
                                wire:loading.attr="disabled">
                            {{ __('offers.modal.submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>