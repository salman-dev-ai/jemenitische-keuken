@php
    /** @var \App\Models\Promotion $promotion */
    $coupon = $promotion->coupon;
    $discount = $coupon?->discount_value ?? 0;
    $locale = app()->getLocale();
@endphp

<div class="relative h-full overflow-hidden rounded-3xl bg-gradient-to-br from-red-900 via-red-950 to-red-900 shadow-xl">

    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 40px 40px;">
    </div>

    <div class="relative flex h-full flex-col p-6 text-white">

        <div class="text-center">
            <p class="text-lg font-light text-amber-200/90">
                {{ __('offers.featured.tagline') }}
            </p>
            <p class="mt-1 text-xl font-semibold">
                {{ __('offers.featured.tagline2') }}
            </p>
        </div>

        <div class="my-6 text-center">
            <div class="inline-flex items-center justify-center">
                <span class="text-lg font-bold text-amber-300">
                    {{ __('offers.featured.discount_label') }}
                </span>
            </div>
            <div class="mt-2 flex items-baseline justify-center gap-1">
                <span class="bg-gradient-to-b from-amber-200 to-amber-500 bg-clip-text text-8xl font-black text-transparent drop-shadow-lg">
                    {{ number_format((float) $discount, 0) }}
                </span>
                <span class="text-5xl font-black text-amber-300">%</span>
            </div>
        </div>

        @if ($promotion->image)
            <div class="relative -mx-6 mt-auto">
                <img
                    src="{{ Storage::disk('public')->url($promotion->image) }}"
                    alt="{{ $promotion->getTranslation('title', $locale) }}"
                    class="h-56 w-full object-cover"
                >
                <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-red-950 to-transparent"></div>
            </div>
        @endif

        <button
            type="button"
            wire:click="useCoupon({{ $promotion->id }})"
            class="mt-4 w-full rounded-xl bg-gradient-to-b from-amber-500 to-amber-700 px-6 py-3.5 text-base font-bold text-white shadow-lg transition hover:from-amber-400 hover:to-amber-600 active:scale-[0.98]"
        >
            {{ $promotion->getTranslation('cta_text', $locale) ?: __('offers.actions.use_now') }}
        </button>

    </div>
</div>