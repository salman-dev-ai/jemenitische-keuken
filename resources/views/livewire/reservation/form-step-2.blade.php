{{--
الخطوة الثانية. تجمع الاسم والهاتف والملاحظات وخيار التذكر، وتبقى الحقول داخل النموذج حتى عند إخفائها.
--}}
{{-- تظهر عند الخطوة الثانية مع إبقاء الربط مؤجلاً إلى لحظة إرسال النموذج. --}}
<div x-show="step === 2" x-cloak x-bind:aria-hidden="(step !== 2).toString()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-2 opacity-0"
    class="col-start-1 row-start-1 space-y-4">
    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#32151C]">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#6B1F2B] text-[10px] text-white">2</span>
        <span>{{ __('messages.reservation.step3') }}</span>
    </div>

    {{-- حقلا الاسم والهاتف المطلوبان ويرتبطان مباشرة بخصائص العميل في المكوّن. --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="customer-name-input" class="mb-2 block text-sm font-bold text-stone-700">
                {{ __('messages.reservation.fullName') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="customer-name-input" type="text" wire:model="customer_name"
                placeholder="{{ __('messages.reservation.fullNamePlaceholder') }}" autocomplete="name"
                class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
            @error('customer_name')
                <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="customer-phone-input" class="mb-2 block text-sm font-bold text-stone-700">
                {{ __('messages.reservation.phone') }} <span class="text-rose-500" aria-hidden="true">*</span>
            </label>
            <input id="customer-phone-input" type="tel" wire:model="customer_phone" placeholder="+31 6 12 34 56 78"
                dir="ltr" autocomplete="tel"
                class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm font-mono focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
            @error('customer_phone')
                <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- ملاحظات اختيارية تُرسل مع طلب الحجز وتظهر تحتها رسالة التحقق. --}}
    <div>
        <label for="special-requests-input" class="mb-2 block text-sm font-bold text-stone-700">
            {{ __('messages.reservation.specialRequests') }}
        </label>
        <textarea id="special-requests-input" wire:model="special_requests" rows="3"
            placeholder="{{ __('messages.reservation.specialRequestsPlaceholder') }}"
            class="w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3"></textarea>
        @error('special_requests')
            <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
        @enderror
    </div>

    {{-- ترحيب العميل العائد وخيار تذكر البيانات أو مسحها من هذا الجهاز. --}}
    <div class="mt-4 space-y-3 border-t border-stone-100 pt-4">
        @if ($isReturningCustomer)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm"
                wire:key="returning-customer-banner">
                <x-lucide-user-check class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" aria-hidden="true" />
                <div class="flex-1">
                    <strong class="block font-bold text-amber-900">
                        {{ __('messages.remember.welcome_back') }}
                    </strong>
                    <span class="text-amber-800">
                        {{ __('messages.remember.filled_automatically') }}
                    </span>
                </div>
                <button type="button"
                    @click="window.dispatchEvent(new CustomEvent('confirm-action', { detail: {
                        title: @js(__('messages.remember.confirm_title')),
                        message: @js(__('messages.remember.confirm_forget')),
                        confirmLabel: @js(__('messages.remember.confirm_yes')),
                        cancelLabel: @js(__('messages.confirm.cancel')),
                        onConfirm: () => $wire.forgetCustomer(),
                    } }))"
                    wire:loading.attr="disabled"
                    wire:target="forgetCustomer"
                    class="shrink-0 rounded-lg border border-amber-300 bg-white px-3 py-1.5 text-xs font-bold text-amber-700 transition hover:border-amber-400 hover:text-amber-900 disabled:cursor-wait disabled:opacity-50">
                    <span wire:loading.remove wire:target="forgetCustomer">
                        {{ __('messages.remember.not_you') }}
                    </span>
                    <span wire:loading wire:target="forgetCustomer">
                        ...
                    </span>
                </button>
            </div>
        @endif

        <label for="remember_me"
            class="flex cursor-pointer items-center gap-3 rounded-xl p-2 transition hover:bg-stone-50">
            <input id="remember_me" type="checkbox" wire:model="remember_me"
                class="h-5 w-5 rounded-md border-stone-300 text-[#6B1F2B] focus:ring-2 focus:ring-[#E07513]">
            <span class="flex-1 text-sm font-semibold text-stone-700">
                {{ __('messages.remember.checkbox') }}
                <x-lucide-save-check class="ml-1 inline  font-semibold h-5 w-5 text-[#6B1F2B]" aria-hidden="true" />
            </span>
        </label>
    </div>
</div>
