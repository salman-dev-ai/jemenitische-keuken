@php
    /** @var \App\Models\Promotion $promotion */
    $coupon = $promotion->coupon;
    $badge = $promotion->discountBadge();
    $locale = app()->getLocale();
    $hasVideo = ! empty($promotion->video);
@endphp

<div class="group relative flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-md transition hover:shadow-xl">

    {{-- ═══ الصورة ═══ --}}
    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">

        @if ($promotion->image)
            <img
                src="{{ Storage::disk('public')->url($promotion->image) }}"
                alt="{{ $promotion->getTranslation('title', $locale) }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            >
        @else
            <div class="flex h-full items-center justify-center text-gray-400">
                {{ __('offers.no_image') }}
            </div>
        @endif

        {{-- شارة الخصم (بدون فيديو) --}}
        @if ($badge && ! $hasVideo)
            <div class="absolute end-3 top-3 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-lg">
                <span class="text-lg font-black">{{ $badge }}</span>
            </div>
        @endif

        {{-- شارة فيديو + زر التشغيل --}}
        @if ($hasVideo)
            <div class="absolute end-3 top-3 flex items-center gap-1.5 rounded-full bg-black/60 px-3 py-1 text-xs font-bold text-white backdrop-blur-sm">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                </svg>
                <span>{{ __('offers.video') }}</span>
            </div>

            {{-- ⭐ زر تشغيل الفيديو (يفتح video modal) --}}
            <button
                type="button"
                x-data
                @click="$dispatch('open-video-modal', {
                    url: '{{ Storage::disk('public')->url($promotion->video) }}',
                    title: @js($promotion->getTranslation('title', $locale))
                })"
                class="absolute inset-0 flex items-center justify-center bg-black/20 transition hover:bg-black/30"
                aria-label="{{ __('offers.video') }}"
            >
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 shadow-xl backdrop-blur-sm transition hover:scale-110">
                    <svg class="ms-1 h-7 w-7 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                    </svg>
                </span>
            </button>

            {{-- مدة الفيديو --}}
            @if ($promotion->video_duration)
                <div class="pointer-events-none absolute bottom-3 start-3 rounded-md bg-black/70 px-2 py-0.5 text-xs font-bold text-white">
                    {{ gmdate('i:s', $promotion->video_duration) }}
                </div>
            @endif
        @endif

    </div>

    {{-- ═══ المحتوى ═══ --}}
    <div class="flex flex-1 flex-col p-4">

        {{-- العنوان --}}
        <h3 class="text-center text-lg font-bold text-amber-900">
            {{ $promotion->getTranslation('title', $locale) }}
        </h3>

        {{-- صندوق كود الكوبون + زر النسخ --}}
        @if ($coupon)
            <div
                x-data="{ copied: false }"
                class="mt-3 flex items-center justify-between gap-2 rounded-lg border-2 border-dashed border-amber-300 bg-amber-50/60 px-3 py-2"
            >
                <code class="font-mono text-sm font-bold tracking-wider text-amber-900">
                    {{ $coupon->code }}
                </code>

                <button
                    type="button"
                    @click="
                        navigator.clipboard.writeText(@js($coupon->code));
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="relative flex h-6 w-6 items-center justify-center transition"
                    :class="copied ? 'text-green-600' : 'text-amber-700 hover:text-amber-900'"
                    :title="copied ? '{{ __('offers.actions.copied') }}' : '{{ __('offers.actions.copy') }}'"
                >
                    {{-- أيقونة النسخ --}}
                    <svg
                        x-show="!copied"
                        x-cloak
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>

                    {{-- أيقونة الصح --}}
                    <svg
                        x-show="copied"
                        x-cloak
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- ينتهي قريبًا --}}
        @if ($promotion->ends_at)
            <div class="mt-3 flex items-center justify-center gap-1.5 text-xs font-medium text-red-700">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <span>{{ __('offers.expires_soon') }}</span>
            </div>
        @endif

        {{-- زر استخدم الآن (يفتح coupon modal) --}}
        <button
            type="button"
            wire:click="useCoupon({{ $promotion->id }})"
            class="mt-3 w-full rounded-lg bg-amber-800 px-4 py-3 text-sm font-bold text-white shadow-md transition hover:bg-amber-900 active:scale-[0.98]"
        >
            {{ __('offers.actions.use_now') }}
        </button>

    </div>

</div>