<?php

use App\Models\Promotion;
use function Livewire\Volt\{state, computed};

state(['copiedCode' => null]);

$promotions = computed(function () {
    return Promotion::query()
        ->with('coupon')
        ->visible()
        ->whereHas('coupon', fn ($q) => $q->available())
        ->get();
});

$featured = computed(fn () => $this->promotions->firstWhere('is_featured', true));
$regular = computed(fn () => $this->promotions->where('is_featured', false)->values());

$copyCode = function (string $code) {
    $this->copiedCode = $code;
    $this->dispatch('coupon-copied', code: $code);
};

$useCoupon = function (int $promotionId) {
    $this->dispatch('open-coupon-modal', promotionId: $promotionId);
};

?>

<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    {{-- ═══ العنوان الرئيسي ═══ --}}
    <div class="mb-10 text-center">
        <div class="flex items-center justify-center gap-4">
            <span class="h-px w-24 bg-gradient-to-l from-transparent to-amber-700/60"></span>
            <span class="text-2xl text-amber-700">✦</span>

            <div>
                <h2 class="font-serif text-4xl font-bold text-amber-900 md:text-5xl">
                    {{ __('offers.hero.title') }}
                </h2>
                <p class="mt-1 text-xl font-semibold text-amber-700">
                    {{ __('offers.hero.subtitle') }}
                </p>
            </div>

            <span class="text-2xl text-amber-700">✦</span>
            <span class="h-px w-24 bg-gradient-to-r from-transparent to-amber-700/60"></span>
        </div>
    </div>

    {{-- ═══ Grid ═══ --}}
    @if ($this->promotions->isNotEmpty())
        <div class="mx-auto grid max-w-7xl gap-6 px-4 lg:grid-cols-12">

            {{-- البطاقة المميزة --}}
            @if ($this->featured)
                <div class="lg:col-span-3">
                    @include('livewire.offers.partials.featured-card', [
                        'promotion' => $this->featured,
                    ])
                </div>
            @endif

            {{-- البطاقات العادية --}}
            <div class="lg:col-span-9">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($this->regular as $promotion)
                        @include('livewire.offers.partials.card', [
                            'promotion' => $promotion,
                        ])
                    @endforeach
                </div>
            </div>

        </div>
    @else
        <div class="py-16 text-center text-gray-500">
            {{ __('offers.empty') }}
        </div>
    @endif

    {{-- ═══ سكربت النسخ ═══ --}}
    <script>
        window.addEventListener('coupon-copied', (e) => {
            navigator.clipboard.writeText(e.detail.code);
        });
    </script>

</div>