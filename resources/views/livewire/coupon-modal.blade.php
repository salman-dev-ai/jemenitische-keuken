<?php

use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, on};

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * Coupon Modal — Volt Component
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الدور:
 *    - التقاط حدث `open-coupon-modal` من offers-section
 *    - للعميل المسجّل (كوكي): إطلاق حدث مباشر لـ menu-with-cart
 *    - للعميل الجديد: عرض نموذج تسجيل → إنشاء Customer → إطلاق الحدث
 *
 * 📝 حقول التسجيل:
 *    إلزامي: name, phone
 *    اختياري: email, address, city, postal_code
 *
 * ✅ لا redirect — SPA حقيقي
 * ✅ لا claim() مبكر — claim يحدث في OrderService::checkout
 * ✅ التواصل مع menu-with-cart عبر browser event
 * ═══════════════════════════════════════════════════════════════════════════
 */

state([
    'show'        => false,
    'name'        => '',
    'phone'       => '',
    'email'       => '',
    'address'     => '',
    'city'        => '',
    'postal_code' => '',
    'couponCode'  => '',
]);

/**
 * 📥 التقاط حدث "استخدم الآن" من بطاقات العروض.
 */
on(['open-coupon-modal' => function (int $promotionId, string $action = 'use') {
    $promotion = Promotion::with('coupon')->find($promotionId);

    if (! $promotion || ! $promotion->coupon) {
        return;
    }

    $this->couponCode = $promotion->coupon->code;
    $this->resetForm();

    // عميل مسجّل
    if ($this->currentCustomer()) {
        if ($action === 'copy') {
            // اطلب النسخ من الـ frontend
            $this->dispatch('coupon-copied', code: $this->couponCode);
        } else {
            // اطبق الكوبون على السلة
            $this->applyToCart();
        }
        return;
    }

    // عميل جديد؟ → افتح نموذج التسجيل (سواء copy أو use)
    $this->show = true;
}]);

/**
 * 📝 حفظ تسجيل العميل الجديد.
 */
$submitGuest = function (): void {
    $validated = $this->validate([
        'name' => 'required|string|min:3|max:255',
        'phone' => [
            'required',
            'string',
            'min:7',
            'max:30',
            'regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/',
        ],
        'email'       => 'nullable|email|max:150',
        'address'     => 'nullable|string|max:255',
        'city'        => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
    ]);

    $customer = Customer::create([
        'name'           => $validated['name'],
        'phone'          => $this->normalizePhone($validated['phone']),
        'email'          => $validated['email'] ?: null,
        'address'        => $validated['address'] ?: null,
        'city'           => $validated['city'] ?: null,
        'postal_code'    => $validated['postal_code'] ?: null,
        'remember_token' => Str::random(60),
    ]);

    Cookie::queue(
        'remember_customer_token',
        $customer->remember_token,
        60 * 24 * 90,
    );

    $this->applyToCart();
};

/**
 * 🚀 إطلاق حدث التطبيق + إغلاق النافذة.
 */
$applyToCart = function (): void {
    $this->show = false;
    $this->dispatch('apply-coupon-to-cart', code: $this->couponCode);
};

/**
 * 👤 قراءة العميل الحالي من الكوكي.
 */
$currentCustomer = function (): ?Customer {
    $token = request()->cookie('remember_customer_token');

    return $token
        ? Customer::where('remember_token', $token)->first()
        : null;
};

/**
 * 🔄 إعادة تعيين حقول النموذج.
 */
$resetForm = function (): void {
    $this->name        = '';
    $this->phone       = '';
    $this->email       = '';
    $this->address     = '';
    $this->city        = '';
    $this->postal_code = '';
};

/**
 * 📞 تطبيع رقم الهاتف.
 */
$normalizePhone = function (string $phone): string {
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $ascii  = ['0','1','2','3','4','5','6','7','8','9'];

    return preg_replace('/[\s\-\(\)]+/', '', str_replace($arabic, $ascii, $phone)) ?: '';
};

?>

<div>
    @if ($show)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            wire:click.self="$set('show', false)"
            role="dialog"
            aria-modal="true"
            aria-labelledby="coupon-modal-title"
            x-data
            @keydown.window.escape="$wire.set('show', false)"
        >
            <div class="w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl animate-fade-in"
                 dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">

                {{-- ═══ رأس النافذة ═══ --}}
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100">
                        <svg class="h-6 w-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="coupon-modal-title" class="text-xl font-bold text-amber-900">
                            {{ __('offers.modal.title') }}
                        </h3>
                        <p class="mt-0.5 text-sm text-gray-600">
                            {{ __('offers.modal.subtitle') }}
                        </p>
                    </div>
                </div>

                {{-- ═══ الكود ═══ --}}
                <div class="mb-5 flex items-center justify-between gap-2 rounded-xl border-2 border-dashed border-amber-300 bg-amber-50/60 px-4 py-3">
                    <code class="font-mono text-base font-bold tracking-wider text-amber-900" dir="ltr">
                        {{ $couponCode }}
                    </code>
                    <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                {{-- ═══ النموذج ═══ --}}
                <form wire:submit="submitGuest" class="space-y-4">

                    {{-- الاسم (إلزامي) --}}
                    <div>
                        <label for="coupon-name" class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.name') }}
                            <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <input id="coupon-name" type="text" wire:model="name" autocomplete="name"
                            class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                   @error('name') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                   @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                        @error('name') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- الهاتف (إلزامي) --}}
                    <div>
                        <label for="coupon-phone" class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.phone') }}
                            <span class="text-rose-500" aria-hidden="true">*</span>
                        </label>
                        <input id="coupon-phone" type="tel" wire:model="phone" dir="ltr" autocomplete="tel"
                            placeholder="+31 6 1234 5678"
                            class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                   @error('phone') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                   @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                        @error('phone') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- البريد الإلكتروني (اختياري) --}}
                    <div>
                        <label for="coupon-email" class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.email') }}
                            <span class="text-gray-400 text-xs font-normal">({{ __('offers.modal.optional') }})</span>
                        </label>
                        <input id="coupon-email" type="email" wire:model="email" dir="ltr" autocomplete="email"
                            placeholder="example@domain.com"
                            class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                   @error('email') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                   @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                        @error('email') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- العنوان (اختياري) --}}
                    <div>
                        <label for="coupon-address" class="mb-1 block text-sm font-medium text-gray-700">
                            {{ __('offers.modal.address') }}
                            <span class="text-gray-400 text-xs font-normal">({{ __('offers.modal.optional') }})</span>
                        </label>
                        <input id="coupon-address" type="text" wire:model="address" autocomplete="street-address"
                            placeholder="{{ __('offers.modal.address_placeholder') }}"
                            class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                   @error('address') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                   @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                        @error('address') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- المدينة + الرمز البريدي (جنباً إلى جنب) --}}
                    <div class="grid grid-cols-2 gap-3">

                        {{-- المدينة (اختياري) --}}
                        <div>
                            <label for="coupon-city" class="mb-1 block text-sm font-medium text-gray-700">
                                {{ __('offers.modal.city') }}
                                <span class="text-gray-400 text-xs font-normal">({{ __('offers.modal.optional') }})</span>
                            </label>
                            <input id="coupon-city" type="text" wire:model="city" autocomplete="address-level2"
                                placeholder="{{ __('offers.modal.city_placeholder') }}"
                                class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                       @error('city') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                       @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                            @error('city') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        {{-- الرمز البريدي (اختياري) --}}
                        <div>
                            <label for="coupon-postal-code" class="mb-1 block text-sm font-medium text-gray-700">
                                {{ __('offers.modal.postal_code') }}
                                <span class="text-gray-400 text-xs font-normal">({{ __('offers.modal.optional') }})</span>
                            </label>
                            <input id="coupon-postal-code" type="text" wire:model="postal_code" dir="ltr"
                                autocomplete="postal-code"
                                placeholder="1012 NK"
                                class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
                                       @error('postal_code') border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                                       @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror">
                            @error('postal_code') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    {{-- ═══ الأزرار ═══ --}}
                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="$set('show', false)"
                            class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 transition hover:bg-gray-50">
                            {{ __('offers.modal.cancel') }}
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitGuest"
                            class="flex-1 rounded-lg bg-amber-800 px-4 py-2.5 font-medium text-white transition hover:bg-amber-900 disabled:cursor-wait disabled:opacity-70">
                            <span wire:loading.remove wire:target="submitGuest">
                                {{ __('offers.modal.submit') }}
                            </span>
                            <span wire:loading wire:target="submitGuest"
                                  class="inline-block h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin align-middle"></span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif
</div>
