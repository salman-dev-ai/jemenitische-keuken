{{-- قسم الكوبون (تطبيق/إزالة = حساب على الخادم → Livewire) --}}
<div class="pt-4 border-t border-stone-200 mt-6 space-y-3">
    <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-1 flex items-center gap-2">
        <x-lucide-ticket-percent class="w-4 h-4" aria-hidden="true" />
        {{ __('cart.coupon.section_title') }}
    </h4>

    @if ($this->hasAppliedCoupon())
        <div class="flex items-center justify-between gap-3 p-3 rounded-xl border-2 border-emerald-200 bg-emerald-50">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <x-lucide-check class="w-5 h-5" aria-hidden="true" />
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-black text-sm text-emerald-900 truncate" dir="ltr">{{ $appliedCouponCode }}</div>
                    <div class="text-xs text-emerald-700">
                        {{ __('cart.coupon.saved') }}
                        <span class="font-black">€{{ number_format($appliedCouponDiscount, 2) }}</span>
                    </div>
                </div>
            </div>
            <button type="button" wire:click="removeCoupon" wire:loading.attr="disabled"
                wire:target="removeCoupon"
                class="shrink-0 w-8 h-8 rounded-lg bg-white text-rose-500 hover:bg-rose-100 flex items-center justify-center transition-colors disabled:opacity-50"
                title="{{ __('cart.coupon.remove') }}">
                <span wire:loading.remove wire:target="removeCoupon">
                    <x-lucide-x class="w-4 h-4" aria-hidden="true" />
                </span>
                <span wire:loading wire:target="removeCoupon"
                    class="w-4 h-4 border-2 border-rose-500/30 border-t-rose-600 rounded-full animate-spin"></span>
            </button>
        </div>
    @else
        <div class="flex items-stretch gap-2">
            <input type="text" wire:model="couponCode" wire:keydown.enter.prevent="applyCoupon"
                placeholder="{{ __('cart.coupon.placeholder') }}" dir="ltr" autocomplete="off" spellcheck="false"
                class="flex-1 min-w-0 px-3 py-2.5 rounded-xl bg-white border text-sm outline-hidden focus:border-[#E07513] focus:ring-1 focus:ring-[#E07513]/20 transition-all uppercase tracking-wider font-bold @error('couponCode') border-rose-300 @else border-stone-200 @enderror">

            <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon"
                class="px-4 py-2.5 rounded-xl bg-[#2C0D0A] hover:bg-[#E07513] text-white font-bold text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-wait shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                <span wire:loading.remove wire:target="applyCoupon">{{ __('cart.coupon.apply') }}</span>
                <span wire:loading wire:target="applyCoupon"
                    class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
            </button>
        </div>

        @if ($couponError)
            <div class="flex items-start gap-2 p-2.5 rounded-xl border border-rose-200 bg-rose-50 text-xs" role="alert">
                <x-lucide-alert-circle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" aria-hidden="true" />
                <span class="text-rose-800 leading-relaxed">{{ $couponError }}</span>
            </div>
        @endif
    @endif
</div>
