@php
    $locale = app()->getLocale();
    $direction = $locale === 'ar' ? 'rtl' : 'ltr';

    // ساعات العمل: نفس مصدر مكوّن التذييل (Footer::footerData) حتى تظهر هنا أيضاً
    $f = [
        'hours' => \App\Support\OpeningHoursPresenter::rows(
            \App\Models\RestaurantSetting::query()->first()?->getAttribute('opening_hours')
        ),
    ];
@endphp

{{-- مكوّن مناطق التوصيل: لوحات قابلة للطي في الهاتف، ومفتوحة دائماً على الشاشات الأكبر. --}}
<style>[x-cloak]{display:none !important}</style>

<div
    id="delivery-zones"
    lang="{{ $locale }}"
    dir="{{ $direction }}"
    class="relative isolate mx-auto min-h-[460px] overflow-hidden rounded-[28px] border border-white/50 bg-[#e8d5bd] p-4 text-[#38201d] shadow-2xl sm:p-8 lg:p-12"
>
    <img src="{{ asset('images/shibam-delivery-bg.png') }}" alt="" aria-hidden="true" class="absolute inset-0 -z-20 size-full object-cover object-center">
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-r from-[#fff7e7]/25 via-[#fff7e7]/65 to-[#fff7e7]/25"></div>
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-b from-[#3e1e17]/10 via-transparent to-[#3e1e17]/25"></div>

    <section class="relative z-10 mx-auto w-full max-w-screen-2xl" aria-labelledby="delivery-zones-title">

        {{-- ========== رأس الصفحة ========== --}}
        <header class="mb-6 text-center sm:mb-8">
            <div class="mb-4 inline-flex items-center gap-3 rounded-full border border-[#214f70]/20 bg-[#fffdf8]/90 py-2 pe-5 ps-2 text-start shadow-lg backdrop-blur">
                <img src="{{ asset('images/yemeni-restaurant-logo.jpg') }}" alt="{{ __('delivery.logo_alt') }}" width="58" height="58" class="size-14 shrink-0 rounded-full border-2 border-[#d69a50] object-cover sm:size-16">
                <div class="grid gap-1">
                    <strong class="text-base font-black leading-tight text-[#4a1c1c] sm:text-lg">{{ __('delivery.brand_name') }}</strong>
                    <span lang="nl" dir="ltr" class="text-start text-[10px] font-extrabold uppercase tracking-[.1em] text-[#214f70]">{{ __('delivery.brand_locale') }}</span>
                </div>
            </div>

            <p class="mb-2 text-xs font-extrabold text-white [text-shadow:0_2px_7px_rgba(31,19,14,.8)] sm:text-sm">
                {{ __('delivery.origin_slogan') }}
            </p>
            <h2 id="delivery-zones-title" class="relative mx-auto mb-3 table max-w-full rounded-xl border-s-4 border-[#e08a3c] bg-[#fffaf0]/85 px-5 py-2.5 text-2xl font-black leading-tight text-[#4a1c1c] shadow-lg backdrop-blur sm:text-3xl">
                {{ __('delivery.page_title') }}
            </h2>
            <p class="mx-auto max-w-xl text-xs font-bold leading-relaxed text-white [text-shadow:0_1px_6px_rgba(31,19,14,.85)] sm:text-sm">
                {{ __('delivery.description') }}
            </p>
        </header>

        {{-- ========== شاشة التحميل (هيكل عظمي) ========== --}}
        <div wire:loading role="status" aria-live="polite" aria-label="{{ __('delivery.loading') }}" class="overflow-hidden rounded-2xl border-2 border-[#e08a3c] bg-[#fffdf8]/95 shadow-2xl">
            <span class="sr-only">{{ __('delivery.loading') }}</span>
            <div class="flex items-center gap-2 px-5 py-3 text-xs font-semibold text-[#214f70] sm:px-6">
                <span class="size-2 animate-pulse rounded-full bg-[#e08a3c]" aria-hidden="true"></span>
                {{ __('delivery.loading') }}
            </div>
            <ul aria-hidden="true" class="animate-pulse divide-y divide-[#e08a3c]/20">
                @for ($skeletonRow = 0; $skeletonRow < 3; $skeletonRow++)
                    <li wire:key="delivery-zone-skeleton-{{ $skeletonRow }}" class="flex items-center justify-between gap-4 px-5 py-5 sm:px-6">
                        <div class="grid flex-1 gap-2">
                            <span class="block h-4 w-1/2 rounded-full bg-[#eadbc8]"></span>
                            <span dir="ltr" class="block h-3 w-20 rounded-full bg-[#dbe4e8]"></span>
                        </div>
                        <span class="block h-8 w-20 rounded-full bg-[#f0d2ac]"></span>
                    </li>
                @endfor
            </ul>
        </div>

        {{-- ========== المحتوى: لوحتان متجاورتان على الشاشات الكبيرة ========== --}}
        <div wire:loading.remove class="grid grid-cols-1 items-start gap-4 sm:gap-5 lg:grid-cols-5">

            {{-- ---------- لوحة مناطق التوصيل ---------- --}}
            <div
                class="overflow-hidden rounded-2xl border-2 border-[#e08a3c] bg-[#fffdf8]/95 shadow-2xl backdrop-blur lg:col-span-3"
                x-data="{
                    desktop: false,
                    open: true,
                    init() {
                        const mq = window.matchMedia('(min-width: 768px)');
                        const sync = () => { this.desktop = mq.matches; if (mq.matches) this.open = true };
                        sync();
                        mq.addEventListener('change', sync);
                    }
                }"
            >
                <button
                    type="button"
                    @click="if (!desktop) open = !open"
                    :aria-expanded="(open || desktop).toString()"
                    aria-controls="delivery-zones-panel"
                    class="flex w-full items-center gap-3 bg-gradient-to-r from-[#1d4663] to-[#9D4907] px-5 py-4 text-start text-[#fff7ec] focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-white sm:px-6 md:cursor-default"
                >
                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20" aria-hidden="true">
                        <svg viewBox="0 0 24 24" class="size-5 fill-none stroke-current" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11.5a7 7 0 1 0-14 0C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                    </span>
                    <span class="grid flex-1 gap-0.5">
                        <span class="text-sm font-black sm:text-base">{{ __('delivery.zones_title') }}</span>
                        <span class="text-[11px] font-semibold text-[#f6d9b0]">{{ __('delivery.zones_count', ['count' => $this->deliveryZones->count()]) }}</span>
                    </span>
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="size-5 shrink-0 fill-none stroke-current transition-transform duration-300 md:hidden" :class="open ? 'rotate-180' : ''" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div id="delivery-zones-panel" x-show="open || desktop" x-collapse x-cloak>

                    {{-- عرض الهاتف: قائمة بطاقات --}}
                    <ul class="divide-y divide-[#e08a3c]/20 md:hidden">
                        @forelse ($this->deliveryZones as $zone)
                            <li wire:key="delivery-zone-card-{{ $zone->id }}" class="flex items-center justify-between gap-3 px-5 py-4 transition-colors active:bg-[#fdf3e7]">
                                <div class="grid min-w-0 gap-1.5">
                                    <span class="truncate font-black text-[#4a1c1c]">{{ $zone->region_name }}</span>
                                    <button
                                        type="button"
                                        x-data="{ copied: false }"
                                        data-postal="{{ $zone->postal_code }}"
                                        data-copy-label="{{ __('delivery.copy') }}"
                                        data-copied-label="{{ __('delivery.copied') }}"
                                        @click="navigator.clipboard.writeText($el.dataset.postal).then(() => { copied = true; window.setTimeout(() => copied = false, 1400) }).catch(() => {})"
                                        aria-label="{{ __('delivery.copy_postal', ['postcode' => $zone->postal_code]) }}"
                                        class="inline-flex w-fit items-center gap-2 rounded-lg bg-[#edf3f4] px-2.5 py-1 text-xs text-[#4b5963] transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#214f70]"
                                        :class="copied ? '!bg-[#dcefe3] !text-[#1f5a3a]' : ''"
                                    >
                                        <span dir="ltr" class="font-bold tracking-wide">{{ $zone->postal_code }}</span>
                                        <span class="text-[11px] font-semibold text-[#214f70]" aria-live="polite" x-text="copied ? $root.dataset.copiedLabel : $root.dataset.copyLabel">{{ __('delivery.copy') }}</span>
                                    </button>
                                </div>
                                <span dir="ltr" class="shrink-0 rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-4 py-2 text-sm font-black text-white shadow-md">
                                    €{{ number_format((float) $zone->delivery_cost, 2, '.', ',') }}
                                </span>
                            </li>
                        @empty
                            <li class="px-6 py-10 text-center font-semibold text-[#4a1c1c]">{{ __('delivery.empty') }}</li>
                        @endforelse
                    </ul>

                    {{-- عرض الشاشات الأكبر: جدول --}}
                    <div class="hidden overflow-x-auto focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#214f70] md:block" tabindex="0" aria-label="{{ __('delivery.table_label') }}">
                        <table class="w-full table-fixed border-collapse text-[#4a2723]">
                            <thead>
                                <tr class="bg-[#f6e6cf] text-[#54231f]">
                                    <th scope="col" class="w-[38%] px-6 py-3 text-start text-xs font-extrabold">{{ __('delivery.region') }}</th>
                                    <th scope="col" class="w-[32%] px-6 py-3 text-start text-xs font-extrabold">{{ __('delivery.postal_code') }}</th>
                                    <th scope="col" class="w-[30%] px-6 py-3 text-center text-xs font-extrabold">{{ __('delivery.delivery_cost') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/95">
                                @forelse ($this->deliveryZones as $zone)
                                    <tr wire:key="delivery-zone-{{ $zone->id }}" class="border-t border-[#e08a3c]/25 transition-colors duration-200 even:bg-[#fffaf4] hover:bg-[#fdf3e7]">
                                        <td class="px-6 py-4 text-start font-bold text-[#4a1c1c]">{{ $zone->region_name }}</td>
                                        <td class="px-6 py-4 text-start text-[#4b5963]">
                                            <button
                                                type="button"
                                                x-data="{ copied: false }"
                                                data-postal="{{ $zone->postal_code }}"
                                                data-copy-label="{{ __('delivery.copy') }}"
                                                data-copied-label="{{ __('delivery.copied') }}"
                                                @click="navigator.clipboard.writeText($el.dataset.postal).then(() => { copied = true; window.setTimeout(() => copied = false, 1400) }).catch(() => {})"
                                                aria-label="{{ __('delivery.copy_postal', ['postcode' => $zone->postal_code]) }}"
                                                title="{{ __('delivery.copy_postal', ['postcode' => $zone->postal_code]) }}"
                                                class="inline-flex items-center gap-2 rounded-lg px-2 py-1 text-start transition-colors hover:bg-[#edf3f4] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#214f70]"
                                            >
                                                <span dir="ltr">{{ $zone->postal_code }}</span>
                                                <span class="text-[11px] font-semibold text-[#214f70]" aria-live="polite" x-text="copied ? $root.dataset.copiedLabel : $root.dataset.copyLabel">{{ __('delivery.copy') }}</span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span dir="ltr" class="inline-block min-w-[92px] rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-4 py-2 text-sm font-black text-white shadow-md">
                                                €{{ number_format((float) $zone->delivery_cost, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center font-semibold text-[#4a1c1c]">{{ __('delivery.empty') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ---------- بطاقة أوقات الدوام ---------- --}}
            <div class="lg:col-span-2">
                @include('livewire.footer.footer-hours')
            </div>
        </div>

        {{-- ========== زر الطلب السريع ========== --}}
        <div class="mx-auto mt-6 flex w-fit max-w-full flex-wrap items-center justify-center gap-3 rounded-full bg-[#274739]/95 py-2 pe-2 ps-5 text-xs font-bold text-[#fffaf0] shadow-xl backdrop-blur sm:text-sm">
            <span>{{ __('delivery.order_caption') }}</span>
            <a href="{{ config('services.restaurant.order_url', '#menu') }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-5 py-2 font-black text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                {{ __('delivery.order_now') }}
                <span aria-hidden="true" class="rtl:-scale-x-100">{{ __('delivery.order_arrow') }}</span>
            </a>
        </div>
    </section>
</div>
