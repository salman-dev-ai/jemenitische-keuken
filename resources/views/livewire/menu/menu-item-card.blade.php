@php
    $inCart = isset($cart[$item->id]);
    $cartQty = $inCart ? $cart[$item->id]['quantity'] : 0;

    $hasFreeDrink = isset($freeDrinkItems[$item->id]);

    // معلومات الحساسية والعلامات الغذائية — القاموس المُدار من لوحة التحكم (المفعّل فقط)
    $itemAllergens = $item->relationLoaded('activeAllergens')
        ? $item->activeAllergens
        : $item->activeAllergens()->get();

    @endphp

<div wire:key="item-{{ $item->id }}"
    x-data="{ slug: @js($item->category->slug), expanded: false }"
    x-show="selected === 'all' || selected === slug"
    x-transition.opacity.duration.200ms
    class="group bg-white rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-2xl hover:shadow-stone-200/50 transition-all duration-300 flex flex-col">

    {{-- صورة الطبق --}}
    <div class="relative aspect-[4/4] overflow-hidden bg-stone-100">
        @if ($item->image_path)
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->localized_name }}" width="600" height="600"
                loading="lazy" decoding="async"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center bg-stone-50">
                @svg('lucide-soup', 'w-10 h-10 text-stone-300', ['aria-hidden' => 'true'])
            </div>
        @endif

        <div class="absolute top-3 start-3 flex flex-col gap-2">
                        {{--   شارة المشروب المجاني: hover على الحاسوب + نقر/لمس على الجوال --}}
            @if ($hasFreeDrink)
                <div x-data="{ tip: false }" class="relative">
                    <button type="button"
                        @click.stop="tip = !tip"
                        @mouseenter="tip = true" @mouseleave="tip = false"
                        @keydown.enter.prevent="tip = !tip" @keydown.escape="tip = false"
                        class="px-2.5 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1"
                        aria-label="{{ __('messages.menu.free_drink_badge') }}">
                        @svg('lucide-gift', 'w-4 h-4', ['aria-hidden' => 'true'])
                        <!-- {{ __('messages.menu.free_drink_badge') }} -->
                    </button>
                    <span x-show="tip" x-cloak x-transition.opacity
                        class="absolute top-full start-0 mt-1 z-20 w-44 text-[10px] font-bold bg-stone-900 text-white rounded-lg px-2.5 py-1.5 shadow-xl">
                        {{ __('messages.menu.free_drink_hint') }}
                    </span>
                </div>
            @endif
            
            @if ($item->is_featured)
                <span
                    class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                    @svg('lucide-star', 'w-3 h-3 fill-white', ['aria-hidden' => 'true'])
                    {{ __('messages.menu.featured') }}
                </span>
            @endif
            @if ($item->is_spicy)
                <span
                    class="px-2.5 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg shadow-lg flex items-center gap-1">
                    @svg('lucide-flame', 'w-3 h-3', ['aria-hidden' => 'true'])
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

            @if ($itemAllergens->isNotEmpty())
                <div class="flex flex-wrap gap-1.5 mb-3">
                    @foreach ($itemAllergens as $allergen)
                        @php
                            // كهرماني لمسببات الحساسية، أخضر للعلامات الغذائية
                            $badgeClasses = $allergen->isDietaryLabel()
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-amber-50 text-amber-700 border-amber-200';

                            // صورة مرفوعة أولاً، وإلا أيقونة Lucide المحفوظة في القاموس
                            $svgMarkup = (! $allergen->hasImage() && $allergen->iconName() !== null)
                                ? rescue(fn () => svg($allergen->iconName(), 'w-4 h-4')->toHtml(), null, false)
                                : null;
                        @endphp

                        <div x-data="{ tip: false }" class="relative">
                            <span @mouseenter="tip = true" @mouseleave="tip = false"
                                @click.stop="tip = !tip" @keydown.enter.prevent="tip = !tip"
                                @keydown.escape="tip = false" tabindex="0"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg border {{ $badgeClasses }} cursor-help"
                                aria-label="{{ $allergen->localized_name }}">
                                @if ($allergen->hasImage())
                                    <img src="{{ Storage::disk('public')->url($allergen->image_path) }}"
                                        alt="{{ $allergen->localized_name }}" loading="lazy"
                                        class="w-4 h-4 object-contain">
                                @elseif ($svgMarkup)
                                    {!! $svgMarkup !!}
                                @else
                                    @svg('lucide-info', 'w-4 h-4', ['aria-hidden' => 'true'])
                                @endif
                            </span>

                            {{-- تلميح الاسم المترجَم: hover على الحاسوب + نقر/لمس على الجوال --}}
                            <span x-show="tip" x-cloak x-transition.opacity
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 z-20 whitespace-nowrap text-[10px] font-bold bg-stone-900 text-white rounded-lg px-2.5 py-1.5 shadow-xl pointer-events-none">
                                {{ $allergen->localized_name }}
                            </span>
                        </div>
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
                        @svg('lucide-shopping-cart', 'w-4 h-4', ['aria-hidden' => 'true'])
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
                            @svg('lucide-trash-2', 'w-4 h-4', ['aria-hidden' => 'true'])
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
