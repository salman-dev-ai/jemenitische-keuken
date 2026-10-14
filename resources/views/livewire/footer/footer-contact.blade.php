{{--
    بطاقات الموقع والتواصل والمتابعة. تعرض العنوان وزر الخريطة، الهاتف والبريد،
    وروابط تيك توك وواتساب عند توفرها.
--}}
{{-- بطاقة الموقع وزر الخريطة --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.location') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            <x-lucide-map-pin class="h-5 w-5" />
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
            <x-lucide-map-pin class="h-4 w-4" />
            {{ __('footer.view_map') }}
        </a>
    @endif
</section>

{{-- بطاقة الهاتف والبريد الإلكتروني --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.contact') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            <x-lucide-phone class="h-5 w-5" />
        </span>
    </header>

    <div class="mt-4 space-y-3 text-sm font-medium text-[#2b1810]">
        @if ($f['phone'] && $f['phone_href'])
            <a dir="ltr" href="tel:{{ $f['phone_href'] }}"
                class="flex items-center gap-2 transition hover:text-[#c05a1c]">
                <x-lucide-phone class="h-4 w-4 shrink-0 text-[#c05a1c]" />
                <span>{{ $f['phone'] }}</span>
            </a>
        @endif
        @if ($f['email'])
            <a dir="ltr" href="mailto:{{ $f['email'] }}"
                class="flex items-center gap-2 break-all transition hover:text-[#c05a1c]">
                <x-lucide-mail class="h-4 w-4 shrink-0 text-[#c05a1c]" />
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
            <x-lucide-message-circle class="h-5 w-5" />
        </span>
    </header>

    <div class="mt-4 flex flex-wrap items-center gap-2">
        @if ($f['tiktok'])
            <a href="{{ $f['tiktok'] }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"
                class="inline-flex h-10 items-center gap-2 rounded-full border border-[#e2c3a0] bg-white px-4 text-xs font-bold text-[#2b1810] transition hover:-translate-y-0.5 hover:border-[#c05a1c]">
                {{ __('footer.tiktok') }}
                <x-lucide-music-2 class="h-4 w-4" />
            </a>
        @endif
        @if ($f['whatsapp_href'])
            <a href="{{ $f['whatsapp_href'] }}" target="_blank" rel="noopener noreferrer"
                aria-label="WhatsApp"
                class="inline-flex h-10 items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 text-xs font-bold text-emerald-700 transition hover:-translate-y-0.5 hover:border-emerald-500">
                {{ __('footer.whatsapp') }}
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                </svg>
            </a>
        @endif
    </div>

    @if (! $f['tiktok'] && ! $f['whatsapp_href'])
        <p class="mt-4 text-xs text-[#6a5444]">{{ __('footer.follow_note') }}</p>
    @endif
</section>
