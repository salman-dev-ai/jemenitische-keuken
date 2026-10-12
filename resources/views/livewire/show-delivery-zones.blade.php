@php
    $locale = app()->getLocale();
    $direction = $locale === 'ar' ? 'rtl' : 'ltr';
@endphp

<div
    id="delivery-zones"
    lang="{{ $locale }}"
    dir="{{ $direction }}"
    class="relative isolate mx-auto min-h-[460px] overflow-hidden rounded-[28px] border border-white/50 bg-[#e8d5bd] p-5 text-[#38201d] shadow-2xl transition-opacity duration-200 sm:p-8 lg:p-12"
>
    <img
        src="{{ asset('images/shibam-delivery-bg.png') }}"
        alt=""
        aria-hidden="true"
        class="absolute inset-0 -z-20 size-full object-cover object-center"
    >
    <div
        aria-hidden="true"
        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#fff7e7]/25 via-[#fff7e7]/65 to-[#fff7e7]/25"
    ></div>
    <div
        aria-hidden="true"
        class="absolute inset-0 -z-10 bg-gradient-to-b from-[#3e1e17]/5 via-transparent to-[#3e1e17]/15"
    ></div>
    <img
        src="{{ asset('images/yemeni-restaurant-logo.jpg') }}"
        alt=""
        aria-hidden="true"
        class="pointer-events-none absolute left-1/2 top-24 z-0 size-36 -translate-x-1/2 rounded-full border-[5px] border-[#fffaf0]/40 object-cover opacity-40 shadow-xl sm:size-40"
    >

    <section class="relative z-10 mx-auto w-full max-w-screen-2xl" aria-labelledby="delivery-zones-title">
        <header class="mb-5 text-center sm:mb-6">
            <div class="mb-3 inline-flex items-center gap-3 rounded-full border border-[#214f70]/20 bg-[#fffdf8]/90 py-2 pe-5 ps-2 text-start shadow-lg">
                <img
                    src="{{ asset('images/yemeni-restaurant-logo.jpg') }}"
                    alt="{{ __('delivery.logo_alt') }}"
                    width="58"
                    height="58"
                    class="size-14 shrink-0 rounded-full border-2 border-[#d69a50] object-cover sm:size-16"
                >
                <div class="grid gap-1">
                    <strong class="text-base font-black leading-tight text-[#4a1c1c] sm:text-lg">
                        {{ __('delivery.brand_name') }}
                    </strong>
                    <span lang="nl" dir="ltr" class="text-start text-[10px] font-extrabold uppercase tracking-[.1em] text-[#214f70]">
                        {{ __('delivery.brand_locale') }}
                    </span>
                </div>
            </div>

            <p class="mb-1 text-xs font-extrabold text-white [text-shadow:0_2px_7px_rgba(31,19,14,.8)] sm:text-sm">
                {{ __('delivery.origin_slogan') }}
            </p>
            <h2 id="delivery-zones-title" class="relative mx-auto mb-2 table max-w-full rounded-md border-s-4 border-[#e08a3c] bg-[#fffaf0]/80 px-4 py-2 text-2xl font-black leading-tight text-[#4a1c1c] shadow-lg sm:text-3xl">
                {{ __('delivery.page_title') }}
            </h2>
            <p class="text-xs font-bold text-white [text-shadow:0_1px_6px_rgba(31,19,14,.82)] sm:text-sm">
                {{ __('delivery.description') }}
            </p>
        </header>

        <div wire:loading role="status" aria-live="polite" aria-label="{{ __('delivery.loading') }}" class="overflow-hidden rounded-2xl border-2 border-[#e08a3c] bg-[#fffdf8]/95 shadow-2xl">
            <span class="sr-only">{{ __('delivery.loading') }}</span>
            <div class="flex items-center gap-2 px-5 py-3 text-xs font-semibold text-[#214f70] sm:px-6">
                <span class="size-2 animate-pulse rounded-full bg-[#e08a3c]" aria-hidden="true"></span>
                {{ __('delivery.loading') }}
            </div>
            <div class="overflow-x-auto">
                <table aria-hidden="true" class="w-full min-w-[620px] table-fixed border-collapse text-[#4a2723]">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#54231f] to-[#3e1d1b] text-[#fff7ec]">
                            <th scope="col" class="w-[34%] px-5 py-4 text-start text-xs font-extrabold sm:px-6 sm:text-sm">{{ __('delivery.region') }}</th>
                            <th scope="col" class="w-[33%] px-5 py-4 text-start text-xs font-extrabold sm:px-6 sm:text-sm">{{ __('delivery.postal_code') }}</th>
                            <th scope="col" class="w-[33%] px-5 py-4 text-center text-xs font-extrabold sm:px-6 sm:text-sm">{{ __('delivery.delivery_cost') }}</th>
                        </tr>
                    </thead>
                    <tbody class="animate-pulse bg-white/95">
                        @for ($skeletonRow = 0; $skeletonRow < 3; $skeletonRow++)
                            <tr wire:key="delivery-zone-skeleton-{{ $skeletonRow }}" class="border-t border-[#e08a3c]/25 even:bg-[#fffaf4]">
                                <td class="px-5 py-5 sm:px-6"><span class="block h-4 w-2/3 rounded-full bg-[#eadbc8]"></span></td>
                                <td class="px-5 py-5 sm:px-6"><span dir="ltr" class="block h-4 w-24 rounded-full bg-[#dbe4e8]"></span></td>
                                <td class="px-5 py-5 sm:px-6"><span class="mx-auto block h-8 w-24 rounded-full bg-[#f0d2ac]"></span></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div wire:loading.remove class="overflow-hidden rounded-2xl border-2 border-[#e08a3c] bg-[#fffdf8]/95 shadow-2xl">
            <div class="overflow-x-auto focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#214f70]" tabindex="0" aria-label="{{ __('delivery.table_label') }}">
                <table class="w-full min-w-[620px] table-fixed border-collapse text-[#4a2723]">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#54231f] to-[#3e1d1b] text-[#fff7ec]">
                            <th scope="col" class="w-[34%] px-5 py-4 text-start text-xs font-extrabold sm:px-6 sm:text-sm">
                                {{ __('delivery.region') }}
                            </th>
                            <th scope="col" class="w-[33%] px-5 py-4 text-start text-xs font-extrabold sm:px-6 sm:text-sm">
                                {{ __('delivery.postal_code') }}
                            </th>
                            <th scope="col" class="w-[33%] px-5 py-4 text-center text-xs font-extrabold sm:px-6 sm:text-sm">
                                {{ __('delivery.delivery_cost') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/95">
                        @forelse ($this->deliveryZones as $zone)
                            <tr wire:key="delivery-zone-{{ $zone->id }}" class="border-t border-[#e08a3c]/25 transition-colors duration-200 even:bg-[#fffaf4] hover:bg-[#fdf3e7]">
                                <td class="px-5 py-4 text-start font-bold text-[#4a1c1c] sm:px-6">
                                    {{ $zone->region_name }}
                                </td>
                                <td class="px-5 py-4 text-start text-[#4b5963] sm:px-6">
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
                                <td class="px-5 py-4 text-center sm:px-6">
                                    <span dir="ltr" class="inline-block min-w-[92px] rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-4 py-2 text-sm font-black text-white shadow-md">
                                        €{{ number_format((float) $zone->delivery_cost, 2, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center font-semibold text-[#4a1c1c]">
                                    {{ __('delivery.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mx-auto mt-4 flex w-fit max-w-full flex-wrap items-center justify-center gap-3 rounded-full bg-[#274739]/95 py-2 pe-2 ps-4 text-xs font-bold text-[#fffaf0] shadow-lg sm:text-sm">
            <span>{{ __('delivery.order_caption') }}</span>
            <a href="{{ config('services.restaurant.order_url', '#menu') }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-5 py-2 font-black text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                {{ __('delivery.order_now') }}
                <span aria-hidden="true">{{ __('delivery.order_arrow') }}</span>
            </a>
        </div>

    </section>
</div>
