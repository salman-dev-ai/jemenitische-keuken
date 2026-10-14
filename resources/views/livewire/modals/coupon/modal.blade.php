{{--
    resources/views/livewire/modals/coupon/modal.blade.php

    واجهة نافذة الكوبون كاملة في ملف واحد:
    الخلفية + البطاقة + الرأس + رمز الكوبون + نموذج الضيف + مستمع النسخ.
    المنطق كله في coupon-modal.blade.php (Volt).
--}}

@php
    // حقول النموذج في مصفوفة واحدة: لإضافة حقل أو تعديله غيّر هنا فقط.
    // 'half' => true يجعل الحقل بنصف العرض (المدينة والرمز البريدي).
    $fields = [
        ['id' => 'coupon-name',    'model' => 'name',    'type' => 'text',  'label' => __('offers.modal.name'),    'required' => true,  'autocomplete' => 'name'],
        ['id' => 'coupon-phone',   'model' => 'phone',   'type' => 'tel',   'label' => __('offers.modal.phone'),   'required' => true,  'dir' => 'ltr', 'autocomplete' => 'tel',   'placeholder' => '+31 6 1234 5678'],
        ['id' => 'coupon-email',   'model' => 'email',   'type' => 'email', 'label' => __('offers.modal.email'),   'required' => false, 'dir' => 'ltr', 'autocomplete' => 'email', 'placeholder' => 'example@domain.com'],
        ['id' => 'coupon-address', 'model' => 'address', 'type' => 'text',  'label' => __('offers.modal.address'), 'required' => false, 'autocomplete' => 'street-address', 'placeholder' => __('offers.modal.address_placeholder')],
        ['id' => 'coupon-city',        'model' => 'city',        'type' => 'text', 'label' => __('offers.modal.city'),        'required' => false, 'half' => true, 'autocomplete' => 'address-level2', 'placeholder' => __('offers.modal.city_placeholder')],
        ['id' => 'coupon-postal-code', 'model' => 'postal_code', 'type' => 'text', 'label' => __('offers.modal.postal_code'), 'required' => false, 'half' => true, 'dir' => 'ltr', 'autocomplete' => 'postal-code', 'placeholder' => '1012 NK'],
    ];
@endphp

{{-- مستمع النسخ: يكتب الكود في الحافظة بعد أن يرسل Volt الحدث --}}
<div
    x-data
    x-on:coupon-copied.window="
        navigator.clipboard.writeText($event.detail.code).catch(() => {
            const t = document.createElement('textarea');
            t.value = $event.detail.code;
            document.body.appendChild(t);
            t.select();
            document.execCommand('copy');
            t.remove();
        })
    "
></div>

{{-- الخلفية --}}
<div
    x-data="{ open: $wire.entangle('show').live }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    wire:ignore.self
    x-on:click.self="open = false"
    x-on:keydown.escape.window="if (open) open = false"
    role="dialog"
    aria-modal="true"
    aria-labelledby="coupon-modal-title"
    :aria-hidden="open ? 'false' : 'true'"
>
    {{-- البطاقة --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl"
        dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    >
        {{-- الرأس --}}
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100">
                <svg class="h-6 w-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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

        {{-- رمز الكوبون --}}
        <div class="mb-5 flex items-center justify-between gap-2 rounded-xl border-2 border-dashed border-amber-300 bg-amber-50/60 px-4 py-3">
            <code class="font-mono text-base font-bold tracking-wider text-amber-900" dir="ltr">
                {{ $couponCode }}
            </code>
            <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        {{-- نموذج الضيف --}}
        <form wire:submit="submitGuest" class="grid grid-cols-2 gap-x-3 gap-y-4">

            @foreach ($fields as $f)
                <div class="{{ ($f['half'] ?? false) ? 'col-span-1' : 'col-span-2' }}">
                    <label for="{{ $f['id'] }}" class="mb-1 block text-sm font-medium text-gray-700">
                        {{ $f['label'] }}
                        @if ($f['required'])
                            <span class="text-rose-500" aria-hidden="true">*</span>
                        @else
                            <span class="text-xs font-normal text-gray-400">({{ __('offers.modal.optional') }})</span>
                        @endif
                    </label>

                    <input
                        id="{{ $f['id'] }}"
                        type="{{ $f['type'] }}"
                        wire:model="{{ $f['model'] }}"
                        @isset($f['dir']) dir="{{ $f['dir'] }}" @endisset
                        @isset($f['autocomplete']) autocomplete="{{ $f['autocomplete'] }}" @endisset
                        @isset($f['placeholder']) placeholder="{{ $f['placeholder'] }}" @endisset
                        class="w-full rounded-lg border px-4 py-2.5 transition focus:outline-none
                               @error($f['model']) border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
                               @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror"
                    >

                    @error($f['model'])
                        <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            {{-- الأزرار --}}
            <div class="col-span-2 flex gap-3 pt-2">
                <button
                    type="button"
                    x-on:click="open = false"
                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    {{ __('offers.modal.cancel') }}
                </button>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="submitGuest"
                    class="flex-1 rounded-lg bg-amber-800 px-4 py-2.5 font-medium text-white transition hover:bg-amber-900 disabled:cursor-wait disabled:opacity-70"
                >
                    <span wire:loading.remove wire:target="submitGuest">{{ __('offers.modal.submit') }}</span>
                    <span wire:loading wire:target="submitGuest"
                          class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white align-middle"></span>
                </button>
            </div>
        </form>
    </div>
</div>
