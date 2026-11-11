@php
    /** @var \App\Models\Promotion $promotion */
    $coupon = $promotion->coupon;
    $locale = app()->getLocale();
    $title = $promotion->getTranslation('title', $locale);
    $imageUrl = $promotion->image ? Storage::disk('public')->url($promotion->image) : null;

    $hasVideo = ! empty($promotion->video);
    $videoUrl = $hasVideo ? Storage::disk('public')->url($promotion->video) : null;

    // سطر الصلاحية: "ينتهي في 22 نوفمبر 2026" (وإن لم يوجد تاريخ انتهاء يُعرض تاريخ البداية)
    $endsAt = $promotion->ends_at;
    $startsAt = $promotion->starts_at;
    $dateLine = $endsAt
        ? __('offers.ends_on', ['date' => $endsAt->locale($locale)->translatedFormat('j F Y')])
        : ($startsAt ? __('offers.valid_from') . ': ' . $startsAt->locale($locale)->translatedFormat('j F Y') : null);
    $dateIso = ($endsAt ?? $startsAt)?->toIso8601String();
@endphp

{{-- بطاقة العرض العادية: صورة بملء البطاقة + تدرج داكن + محتوى في الأسفل --}}
<article
    class="group relative isolate flex h-full min-h-[27rem] min-w-[16rem] flex-col justify-end overflow-hidden rounded-[1.75rem] border border-amber-500/70 bg-[#2a0d0d] shadow-xl shadow-black/30 transition duration-300 motion-safe:hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-900/30  ">

    {{-- الصورة --}}
    @if ($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $title }}" loading="lazy" decoding="async"
            class="absolute inset-0 h-full w-full object-cover transition duration-700 motion-safe:group-hover:scale-105">
    @else
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 40px 40px;">
        </div>
    @endif

    {{-- التدرج السفلي لوضوح النص --}}
    <div class="pointer-events-none absolute inset-0"
        style="background: linear-gradient(to top, rgba(28,6,6,0.97) 0%, rgba(28,6,6,0.78) 30%, rgba(28,6,6,0) 66%);">
    </div>

    {{-- الفيديو (إن وُجد): زر تشغيل صغير + المدة --}}
    @if ($hasVideo)
        <button type="button" x-data
            @click="$dispatch('open-video-modal', { url: @js($videoUrl), title: @js($title) })"
            class="absolute start-1/2 top-[26%] z-10 flex h-14 w-14 -translate-x-1/2 items-center justify-center rounded-full bg-white/90 shadow-xl backdrop-blur-sm transition hover:scale-110 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-amber-400 rtl:translate-x-1/2"
            aria-label="{{ __('offers.video') }}: {{ $title }}">
            @svg('lucide-play', 'ms-0.5 h-6 w-6 fill-current text-red-700', ['aria-hidden' => 'true'])
        </button>

        @if ($promotion->video_duration)
            <div
                class="pointer-events-none absolute end-3 top-3 z-10 flex items-center gap-1.5 rounded-full bg-black/60 px-3 py-1 text-xs font-bold tabular-nums text-white backdrop-blur-sm">
                @svg('lucide-video', 'h-3.5 w-3.5', ['aria-hidden' => 'true'])
                {{ gmdate('i:s', $promotion->video_duration) }}
            </div>
        @endif
    @endif

    {{-- المحتوى --}}
    <div class="relative z-10 flex flex-col items-center gap-3 p-4 text-center sm:p-5">

        <h3 class="line-clamp-2 text-2xl font-extrabold leading-snug text-white [text-shadow:0_2px_12px_rgba(0,0,0,0.65)]">
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
                class="inline-flex max-w-full items-center gap-3 rounded-xl border border-dashed border-white/55 bg-black/30 px-4 py-2.5 text-sm text-white backdrop-blur-sm transition hover:bg-black/45 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400">
                <span class="shrink-0 text-amber-400">
                    @svg('lucide-ticket', 'h-5 w-5', ['x-show' => '!copied', 'aria-hidden' => 'true'])
                    @svg('lucide-check', 'h-5 w-5', ['x-show' => 'copied', 'x-cloak' => true, 'aria-hidden' => 'true'])
                </span>
                <span dir="auto" class="whitespace-nowrap font-medium">{{ __('offers.coupon_code') }}</span>
                <span aria-hidden="true" class="h-6 shrink-0 border-l border-dashed border-white/45"></span>
                <code class="truncate font-mono text-base font-bold tracking-wider">{{ $coupon->code }}</code>
                <span class="sr-only" aria-live="polite" x-text="copied ? @js(__('offers.actions.copied')) : ''"></span>
            </button>
        @endif

        {{-- الصلاحية --}}
        @if ($dateLine)
            <p class="flex items-center gap-2 text-sm text-white/85">
                @svg('lucide-clock', 'h-4 w-4 shrink-0 text-amber-400', ['aria-hidden' => 'true'])
                <time datetime="{{ $dateIso }}">{{ $dateLine }}</time>
            </p>
        @endif

        {{-- زر استخدم الآن --}}
        <button type="button" wire:click="useCoupon({{ $promotion->id }})" wire:loading.attr="disabled"
            wire:target="useCoupon({{ $promotion->id }})"
            class="mt-1 inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full border border-amber-400/70 bg-gradient-to-b from-amber-600 to-amber-800 px-6 text-base font-bold text-white shadow-lg shadow-black/30 transition hover:from-amber-500 hover:to-amber-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-300 focus-visible:ring-offset-2 focus-visible:ring-offset-[#2a0d0d] active:scale-[0.98] disabled:cursor-wait disabled:opacity-70">
            <span wire:loading.remove wire:target="useCoupon({{ $promotion->id }})" class="inline-flex items-center gap-3">
                {{ __('offers.actions.use_now') }}
                @svg('lucide-arrow-right', 'h-5 w-5 rtl:-scale-x-100', ['aria-hidden' => 'true'])
            </span>
            <span wire:loading wire:target="useCoupon({{ $promotion->id }})"
                class="h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white" role="status"
                aria-label="loading"></span>
        </button>
    </div>
</article>