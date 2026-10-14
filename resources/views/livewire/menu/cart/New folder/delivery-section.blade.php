{{-- حقول التوصيل: تظهر فوراً عند اختيار "توصيل" (Alpine يقرأ $wire.order_type بلا رحلة خادم) --}}
<div x-show="$wire.order_type === 'delivery'" x-cloak x-transition
    class="space-y-3 p-3 rounded-2xl border border-amber-200 bg-amber-50/50">
    <h5 class="font-bold text-xs text-amber-800 uppercase tracking-wider flex items-center gap-2">
        <x-lucide-truck class="w-4 h-4" aria-hidden="true" />
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
