@php
    /** @var \App\Models\Promotion $promotion */
    $coupon = $promotion->coupon;
    $locale = app()->getLocale();
    $title = $promotion->getTranslation('title', $locale);
    $imageUrl = $promotion->image ? Storage::disk('public')->url($promotion->image) : null;

    // سطر الصلاحية: "ينتهي في 22 نوفمبر 2026" (وإن لم يوجد تاريخ انتهاء يُعرض تاريخ البداية)
    $endsAt = $promotion->ends_at;
    $startsAt = $promotion->starts_at;
    $dateLine = $endsAt
        ? __('offers.ends_on', ['date' => $endsAt->locale($locale)->translatedFormat('j F Y')])
        : ($startsAt ? __('offers.valid_from') . ': ' . $startsAt->locale($locale)->translatedFormat('j F Y') : null);
    $dateIso = ($endsAt ?? $startsAt)?->toIso8601String();

    $ctaText = $promotion->getTranslation('cta_text', $locale) ?: __('offers.actions.use_now');
@endphp

{{-- البطاقة المميزة: صورة عريضة بملء البطاقة + شارة "عرض اليوم" + المحتوى في الأسفل --}}
<article
    class="group relative isolate flex h-full min-h-[27rem] flex-col justify-end overflow-hidden rounded-[1.75rem] border border-amber-500/70 bg-[#2a0d0d] shadow-xl shadow-black/30 transition duration-300 hover:shadow-2xl hover:shadow-amber-900/30 sm:min-h-[30rem]">

    {{-- الصورة --}}
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $title }}" decoding="async"
            class="absolute inset-0 h-full w-full object-cover transition duration-700 motion-safe:group-hover:scale-105">
    @else
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 40px 40px;">
        </div>
    @endif

    {{-- التدرج السفلي لوضوح النص --}}
    <div class="pointer-events-none absolute inset-0"
        style="background: linear-gradient(to top, rgba(28,6,6,0.97) 0%, rgba(28,6,6,0.75) 28%, rgba(28,6,6,0) 64%);">
    </div>

    {{-- شارة "عرض اليوم" بشكل ضربة فرشاة --}}
    <div class="pointer-events-none absolute start-4 top-4 z-10 -rotate-3 drop-shadow-lg sm:start-6 sm:top-6">
        <span
            class="block bg-gradient-to-r from-amber-400 to-amber-500 px-7 py-2.5 text-lg font-black text-[#3b1a05] sm:px-9 sm:text-xl"
            style="clip-path: polygon(1% 28%, 6% 8%, 16% 16%, 30% 2%, 46% 12%, 62% 0, 78% 10%, 92% 4%, 100% 14%, 96% 40%, 100% 62%, 94% 82%, 99% 96%, 82% 88%, 66% 100%, 48% 90%, 32% 98%, 16% 90%, 5% 100%, 0 78%, 4% 52%);">
            {{ __('offers.featured.badge') }}
        </span>
    </div>

    {{-- المحتوى: في الوسط على الجوال، وفي جهة النهاية (اليسار بالعربية) على الشاشات الأكبر --}}
    <div class="relative z-10 ms-auto flex w-full flex-col items-center gap-3 p-4 text-center sm:max-w-[27rem] sm:items-end sm:p-7 sm:text-end">

        <h3 class="text-3xl font-extrabold leading-tight text-white [text-shadow:0_2px_14px_rgba(0,0,0,0.7)] sm:text-4xl">
            {{ $title }}
        </h3>

        {{-- صندوق الكود: أيقونة | كود الخصم | فاصل | الكود (يُنسخ عند الضغط) --}}
        @if ($coupon)
            <button type="button" dir="ltr" x-data="{ copied: false, timer: null }"
                x-on:coupon-copied.window="
                    if ($event.detail.promotionId === {{ $promotion->id }}) {
                        copied = true;
                        clearTimeout(timer);
                        timer = setTimeout(() => copied = false, 2000);
                    }
                "
                @click="$dispatch('open-coupon-modal', { promotionId: {{ $promotion->id }}, action: 'copy' })"
                aria-label="{{ __('offers.actions.copy') }}: {{ $coupon->code }}"
                class="inline-flex max-w-full items-center gap-3 rounded-xl border border-dashed border-amber-300/70 bg-black/35 px-4 py-3 text-sm text-white backdrop-blur-sm transition hover:bg-black/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400">
                <span class="shrink-0 text-amber-400">
                    @svg('lucide-ticket', 'h-6 w-6', ['x-show' => '!copied', 'aria-hidden' => 'true'])
                    @svg('lucide-check', 'h-6 w-6', ['x-show' => 'copied', 'x-cloak' => true, 'aria-hidden' => 'true'])
                </span>
                <span dir="auto" class="whitespace-nowrap font-medium">{{ __('offers.coupon_code') }}</span>
                <span aria-hidden="true" class="h-7 shrink-0 border-l border-dashed border-amber-300/50"></span>
                <code class="truncate font-mono text-lg font-bold tracking-wider text-amber-400">{{ $coupon->code }}</code>
                <span class="sr-only" aria-live="polite" x-text="copied ? @js(__('offers.actions.copied')) : ''"></span>
            </button>
        @endif

        {{-- الصلاحية --}}
        @if ($dateLine)
            <p class="flex items-center gap-2 text-sm text-white/85">
                @svg('lucide-clock', 'h-5 w-5 shrink-0 text-amber-400', ['aria-hidden' => 'true'])
                <time datetime="{{ $dateIso }}">{{ $dateLine }}</time>
            </p>
        @endif

        {{-- زر الإجراء --}}
        <button type="button" wire:click="useCoupon({{ $promotion->id }})" wire:loading.attr="disabled"
            wire:target="useCoupon({{ $promotion->id }})"
            class="mt-1 inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full border border-amber-400/70 bg-gradient-to-b from-amber-600 to-amber-800 px-6 text-base font-bold text-white shadow-lg shadow-black/30 transition hover:from-amber-500 hover:to-amber-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-[#2a0d0d] active:scale-[0.98] disabled:cursor-wait disabled:opacity-70">
            <span wire:loading.remove wire:target="useCoupon({{ $promotion->id }})" class="inline-flex items-center gap-3">
                {{ $ctaText }}
                @svg('lucide-arrow-right', 'h-5 w-5 rtl:-scale-x-100', ['aria-hidden' => 'true'])
            </span>
            <span wire:loading wire:target="useCoupon({{ $promotion->id }})"
                class="h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white" role="status"
                aria-label="loading"></span>
        </button>
    </div>
</article>