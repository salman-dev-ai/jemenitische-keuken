{{-- نافذة كوبوناتي — فتح فوري من المتصفح + إغلاق انزلاقي سلس.
     تعمل بطريقتين:
       1) (الأسرع) تُضمَّن دائماً في الصفحة بدون @if، ويفتحها الزر بالحدث: @click="$dispatch('open-my-coupons')"
       2) تُضمَّن داخل @if ($isMyCouponsModalOpen) كما في السابق، فتفتح تلقائياً عند الإنشاء.
     الإغلاق: Alpine يشغّل حركة الخروج أولاً ثم يُبلغ الخادم. من PHP استخدم:
       $this->dispatch('close-my-coupons');
--}}
<div x-data="{
        visible: false,
        show: false,
        open() {
            if (this.visible) return;
            this.visible = true;
            this.$nextTick(() => this.show = true);
            if (!$wire.isMyCouponsModalOpen) $wire.openMyCoupons();
        },
        close() {
            if (!this.show) return;
            this.show = false;
            setTimeout(() => {
                this.visible = false;
                $wire.closeMyCoupons();
            }, 300);
        },
    }"
    x-init="if ($wire.isMyCouponsModalOpen) { visible = true; $nextTick(() => show = true); }"
    @open-my-coupons.window="open()"
    @close-my-coupons.window="close()"
    @keydown.escape.window="visible && close()"
    x-show="visible" x-cloak
    data-my-coupons
    :data-open="visible"
    class="fixed inset-0 z-[60] overflow-hidden" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    role="dialog" aria-modal="true" aria-labelledby="my-coupons-title">

    {{-- الخلفية --}}
    <div x-show="show"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-stone-900/70"
        @click="close()" aria-hidden="true"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
        {{-- اللوحة --}}
        <div x-show="show"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="transform-gpu will-change-transform w-screen max-w-md md:max-w-lg bg-[#FAF6F0] text-[#2C1810] shadow-2xl flex flex-col justify-between border-l border-[#E8DFD3]">

            <div
                class="p-5 bg-gradient-to-r from-[#2A0D0A] to-[#1A0604] text-white flex items-center justify-between border-b border-[#E07513]/30 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center">
                        <x-lucide-ticket-percent class="w-5 h-5" aria-hidden="true" />
                    </div>
                    <div>
                        <h3 id="my-coupons-title" class="font-black text-base text-white">{{ __('my-coupons.title') }}</h3>
                        <p class="text-xs text-amber-200">{{ __('my-coupons.subtitle') }}</p>
                    </div>
                </div>
                <button type="button" @click="close()"
                    class="text-stone-400 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                    <x-lucide-x class="w-5 h-5" aria-hidden="true" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5">
                @if ($isMyCouponsModalOpen && $this->hasMyCoupons)
                    <div class="mb-4 p-3 rounded-xl border border-amber-200 bg-amber-50/60 flex items-start gap-2">
                        <x-lucide-info class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" aria-hidden="true" />
                        <p class="text-xs text-amber-800 leading-relaxed">{{ __('my-coupons.hint') }}</p>
                    </div>

                    <div class="space-y-3">
                        @foreach ($this->myCoupons as $coupon)
                            <div wire:key="my-coupon-{{ $coupon->id }}"
                                class="bg-white rounded-2xl border-2 border-stone-200 p-4 hover:border-amber-300 transition-colors">

                                <div class="flex items-center gap-2 mb-3 flex-wrap">
                                    <span
                                        class="px-3 py-1.5 rounded-lg bg-stone-900 text-amber-100 font-mono font-black text-base tracking-wider"
                                        dir="ltr">{{ $coupon->code }}</span>

                                    @if ($coupon->is_welcome)
                                        <span
                                            class="px-2 py-1 rounded-md bg-amber-500 text-white text-[10px] font-black flex items-center gap-1">
                                            <x-lucide-sparkles class="w-3 h-3" aria-hidden="true" />
                                            {{ __('my-coupons.welcome_badge') }}
                                        </span>
                                    @endif

                                    @if ($coupon->hasScope())
                                        <span class="px-2 py-1 rounded-md bg-info/10 text-info text-[10px] font-black">
                                            {{ $coupon->applies_to->getLabel() }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-2xl font-black text-[#E07513] mb-2 leading-none">
                                    @if ($coupon->discount_type->isPercentage())
                                        {{ __('my-coupons.percentage_off', ['value' => (float) $coupon->discount_value]) }}
                                    @else
                                        {{ __('my-coupons.fixed_off', ['value' => number_format((float) $coupon->discount_value, 2)]) }}
                                    @endif
                                </div>

                                <div class="space-y-1 mb-4 text-xs text-stone-500">
                                    @if ((float) $coupon->min_order_total > 0)
                                        <div class="flex items-center gap-1.5">
                                            <x-lucide-shopping-bag class="w-3.5 h-3.5" aria-hidden="true" />
                                            <span>{{ __('my-coupons.min_order', ['value' => number_format((float) $coupon->min_order_total, 2)]) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1.5">
                                        <x-lucide-calendar-clock class="w-3.5 h-3.5" aria-hidden="true" />
                                        @if ($coupon->expires_at)
                                            <span>{{ __('my-coupons.expires', ['date' => $coupon->expires_at->format('Y-m-d')]) }}</span>
                                        @else
                                            <span>{{ __('my-coupons.no_expiry') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    {{-- نسخ فوري عبر Alpine؛ الإشعار للخادم يتم بدون انتظار --}}
                                    <button type="button"
                                        x-data="{
                                            copied: false,
                                            code: @js($coupon->code),
                                            async copy() {
                                                try {
                                                    if (navigator.clipboard && window.isSecureContext) {
                                                        await navigator.clipboard.writeText(this.code);
                                                    } else {
                                                        const t = document.createElement('textarea');
                                                        t.value = this.code;
                                                        t.setAttribute('readonly', '');
                                                        t.style.cssText = 'position:fixed;opacity:0';
                                                        document.body.appendChild(t);
                                                        t.select();
                                                        document.execCommand('copy');
                                                        t.remove();
                                                    }
                                                    this.copied = true;
                                                    $wire.markCouponAsCopied(this.code);
                                                    setTimeout(() => this.copied = false, 2000);
                                                } catch (e) { this.copied = false; }
                                            },
                                        }"
                                        @click="copy()"
                                        class="flex-1 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold text-sm transition-colors flex items-center justify-center gap-2">
                                        <span x-show="!copied" class="flex items-center gap-2">
                                            <x-lucide-copy class="w-4 h-4" aria-hidden="true" />
                                            <span>{{ __('my-coupons.copy') }}</span>
                                        </span>
                                        <span x-show="copied" x-cloak class="flex items-center gap-2 text-emerald-700">
                                            <x-lucide-check class="w-4 h-4" aria-hidden="true" />
                                            <span>{{ __('my-coupons.copied') }}</span>
                                        </span>
                                    </button>

                                    <button type="button"
                                        wire:click="applyCouponFromList(@js($coupon->code))"
                                        wire:loading.attr="disabled" wire:target="applyCouponFromList"
                                        class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] text-white font-black text-sm shadow-md hover:from-[#c2620a] hover:to-[#994303] transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-wait">
                                        <span wire:loading.remove wire:target="applyCouponFromList">{{ __('my-coupons.apply') }}</span>
                                        <span wire:loading wire:target="applyCouponFromList"
                                            class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif ($isMyCouponsModalOpen)
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div
                            class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mb-4 border-2 border-stone-200">
                            <x-lucide-ticket-percent class="w-10 h-10 text-stone-300" aria-hidden="true" />
                        </div>
                        <h3 class="text-base font-black text-stone-700 mb-2">{{ __('my-coupons.empty') }}</h3>
                        <p class="text-xs text-stone-500 max-w-[260px] leading-relaxed">{{ __('my-coupons.empty_hint') }}</p>
                    </div>
                @else
                    {{-- هيكل تحميل يظهر لحظة الفتح حتى تصل البيانات من الخادم --}}
                    <div class="space-y-3 animate-pulse" aria-hidden="true">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="h-40 rounded-2xl bg-stone-200/70"></div>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>