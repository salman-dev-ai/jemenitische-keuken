{{--
    بطاقات الموقع والتواصل والمتابعة. تعرض العنوان وزر الخريطة، الهاتف والبريد،
    وروابط تيك توك وواتساب عند توفرها.
--}}
{{-- بطاقة الموقع وزر الخريطة --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.location') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            @svg('lucide-map-pin', 'h-5 w-5', ['aria-hidden' => 'true'])
        </span>
    </header>

    @if ($f['address'] || $f['city_line'])
        <address class="mt-4 space-y-1 text-sm not-italic leading-7 text-[#6a5444]">
            @if ($f['address'])
                <p>{{ $f['address'] }}</p>
            @endif
            @if ($f['city_line'])
                <p>{{ $f['city_line'] }}</p>
            @endif
        </address>
    @else
        <p class="mt-4 text-sm text-[#6a5444]">{{ __('footer.address_unavailable') }}</p>
    @endif

    @if ($f['maps_link'])
        <a href="{{ $f['maps_link'] }}" target="_blank" rel="noopener noreferrer"
            class="mt-4 inline-flex h-10 items-center gap-2 rounded-xl border border-[#c05a1c] px-4 text-xs font-bold text-[#c05a1c] transition hover:bg-[#c05a1c] hover:text-white">
            @svg('lucide-map-pin', 'h-4 w-4', ['aria-hidden' => 'true'])
            {{ __('footer.view_map') }}
        </a>
    @endif
</section>

{{-- بطاقة الهاتف والبريد الإلكتروني --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.contact') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            @svg('lucide-phone', 'h-5 w-5', ['aria-hidden' => 'true'])
        </span>
    </header>

    <div class="mt-4 space-y-3 text-sm font-medium text-[#2b1810]">
        @if ($f['phone'] && $f['phone_href'])
            <a dir="ltr" href="tel:{{ $f['phone_href'] }}"
                class="flex items-center gap-2 transition hover:text-[#c05a1c]">
                @svg('lucide-phone', 'h-4 w-4 shrink-0 text-[#c05a1c]', ['aria-hidden' => 'true'])
                <span>{{ $f['phone'] }}</span>
            </a>
        @endif
        @if ($f['email'])
            <a dir="ltr" href="mailto:{{ $f['email'] }}"
                class="flex items-center gap-2 break-all transition hover:text-[#c05a1c]">
                @svg('lucide-mail', 'h-4 w-4 shrink-0 text-[#c05a1c]', ['aria-hidden' => 'true'])
                <span>{{ $f['email'] }}</span>
            </a>
        @endif
        @if (! $f['phone'] && ! $f['email'])
            <p class="text-[#6a5444]">{{ __('footer.contact_unavailable') }}</p>
        @endif
    </div>
</section>

{{-- بطاقة التابعة والتواصل الاجتماعي --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.follow') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            @svg('icon-whatsapp', 'h-5 w-5', ['aria-hidden' => 'true'])
        </span>
    </header>

    <div class="mt-4 flex flex-wrap items-center gap-2">
        @if ($f['tiktok'])
            <a href="{{ $f['tiktok'] }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"
                class="inline-flex h-10 items-center gap-2 rounded-full border border-[#e2c3a0] bg-white px-4 text-xs font-bold text-[#2b1810] transition hover:-translate-y-0.5 hover:border-[#c05a1c]">
                {{ __('footer.tiktok') }}
                @svg('lucide-music-2', 'h-4 w-4', ['aria-hidden' => 'true'])
            </a>
        @endif
        @if ($f['whatsapp_href'])
            <a href="{{ $f['whatsapp_href'] }}" target="_blank" rel="noopener noreferrer"
                aria-label="WhatsApp"
                class="inline-flex h-10 items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 text-xs font-bold text-emerald-700 transition hover:-translate-y-0.5 hover:border-emerald-500">
                {{ __('footer.whatsapp') }}
                @svg('icon-whatsapp', 'h-4 w-4', ['aria-hidden' => 'true'])
            </a>
        @endif
    </div>

    @if (! $f['tiktok'] && ! $f['whatsapp_href'])
        <p class="mt-4 text-xs text-[#6a5444]">{{ __('footer.follow_note') }}</p>
    @endif
</section>
