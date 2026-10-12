{{-- نموذج الحجز: يضم الحقول + التوصيل + الكوبون + الملخص + زر الإرسال --}}
@php
    $inputClass = 'w-full px-3 py-2.5 rounded-xl bg-white border border-stone-200 text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all';
@endphp

<form wire:submit="checkout" class="space-y-4 pt-4 border-t border-stone-200">
    <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-1 flex items-center gap-2">
        <x-lucide-clipboard-list class="w-4 h-4" aria-hidden="true" />
        {{ __('messages.reservation.contactDetails') }}
    </h4>

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

    @include('livewire.menu.cart.delivery-section', ['inputClass' => $inputClass])

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
                <button type="button" wire:click="forgetCustomer"
                    wire:confirm="{{ __('messages.remember.confirm_forget') }}" wire:loading.attr="disabled"
                    wire:target="forgetCustomer"
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
            <span class="flex-1 text-sm font-semibold text-stone-700">{{ __('messages.remember.checkbox') }}</span>
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

    @include('livewire.menu.cart.coupon-section')

    <div class="pt-4 border-t border-stone-200 mt-6 space-y-4">
        @include('livewire.menu.cart.summary')
        @include('livewire.menu.cart.submit-button')
    </div>
</form>
