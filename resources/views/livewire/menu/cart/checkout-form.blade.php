{{-- نموذج الحجز: الحقول + التوصيل + تذكرني + الأخطاء + الكوبون + الملخص + زر الإرسال --}}
@php
    $inputClass = 'w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all';
    $pricing = $this->pricing;
@endphp

{{--
    إرسال الطلب + فتح واتساب في تبويب جديد:
    1) عند الضغط نفتح تبويباً فارغاً فوراً (داخل حدث المستخدم) كي لا يحجبه المتصفح.
    2) ننتظر نتيجة checkout() من الخادم: رابط wa.me عند النجاح أو null عند أي فشل.
    3) نجاح ⇒ نوجّه التبويب إلى واتساب. فشل ⇒ نغلق التبويب الفارغ ويبقى العميل في صفحته.
    4) إن حجب المتصفح التبويب نفتح واتساب في الصفحة الحالية كحل بديل.
--}}
<form
    x-data="{
        async submit() {
            const tab = window.open('', '_blank');
            let url = null;

            try {
                url = await this.$wire.checkout();
            } finally {
                const isValid = typeof url === 'string' && url.startsWith('https://wa.me/');

                if (isValid && tab && !tab.closed) {
                    tab.opener = null;
                    tab.location.href = url;
                } else if (isValid) {
                    window.location.href = url;
                } else if (tab && !tab.closed) {
                    tab.close();
                }
            }
        },
    }"
    @submit.prevent="submit()"
    class="space-y-4 pt-4 border-t border-stone-200">
    <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-1 flex items-center gap-2">
        <x-lucide-clipboard-list class="w-4 h-4" aria-hidden="true" />
        {{ __('messages.reservation.contactDetails') }}
    </h4>

    {{-- بيانات التواصل --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
            <label for="customer_name" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.fullName') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="customer_name" type="text" wire:model="customer_name" required autocomplete="name"
                placeholder="{{ __('messages.reservation.fullNamePlaceholder') }}" class="{{ $inputClass }}">
            @error('customer_name') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="customer_phone" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.phone') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="customer_phone" type="tel" wire:model="customer_phone" required dir="ltr"
                autocomplete="tel" placeholder="+31 6 1234 5678" class="{{ $inputClass }}">
            @error('customer_phone') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="customer_email" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.email') ?? __('messages.contact.form.email') }}
            </label>
            <input id="customer_email" type="email" wire:model="customer_email" autocomplete="email"
                placeholder="example@domain.com" class="{{ $inputClass }}">
            @error('customer_email') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- التاريخ والوقت --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="reservation_date" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.reservationDate') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="reservation_date" type="date" wire:model="reservation_date" required
                min="{{ now()->toDateString() }}" class="{{ $inputClass }}">
            @error('reservation_date') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="reservation_time" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.preferredTime') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="reservation_time" type="time" wire:model="reservation_time" required
                class="{{ $inputClass }}">
            @error('reservation_time') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- نوع الخدمة وعدد الأشخاص --}}
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="order_type" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.serviceType') }}
            </label>
            {{-- wire:model.live يحدّث $wire.order_type فوراً في المتصفح، فيظهر قسم التوصيل بلا انتظار --}}
            <select id="order_type" wire:model.live="order_type"
                class="{{ $inputClass }} appearance-none @error('order_type') border-rose-300 ring-1 ring-rose-200 @enderror">
                <option value="dine_in">{{ __('messages.reservation.dineIn') }}</option>
                <option value="pickup">{{ __('messages.reservation.takeaway') }}</option>
                <option value="delivery">{{ __('messages.reservation.delivery') }}</option>
            </select>
            @error('order_type') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="cart-party-size" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.reservation.partySize') }}
            </label>
            <input id="cart-party-size" type="number" wire:model="party_size" min="1" max="20" required
                class="{{ $inputClass }}">
            @error('party_size') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- حقول التوصيل: تظهر فوراً عند اختيار "توصيل" (Alpine يقرأ $wire.order_type بلا رحلة خادم) --}}
    <div x-show="$wire.order_type === 'delivery'" x-cloak x-transition
        class="space-y-3 p-3 rounded-2xl border border-amber-200 bg-amber-50/50">
        <h5 class="font-bold text-xs text-amber-800 uppercase tracking-wider flex items-center gap-2">
            <x-lucide-truck-electric class="w-4 h-4" aria-hidden="true" />
            {{ __('messages.remember.delivery_details') }}
        </h5>

        <div>
            <label for="delivery_address" class="block text-xs font-bold text-stone-700 mb-1.5">
                {{ __('messages.remember.address') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="delivery_address" type="text" wire:model="delivery_address" autocomplete="street-address"
                placeholder="{{ __('messages.remember.address_placeholder') }}"
                class="{{ $inputClass }} @error('delivery_address') border-rose-300 @enderror">
            @error('delivery_address') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="delivery_city" class="block text-xs font-bold text-stone-700 mb-1.5">
                    {{ __('messages.remember.city') }} <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input id="delivery_city" type="text" wire:model="delivery_city" autocomplete="address-level2"
                    placeholder="{{ __('messages.remember.city_placeholder') }}"
                    class="{{ $inputClass }} @error('delivery_city') border-rose-300 @enderror">
                @error('delivery_city') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="delivery_postal_code" class="block text-xs font-bold text-stone-700 mb-1.5">
                    {{ __('messages.remember.postal_code') }} <span class="text-rose-500" aria-hidden="true">*</span>
                </label>
                <input id="delivery_postal_code" type="text" wire:model="delivery_postal_code" dir="ltr"
                    autocomplete="postal-code" placeholder="{{ __('messages.remember.postal_placeholder') }}"
                    class="{{ $inputClass }} @error('delivery_postal_code') border-rose-300 @enderror">
                @error('delivery_postal_code') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div>
        <label for="special_requests" class="block text-xs font-bold text-stone-700 mb-1.5">
            {{ __('messages.reservation.specialRequests') }}
        </label>
        <textarea id="special_requests" wire:model="special_requests" rows="2"
            placeholder="{{ __('messages.reservation.requestsPlaceholderMenu') }}"
            class="{{ $inputClass }} resize-none"></textarea>
    </div>

    {{-- تذكرني + لست أنا --}}
    <div class="space-y-3 pt-2">
        @if ($isReturningCustomer)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm"
                wire:key="returning-customer-banner">
                <x-lucide-user-check class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" aria-hidden="true" />
                <div class="flex-1">
                    <strong class="block font-bold text-amber-900">{{ __('messages.remember.welcome_back') }}</strong>
                    <span class="text-amber-800">{{ __('messages.remember.filled_automatically') }}</span>
                </div>
                <button type="button"
                    @click="window.dispatchEvent(new CustomEvent('confirm-action', { detail: {
                        title: @js(__('messages.remember.confirm_title')),
                        message: @js(__('messages.remember.confirm_forget')),
                        confirmLabel: @js(__('messages.remember.confirm_yes')),
                        cancelLabel: @js(__('messages.confirm.cancel')),
                        onConfirm: () => $wire.forgetCustomer(),
                    } }))"
                    wire:loading.attr="disabled" wire:target="forgetCustomer"
                    class="shrink-0 rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold text-amber-700 transition hover:border-amber-400 hover:text-amber-900 disabled:cursor-wait disabled:opacity-50">
                    <span wire:loading.remove wire:target="forgetCustomer">{{ __('messages.remember.not_you') }}</span>
                    <span wire:loading wire:target="forgetCustomer" class="inline-flex items-center gap-1">
                        <x-lucide-loader-2 class="w-3 h-3 animate-spin" aria-hidden="true" />
                    </span>
                </button>
            </div>
        @endif

        <label for="remember_me"
            class="flex cursor-pointer items-center gap-3 rounded-xl p-2 transition hover:bg-stone-50">
            <input id="remember_me" type="checkbox" wire:model="remember_me"
                class="h-5 w-5 rounded-md border-stone-300 text-[#E07513] focus:ring-2 focus:ring-[#E07513]/30">
            <span class="flex-1 text-sm font-semibold text-stone-700">{{ __('messages.remember.checkbox') }}
                                <x-lucide-save-check class="ml-1 inline  font-semibold h-5 w-5 text-[#6B1F2B]" aria-hidden="true" />

            </span>
        </label>
    </div>

    {{-- ملخص الأخطاء --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-3 space-y-2" role="alert">
            <div class="flex items-center gap-2">
                <x-lucide-alert-octagon class="w-4 h-4 text-rose-600 shrink-0" aria-hidden="true" />
                <span class="font-black text-xs text-rose-900 uppercase tracking-wider">
                    {{ $errors->count() }} {{ __('messages.order.validation_error') }}
                </span>
            </div>
            <ol class="list-decimal list-inside space-y-0.5 ps-1">
                @foreach ($errors->all() as $msg)
                    <li class="text-xs text-rose-800 leading-relaxed">{{ $msg }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    {{-- قسم الكوبون (تطبيق/إزالة = حساب على الخادم → Livewire) --}}
    <div class="pt-4 border-t border-stone-200 mt-6 space-y-3">
        <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-1 flex items-center gap-2">
            <x-lucide-ticket-percent class="w-4 h-4" aria-hidden="true" />
            {{ __('cart.coupon.section_title') }}
        </h4>

        @if ($this->hasAppliedCoupon())
            <div class="flex items-center justify-between gap-3 p-3 rounded-xl border-2 border-emerald-200 bg-emerald-50">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                        <x-lucide-check class="w-5 h-5" aria-hidden="true" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-black text-sm text-emerald-900 truncate" dir="ltr">{{ $appliedCouponCode }}</div>
                        <div class="text-xs text-emerald-700">
                            {{ __('cart.coupon.saved') }}
                            <span class="font-black">€{{ number_format($appliedCouponDiscount, 2) }}</span>
                        </div>
                    </div>
                </div>
                <button type="button" wire:click="removeCoupon" wire:loading.attr="disabled"
                    wire:target="removeCoupon"
                    class="shrink-0 w-8 h-8 rounded-lg bg-white text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors disabled:opacity-50"
                    title="{{ __('cart.coupon.remove') }}" aria-label="{{ __('cart.coupon.remove') }}">
                    <span wire:loading.remove wire:target="removeCoupon">
                        <x-lucide-x class="w-4 h-4" aria-hidden="true" />
                    </span>
                    <span wire:loading wire:target="removeCoupon"
                        class="w-4 h-4 border-2 border-rose-500/30 border-t-rose-600 rounded-full animate-spin"></span>
                </button>
            </div>
        @else
            <div class="flex items-stretch gap-2">
                <input type="text" wire:model="couponCode" wire:keydown.enter.prevent="applyCoupon"
                    placeholder="{{ __('cart.coupon.placeholder') }}" dir="ltr" autocomplete="off" spellcheck="false"
                    aria-label="{{ __('cart.coupon.section_title') }}"
                    class="flex-1 min-w-0 px-3 py-2.5 rounded-xl bg-white border text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all uppercase tracking-wider font-bold @error('couponCode') border-rose-300 @else border-stone-200 @enderror">

                <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon"
                    class="px-4 py-2.5 rounded-xl bg-[#2C0D0A] hover:bg-[#E07513] text-white font-bold text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-wait shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                    <span wire:loading.remove wire:target="applyCoupon">{{ __('cart.coupon.apply') }}</span>
                    <span wire:loading wire:target="applyCoupon"
                        class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                </button>
            </div>

            @if ($couponError)
                <div class="flex items-start gap-2 p-2.5 rounded-xl border border-rose-200 bg-rose-50 text-xs" role="alert">
                    <x-lucide-alert-circle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" aria-hidden="true" />
                    <span class="text-rose-800 leading-relaxed">{{ $couponError }}</span>
                </div>
            @endif
        @endif
    </div>

    <div class="pt-4 border-t border-stone-200 mt-6 space-y-4">
        {{-- ملخص السعر --}}
        <div class="bg-[#FAF4ED] p-4 rounded-xl border border-[#E07513]/25 space-y-2.5">
            <div class="flex justify-between items-center text-sm">
                <span class="text-stone-600">{{ __('cart.summary.subtotal') }}</span>
                <span class="font-bold text-stone-800">€{{ number_format($pricing->subtotal, 2) }}</span>
            </div>

            @if ($pricing->discount > 0)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-emerald-700 flex items-center gap-1.5">
                        <x-lucide-ticket-percent class="w-3.5 h-3.5" aria-hidden="true" />
                        <span>{{ __('cart.summary.discount') }}</span>
                        <span class="text-xs font-bold text-emerald-600" dir="ltr">({{ $appliedCouponCode }})</span>
                    </span>
                    <span class="font-bold text-emerald-600">−€{{ number_format($pricing->discount, 2) }}</span>
                </div>

                <div class="flex justify-between items-center text-sm pb-2 border-b border-[#E07513]/15">
                    <span class="text-stone-600">{{ __('cart.summary.after_discount') }}</span>
                    <span class="font-bold text-stone-800">€{{ number_format($pricing->taxableAmount, 2) }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center text-sm">
                <span class="text-stone-600 flex items-center gap-1.5">
                    <span>{{ __('cart.summary.vat') }}</span>
                    <span class="text-xs text-stone-400 font-normal">({{ number_format($pricing->vatRate, 0) }}%)</span>
                </span>
                <span class="font-bold text-stone-800">€{{ number_format($pricing->vat, 2) }}</span>
            </div>

            <div class="flex justify-between items-center pt-2.5 border-t-2 border-[#E07513]/30">
                <span class="text-base font-black text-[#2C0D0A]">{{ __('cart.summary.total') }}</span>
                <span class="text-xl font-black text-[#E07513]">€{{ number_format($pricing->total, 2) }}</span>
            </div>
        </div>

        {{-- زر الإرسال: يستدعي submit() في الأعلى الذي يفتح واتساب في تبويب جديد بعد نجاح الطلب --}}
        <button type="submit" wire:loading.attr="disabled" wire:target="checkout"
            class="w-full py-4 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-xl text-sm shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="checkout" class="inline-flex items-center gap-2">
                {{ __('messages.reservation.confirmReservation') }}
                <x-lucide-crown class="w-5 h-5" aria-hidden="true" />
            </span>
            <span wire:loading wire:target="checkout" class="inline-flex items-center gap-2">
                <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                {{ __('messages.reservation.processingReservation') }}
            </span>
        </button>

        <p class="text-[10px] text-center text-stone-400 flex items-center justify-center gap-1">
            <x-lucide-shield-check class="w-3 h-3" aria-hidden="true" />
            <span>{{ __('messages.reservation.smsNotice') }}</span>
        </p>
    </div>
</form>
