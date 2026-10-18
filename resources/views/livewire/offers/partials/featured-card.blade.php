@php
    /** @var \App\Models\Promotion $promotion */
    $coupon = $promotion->coupon;
    $discount = $coupon?->discount_value ?? 0;
    $locale = app()->getLocale();
@endphp
{{-- D:\YearTherPartOne\learn-php\projects\jemenitische-keuken-chat-v-valide\jemenitische-keuken-chat\resources\views\livewire\offers\partials\featured-card.blade.php --}}
<div class="relative h-full min-h-[440px] overflow-hidden rounded-[40px] bg-gradient-to-br from-red-900 via-red-950 to-red-900 shadow-xl">

    {{-- Full-bleed image --}}
    @if ($promotion->image)
        <img
            src="{{ Storage::disk('public')->url($promotion->image) }}"
            alt="{{ $promotion->getTranslation('title', $locale) }}"
            class="absolute inset-0 h-full w-full object-cover"
        >
    @else
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 40px 40px;">
        </div>
    @endif

    {{-- Bottom gradient --}}
    <div class="pointer-events-none absolute inset-0"
         style="background: linear-gradient(to top, rgba(38,8,14,0.98) 0%, rgba(58,12,20,0.88) 24%, rgba(58,12,20,0) 56%);">
    </div>

    {{-- Slim corner discount tab --}}
    <div class="absolute end-4 top-4 flex h-[102px] w-[52px] flex-col items-center justify-center rounded-b-[18px] rounded-t-[3px] border border-amber-200/70 bg-gradient-to-b from-amber-300 to-amber-500 text-[#4a2008] shadow-lg">
        <span class="text-xl font-black leading-none">
            {{ number_format((float) $discount, 0) }}%
        </span>
        <span class="mt-1.5 text-[8px] font-extrabold uppercase tracking-[0.2em]">
            {{ __('offers.featured.discount_label') }}
        </span>
    </div>

    {{-- Centered footer --}}
    <div class="absolute inset-x-0 bottom-0 flex flex-col items-center px-4 pb-4 text-center text-white">

        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-amber-200">
            {{ __('offers.featured.tagline') }}
        </p>

        <h3 class="mt-1.5 text-[22px] font-extrabold leading-tight">
            {{ $promotion->getTranslation('title', $locale) }}
        </h3>

        <p class="mt-1.5 text-[13px] text-white/85">
            {{ __('offers.featured.tagline2') }}
        </p>

        <button
            type="button"
            wire:click="useCoupon({{ $promotion->id }})"
            class="mt-4 h-11 w-full rounded-full border border-amber-200/60 bg-[#D66E07] px-6 text-sm font-semibold text-white transition hover:bg-[#6a3220] active:scale-[0.98]"
        >
            {{ $promotion->getTranslation('cta_text', $locale) ?: __('offers.actions.use_now') }}
        </button>

    </div>
</div>