@php
    $inCart = isset($cart[$item->id]);
    $cartQty = $inCart ? $cart[$item->id]['quantity'] : 0;
    $allergens = is_array($item->allergens) ? $item->allergens : (json_decode($item->allergens ?? '[]', true) ?: []);
@endphp

<div wire:key="item-{{ $item->id }}"
    x-data="{ slug: @js($item->category->slug), expanded: false }"
    x-show="selected === 'all' || selected === slug"
    x-transition.opacity.duration.200ms
    class="group bg-white rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-2xl hover:shadow-stone-200/50 transition-all duration-300 flex flex-col">

    {{-- صورة الطبق --}}
    <div class="relative aspect-[4/3] overflow-hidden bg-stone-100">
        @if ($item->image_path)
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->localized_name }}" loading="lazy"
                width="600" height="450"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center bg-stone-50">
                <x-lucide-soup class="w-10 h-10 text-stone-300" aria-hidden="true" />
            </div>
        @endif

        <div class="absolute top-3 start-3 flex flex-col gap-2">
            @if ($item->is_featured)
                <span
                    class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                    <x-lucide-star class="w-3 h-3 fill-white" aria-hidden="true" />
                    {{ __('messages.menu.featured') }}
                </span>
            @endif
            @if ($item->is_spicy)
                <span
                    class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                    <x-lucide-flame class="w-3 h-3" aria-hidden="true" />
                    {{ __('messages.menu.spicy') }}
                </span>
            @endif
        </div>

        <div
            class="absolute bottom-3 end-3 px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg border border-stone-100">
            <span class="text-sm font-black text-stone-900">€{{ number_format($item->price, 2) }}</span>
        </div>
    </div>

    {{-- تفاصيل الطبق --}}
    <div class="p-5 flex flex-col flex-1">
        <div class="flex-1">
            <h4 class="text-lg font-black text-stone-900 mb-1.5 leading-snug">{{ $item->localized_name }}</h4>

            {{-- الوصف: اضغط لإظهار/إخفاء كامل الوصف (فوري عبر Alpine) --}}
            <p @click="expanded = !expanded" :class="expanded ? '' : 'line-clamp-2'"
                class="text-sm text-stone-500 leading-relaxed mb-3 cursor-pointer">
                {{ $item->localized_description }}
            </p>

            @if (!empty($allergens))
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach ($allergens as $allergen)
                        <span
                            class="text-[12px] font-bold px-2 py-0.5 bg-stone-100 text-stone-600 rounded-md border border-stone-200">
                            {{ $allergen }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- أزرار السلة (حسابات/تخزين → Livewire) --}}
        <div class="pt-4 border-t border-stone-100 mt-auto">
            @if (!$inCart)
                <button type="button" wire:click="addToCart({{ $item->id }})" wire:loading.attr="disabled"
                    wire:target="addToCart({{ $item->id }})"
                    class="w-full py-3 bg-stone-900 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition-all duration-300 flex items-center justify-center gap-2 group-hover:shadow-lg disabled:opacity-70 disabled:cursor-wait">
                    <span wire:loading.remove wire:target="addToCart({{ $item->id }})"
                        class="flex items-center gap-2">
                        <x-lucide-shopping-cart class="w-4 h-4" aria-hidden="true" />
                        <span>{{ __('messages.menu.addToCart') ?? 'أضف إلى السلة' }}</span>
                    </span>
                    <span wire:loading wire:target="addToCart({{ $item->id }})"
                        class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                </button>
            @else
                <div class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-xl p-1">
                    <button type="button" wire:click="updateQuantity({{ $item->id }}, -1)"
                        class="w-9 h-9 rounded-lg bg-white text-stone-900 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold text-lg shadow-sm transition-colors">−</button>

                    <button type="button" wire:click="removeFromCart({{ $item->id }})"
                        wire:loading.attr="disabled" wire:target="removeFromCart({{ $item->id }})"
                        class="w-9 h-9 rounded-lg bg-white text-red-500 hover:bg-red-100 hover:text-red-600 flex items-center justify-center shadow-sm transition-all disabled:opacity-50 disabled:cursor-wait"
                        title="{{ __('messages.menu.removeFromCart') }}">
                        <span wire:loading.remove wire:target="removeFromCart({{ $item->id }})">
                            <x-lucide-trash-2 class="w-4 h-4" aria-hidden="true" />
                        </span>
                        <span wire:loading wire:target="removeFromCart({{ $item->id }})"
                            class="w-4 h-4 border-2 border-red-500/30 border-t-red-600 rounded-full animate-spin"></span>
                    </button>

                    <span class="font-black text-stone-900 text-base w-8 text-center">{{ $cartQty }}</span>

                    <button type="button" wire:click="updateQuantity({{ $item->id }}, 1)"
                        class="w-9 h-9 rounded-lg bg-amber-500 text-white hover:bg-amber-600 flex items-center justify-center font-bold text-lg shadow-sm transition-colors">+</button>
                </div>
            @endif
        </div>
    </div>
</div>
