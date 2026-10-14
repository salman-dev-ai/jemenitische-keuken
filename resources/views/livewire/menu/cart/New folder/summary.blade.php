{{-- ملخص السعر --}}
@php $pricing = $this->pricing; @endphp

<div class="bg-[#FAF4ED] p-4 rounded-xl border border-[#E07513]/25 space-y-2.5">
    <div class="flex justify-between items-center text-sm">
        <span class="text-stone-600">{{ __('cart.summary.subtotal') }}</span>
        <span class="font-bold text-stone-800">€{{ number_format($pricing->subtotal, 2) }}</span>
    </div>

    @if ($pricing->discount > 0)
        <div class="flex justify-between items-center text-sm">
            <span class="text-emerald-700 flex items-center gap-1.5">
                <x-lucide-ticket-percent class="w-3.5 h-3.5" aria-hidden="true" />
                <span>{{ __('cart.summary.discount') }}</span>
                <span class="text-xs font-bold text-emerald-600" dir="ltr">({{ $appliedCouponCode }})</span>
            </span>
            <span class="font-bold text-emerald-600">−€{{ number_format($pricing->discount, 2) }}</span>
        </div>

        <div class="flex justify-between items-center text-sm pb-2 border-b border-[#E07513]/15">
            <span class="text-stone-600">{{ __('cart.summary.after_discount') }}</span>
            <span class="font-bold text-stone-800">€{{ number_format($pricing->taxableAmount, 2) }}</span>
        </div>
    @endif

    <div class="flex justify-between items-center text-sm">
        <span class="text-stone-600 flex items-center gap-1.5">
            <span>{{ __('cart.summary.vat') }}</span>
            <span class="text-xs text-stone-400 font-normal">({{ number_format($pricing->vatRate, 0) }}%)</span>
        </span>
        <span class="font-bold text-stone-800">€{{ number_format($pricing->vat, 2) }}</span>
    </div>

    <div class="flex justify-between items-center pt-2.5 border-t-2 border-[#E07513]/30">
        <span class="text-base font-black text-[#2C0D0A]">{{ __('cart.summary.total') }}</span>
        <span class="text-xl font-black text-[#E07513]">€{{ number_format($pricing->total, 2) }}</span>
    </div>
</div>
