{{--
    صفحة "معلومات المطعم": أوقات الدوام + قاموس مسببات الحساسية والعلامات الغذائية + جدول التوصيل.
    تقرأ كل البيانات من الخصائص المحسوبة للمكوّن، وتعيد استخدام جزئية ساعات العمل نفسها
    المستخدمة في التذييل حتى لا يتكرر منطق العرض.
--}}

@php
    $locale = app()->getLocale();
    $direction = in_array($locale, ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr';

    // نفس مصدر التذييل: صفوف الساعات جاهزة للعرض في بطاقة footer-hours.
    $f = ['hours' => $this->hoursRows];

    $setting = $this->setting;
    $groups = $this->allergenGroups;
    $hasAllergens = $groups['allergens']->isNotEmpty() || $groups['labels']->isNotEmpty();
@endphp

<div id="restaurant-info" lang="{{ $locale }}" dir="{{ $direction }}"
    class="relative isolate mx-auto overflow-hidden rounded-[28px] border border-white/50 bg-[#e8d5bd] p-4 text-[#38201d] shadow-2xl sm:p-8 lg:p-12">
    <img src="{{ asset('images/shibam-delivery-bg.webp') }}" alt="" aria-hidden="true" width="1920" height="823"
        decoding="async" class="absolute inset-0 -z-20 size-full object-cover object-center">
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-r from-[#fff7e7]/25 via-[#fff7e7]/70 to-[#fff7e7]/25"></div>
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-gradient-to-b from-[#3e1e17]/10 via-transparent to-[#3e1e17]/25"></div>

    <section class="relative z-10 mx-auto w-full max-w-screen-2xl" aria-labelledby="restaurant-info-title">

        {{-- ========== رأس الصفحة ========== --}}
        <header class="mb-6 text-center sm:mb-8">
            <div class="mb-4 inline-flex items-center gap-3 rounded-full border border-[#214f70]/20 bg-[#fffdf8]/90 py-2 pe-5 ps-2 text-start shadow-lg backdrop-blur">
                <img src="{{ asset('images/yemeni-restaurant-logo.jpg') }}" alt="{{ __('info.logo_alt') }}"
                    width="58" height="58"
                    class="size-14 shrink-0 rounded-full border-2 border-[#d69a50] object-cover sm:size-16">
                <div class="grid gap-1">
                    <strong class="text-base font-black leading-tight text-[#4a1c1c] sm:text-lg">
                        {{ $setting->localized_name ?: __('footer.default_name') }}
                    </strong>
                    <span lang="nl" dir="ltr"
                        class="text-start text-[10px] font-extrabold uppercase tracking-[.1em] text-[#214f70]">
                        {{ __('delivery.brand_locale') }}
                    </span>
                </div>
            </div>

            <h1 id="restaurant-info-title"
                class="relative mx-auto mb-3 table max-w-full rounded-xl border-s-4 border-[#e08a3c] bg-[#fffaf0]/85 px-5 py-2.5 text-2xl font-black leading-tight text-[#4a1c1c] shadow-lg backdrop-blur sm:text-3xl">
                {{ __('info.page_title') }}
            </h1>
            <p class="mx-auto max-w-2xl text-xs font-bold leading-relaxed text-white [text-shadow:0_1px_6px_rgba(31,19,14,.85)] sm:text-sm">
                {{ __('info.page_subtitle') }}
            </p>
 
        </header>

        {{-- ========== أوقات الدوام + قاموس الحساسية ========== --}}
        <div class="grid grid-cols-1 items-start gap-4 sm:gap-5 lg:grid-cols-2">

            {{-- ---------- بطاقة أوقات الدوام ---------- --}}
            @include('livewire.footer.footer-hours')

            {{-- ---------- قاموس مسببات الحساسية والعلامات الغذائية ---------- --}}
            <div class="overflow-hidden rounded-2xl border-2 border-[#9D4907]/40 bg-[#fbf3e8]/95 shadow-2xl backdrop-blur">
                <div class="flex items-center gap-3 bg-gradient-to-r from-[#1d4663] to-[#9D4907] px-5 py-4 text-start text-white sm:px-6">
                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20" aria-hidden="true">
                        @svg('lucide-shield-alert', 'size-5', ['aria-hidden' => 'true'])
                    </span>
                    <span class="grid flex-1 gap-0.5">
                        <span class="text-xs font-black sm:text-base">{{ __('info.allergens_title') }}</span>
                        <span class="text-[11px] font-semibold text-[#f6d9b0]">
                            {{ __('info.allergens_subtitle', ['count' => $this->allergenCatalog->count()]) }}
                        </span>
                    </span>
                </div>

                <div class="p-4 sm:p-5">
                    @if (! $hasAllergens)
                        <p class="text-sm leading-6 text-[#6a5444]">{{ __('info.allergens_empty') }}</p>
                    @else
                        @foreach ([
                            ['key' => 'allergens', 'label' => __('info.allergens_group'), 'icon' => 'lucide-triangle-alert', 'headingClass' => 'text-[#8a2a1f]'],
                            ['key' => 'labels', 'label' => __('info.labels_group'), 'icon' => 'lucide-badge-check', 'headingClass' => 'text-[#1f5a3a]'],
                        ] as $group)
                            @if ($groups[$group['key']]->isNotEmpty())
                                <div @class(['mt-5' => ! $loop->first])>
                                    <h2 class="mb-2 flex items-center gap-2 text-xs font-black {{ $group['headingClass'] }}">
                                        @svg($group['icon'], 'size-4', ['aria-hidden' => 'true'])
                                        {{ $group['label'] }}
                                    </h2>

                                    <ul class="flex flex-wrap gap-2">
                                        @foreach ($groups[$group['key']] as $allergen)
                                            @php
                                                $iconName = $allergen->iconName();

                                                // صورة مرفوعة أولاً، وإلا أيقونة Lucide المحفوظة في القاموس.
                                                $svgMarkup = (! $allergen->hasImage() && $iconName !== null)
                                                    ? rescue(fn () => svg($iconName, 'size-5')->toHtml(), null, false)
                                                    : null;

                                                $chipClasses = $allergen->isDietaryLabel()
                                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
                                                    : 'border-amber-200 bg-amber-50 text-amber-800';
                                            @endphp

                                            <li class="flex items-center gap-2 rounded-xl border {{ $chipClasses }} px-3 py-2 shadow-sm">
                                                @if ($allergen->hasImage())
                                                    <img src="{{ Storage::disk('public')->url($allergen->image_path) }}"
                                                        alt="" loading="lazy" class="size-5 shrink-0 object-contain">
                                                @elseif ($svgMarkup)
                                                    <span class="shrink-0" aria-hidden="true">{!! $svgMarkup !!}</span>
                                                @else
                                                    @svg('lucide-info', 'size-5 shrink-0', ['aria-hidden' => 'true'])
                                                @endif

                                                <span class="text-xs font-bold leading-none sm:text-sm">
                                                    {{ $allergen->localized_name }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach

                        <p class="mt-5 flex items-start gap-2 rounded-xl bg-white/70 p-3 text-[12px] leading-5 text-[#A84A13] font-bold ring-1 ring-[#e8d5bd]">
                            @svg('lucide-info', 'size-4 shrink-0 text-[#c05a1c]', ['aria-hidden' => 'true'])
                            <span>{{ __('info.allergens_note') }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========== جدول مناطق التوصيل ========== --}}
        <div class="mt-4 overflow-hidden rounded-2xl border-2 border-[#e08a3c] bg-[#fffdf8]/95 shadow-2xl backdrop-blur sm:mt-5">
            <div class="flex items-center gap-3 bg-gradient-to-r from-[#1d4663] to-[#9D4907] px-5 py-4 text-start text-[#fff7ec] sm:px-6">
                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-white/10 ring-1 ring-white/20" aria-hidden="true">
                    @svg('lucide-truck', 'size-5', ['aria-hidden' => 'true'])
                </span>
                <span class="grid flex-1 gap-0.5">
                    <span class="text-sm font-black sm:text-base">{{ __('info.delivery_title') }}</span>
                    <span class="text-[11px] font-semibold text-[#f6d9b0]">
                        {{ __('info.delivery_subtitle', ['count' => $this->deliveryZones->count()]) }}
                    </span>
                </span>
            </div>

            {{-- عرض الهاتف: قائمة بطاقات --}}
            <ul class="divide-y divide-[#e08a3c]/20 md:hidden">
                @forelse ($this->deliveryZones as $zone)
                    <li wire:key="restaurant-info-zone-{{ $zone->id }}" class="flex items-center justify-between gap-3 px-5 py-4">
                        <div class="grid min-w-0 gap-1">
                            <span class="truncate font-black text-[#4a1c1c]">{{ $zone->region_name }}</span>
                            <span dir="ltr" class="text-xs font-semibold text-[#4b5963]">{{ $zone->postal_code }}</span>
                            <span class="text-[11px] font-semibold text-[#6a5444]">
                                {{ __('delivery.minimum_order') }}: €{{ number_format((float) $zone->minimum_order, 2, '.', ',') }}
                            </span>
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
            <div class="hidden overflow-x-auto focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#214f70] md:block"
                tabindex="0" aria-label="{{ __('delivery.table_label') }}">
                <table class="w-full table-fixed border-collapse text-[#4a2723]">
                    <thead>
                        <tr class="bg-[#f6e6cf] text-[#54231f]">
                            <th scope="col" class="w-[22%] px-6 py-3 text-start text-xs font-extrabold">{{ __('delivery.postal_code') }}</th>
                            <th scope="col" class="w-[30%] px-6 py-3 text-start text-xs font-extrabold">{{ __('delivery.region') }}</th>
                            <th scope="col" class="w-[23%] px-6 py-3 text-center text-xs font-extrabold">{{ __('delivery.minimum_order') }}</th>
                            <th scope="col" class="w-[25%] px-6 py-3 text-center text-xs font-extrabold">{{ __('delivery.delivery_cost') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/95">
                        @forelse ($this->deliveryZones as $zone)
                            <tr wire:key="restaurant-info-zone-row-{{ $zone->id }}"
                                class="border-t border-[#e08a3c]/25 transition-colors duration-200 even:bg-[#fffaf4] hover:bg-[#fdf3e7]">
                                <td dir="ltr" class="px-6 py-4 text-start font-bold text-[#4b5963]">{{ $zone->postal_code }}</td>
                                <td class="px-6 py-4 text-start font-bold text-[#4a1c1c]">{{ $zone->region_name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span dir="ltr" class="inline-block min-w-[92px] rounded-full bg-[#edf3f4] px-4 py-2 text-sm font-black text-[#214f70] shadow-sm">
                                        €{{ number_format((float) $zone->minimum_order, 2, '.', ',') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span dir="ltr" class="inline-block min-w-[92px] rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-4 py-2 text-sm font-black text-white shadow-md">
                                        €{{ number_format((float) $zone->delivery_cost, 2, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center font-semibold text-[#4a1c1c]">{{ __('delivery.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="border-t border-[#e08a3c]/20 bg-[#fff7e7]/80 px-5 py-3 text-[11px] font-semibold text-[#6a5444] sm:px-6">
                {{ __('info.delivery_note') }}
            </p>
        </div>

        {{-- ========== دعوة للطلب ========== --}}
        <div class="mx-auto mt-6 flex w-fit max-w-full flex-wrap items-center justify-center gap-3 rounded-full bg-[#274739]/95 py-2 pe-2 ps-5 text-xs font-bold text-[#fffaf0] shadow-xl backdrop-blur sm:text-sm">
            <span>{{ __('info.order_caption') }}</span>
            <a href="{{ config('services.restaurant.order_url', '#menu') }}"
                class="inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-gradient-to-r from-[#e9a34b] to-[#d77b2a] px-5 py-2 font-black text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                {{ __('info.order_now') }}
                <span aria-hidden="true" class="rtl:-scale-x-100">{{ __('info.order_arrow') }}</span>
            </a>
        </div>
    </section>
</div>
