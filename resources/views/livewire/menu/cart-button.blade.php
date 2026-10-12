{{-- الشريط العائم للسلة — فتح النافذة فوري (isCartOpen من Alpine) --}}
@if (count($cart) > 0)
    <div class="fixed bottom-5 inset-x-4 sm:inset-x-auto sm:right-6 sm:left-6 max-w-2xl mx-auto z-40"
        x-show="!isCartOpen" x-transition.opacity>
        <div
            class="bg-gradient-to-r from-[#2A0D0A] via-[#1E0907] to-[#2A0D0A] text-white p-3.5 sm:p-4 rounded-3xl shadow-2xl border-2 border-[#E07513] flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div
                        class="w-11 h-11 rounded-2xl bg-[#E07513] text-white flex items-center justify-center shadow-md">
                        <x-lucide-shopping-bag class="w-6 h-6" aria-hidden="true" />
                    </div>
                    <span
                        class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-amber-400 text-stone-950 text-[11px] font-black flex items-center justify-center">
                        {{ $this->totalCartCount }}
                    </span>
                </div>
                <span class="text-sm sm:text-base font-black text-white">
                    {{ __('messages.menu.total') }}: €{{ number_format($this->totalCartAmount, 2) }}
                </span>
            </div>

            <button type="button" @click="isCartOpen = true"
                class="px-5 py-2.5 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-2xl text-xs sm:text-sm shadow-md transition-all flex items-center gap-2 cursor-pointer shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                <span>{{ __('messages.menu.checkout') }}</span>
                <x-lucide-arrow-left class="w-4 h-4 rtl:rotate-0 ltr:rotate-180" aria-hidden="true" />
            </button>
        </div>
    </div>
@endif
