{{-- قائمة أطباق السلة --}}
<div>
    <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-3 flex items-center gap-2">
        <x-lucide-utensils class="w-4 h-4" aria-hidden="true" />
        {{ __('messages.menu.selectedDishes') ?? 'الأطباق المختارة للوليمة' }}
    </h4>

    <div class="space-y-3">
        @foreach ($cart as $id => $cartItem)
            <div wire:key="cart-row-{{ $id }}"
                class="bg-white p-3 rounded-xl border border-stone-200 flex items-center justify-between shadow-sm hover:border-amber-200 transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-xs text-[#2C0D0A] truncate">{{ $cartItem['name'] }}</div>
                    <div class="text-xs font-black text-[#E07513] mt-0.5">
                        €{{ number_format($cartItem['price'] * $cartItem['quantity'], 2) }}
                    </div>
                </div>

                <div class="flex items-center gap-2 bg-stone-50 p-1 rounded-lg border border-stone-200">
                    <button type="button" wire:click="removeFromCart({{ $id }})"
                        wire:loading.attr="disabled" wire:target="removeFromCart({{ $id }})"
                        class="w-7 h-7 flex items-center justify-center text-stone-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors disabled:opacity-50"
                        title="{{ __('messages.menu.removeFromCart') }}">
                        <span wire:loading.remove wire:target="removeFromCart({{ $id }})">
                            <x-lucide-trash-2 class="w-3 h-3" aria-hidden="true" />
                        </span>
                        <span wire:loading wire:target="removeFromCart({{ $id }})"
                            class="w-3 h-3 border border-red-500 border-t-transparent rounded-full animate-spin"></span>
                    </button>

                    <div class="w-px h-5 bg-stone-300"></div>

                    <button type="button" wire:click="updateQuantity({{ $id }}, -1)"
                        wire:loading.attr="disabled" wire:target="updateQuantity({{ $id }})"
                        class="w-7 h-7 bg-white rounded text-stone-900 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors disabled:opacity-50">−</button>

                    <span class="w-5 text-center text-xs font-black text-[#2C0D0A]">
                        <span wire:loading.remove
                            wire:target="updateQuantity({{ $id }}), removeFromCart({{ $id }})">{{ $cartItem['quantity'] }}</span>
                        <span wire:loading wire:target="updateQuantity({{ $id }}), removeFromCart({{ $id }})"
                            class="w-3 h-3 border-2 border-amber-500/30 border-t-amber-600 rounded-full animate-spin inline-block"></span>
                    </span>

                    <button type="button" wire:click="updateQuantity({{ $id }}, 1)"
                        wire:loading.attr="disabled" wire:target="updateQuantity({{ $id }})"
                        class="w-7 h-7 bg-[#E07513] text-white rounded hover:bg-amber-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors disabled:opacity-50">+</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
