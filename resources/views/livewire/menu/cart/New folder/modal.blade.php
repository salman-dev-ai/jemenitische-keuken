{{-- نافذة السلة: موجودة دائماً في الـ DOM ويتحكم بها Alpine (x-show + x-transition) = فتح/إغلاق فوري --}}
<div x-show="isCartOpen" x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    role="dialog" aria-modal="true" aria-labelledby="cart-modal-title"
    @keydown.escape.window="if (!$wire.isMyCouponsModalOpen) isCartOpen = false">

    {{-- الخلفية --}}
    <div x-show="isCartOpen"
        x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-stone-900/70 backdrop-blur-sm" @click="isCartOpen = false" aria-hidden="true">
    </div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
        <div x-show="isCartOpen"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md md:max-w-lg bg-[#FAF6F0] text-[#2C1810] shadow-2xl flex flex-col justify-between border-l border-[#E8DFD3]">

            {{-- رأس النافذة --}}
            <div
                class="p-5 bg-gradient-to-r from-[#2A0D0A] to-[#1A0604] text-white flex items-center justify-between border-b border-[#E07513]/30 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center">
                        <x-lucide-crown class="w-5 h-5" aria-hidden="true" />
                    </div>
                    <div>
                        <h3 id="cart-modal-title" class="font-black text-base text-white">
                            {{ __('messages.menu.cart') ?? 'سلة الأطباق وتثبيت الحجز' }}</h3>
                        <p class="text-xs text-amber-200">{{ $this->totalCartCount }}
                            {{ __('messages.menu.items') ?? 'أصناف مختارة' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    {{-- كوبوناتي: يجلب بيانات من الخادم --}}
                    <button type="button" wire:click="openMyCoupons"
                        class="text-amber-200 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
                        title="{{ __('my-coupons.title') }}" aria-label="{{ __('my-coupons.title') }}">
                        <x-lucide-ticket-percent class="w-5 h-5" aria-hidden="true" />
                    </button>

                    {{-- إغلاق فوري --}}
                    <button type="button" @click="isCartOpen = false" aria-label="{{ __('messages.menu.close') ?? 'Close' }}"
                        class="text-stone-400 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                        <x-lucide-x class="w-5 h-5" aria-hidden="true" />
                    </button>
                </div>
            </div>

            {{-- المحتوى القابل للتمرير --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-5">
                @if (count($cart) === 0)
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div
                            class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mb-4 border-2 border-stone-200">
                            <x-lucide-shopping-cart class="w-10 h-10 text-stone-300" aria-hidden="true" />
                        </div>
                        <h3 class="text-sm font-black text-stone-700 mb-2">
                            {{ __('messages.menu.empty') ?? 'السلة فارغة' }}</h3>
                        <p class="text-xs text-stone-500 max-w-[200px] mb-6">{{ __('messages.menu.emptyText') }}</p>
                        <button type="button" @click="isCartOpen = false"
                            class="w-full py-3 bg-[#2A0D0A] hover:bg-[#E07513] text-white font-bold rounded-xl text-sm transition-colors">
                            {{ __('messages.menu.browseMenu') }}
                        </button>
                    </div>
                @else
                    @include('livewire.menu.cart.items-list')
                    @include('livewire.menu.cart.checkout-form')
                @endif
            </div>
        </div>
    </div>
</div>
