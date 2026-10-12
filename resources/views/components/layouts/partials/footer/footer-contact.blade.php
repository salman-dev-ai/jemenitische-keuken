{{--
    بطاقة التواصل في التذييل.
    تعرض العنوان والهاتف والبريد، ثم روابط تيك توك وواتساب
    وزر فتح الموقع على خريطة جوجل عند توفر الرابط.
--}}
@php
    extract($footerLayout ?? [], EXTR_SKIP);
@endphp

<section
    class="max-h-70 min-h-[200px] rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
    <h2 class="flex items-center gap-2 text-[15px] font-black text-white">
        <span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>
        {{ $translate('messages.footer.contact', 'تواصل معنا') }}
    </h2>

    {{-- العنوان والهاتف والبريد إن وُجدت --}}
    <div class="mt-4 space-y-2 text-[13px] font-medium leading-5 text-white/85">
        @if ($address || $city || $postalCode)
            <p class="flex items-center gap-2">
                <x-lucide-map-pin class="h-4 w-4 shrink-0 text-[#efa452]" />
                <span>
                    {{ $address }}@if ($city)
                        , {{ $city }}
                    @endif
                    @if ($postalCode)
                        , {{ $postalCode }}
                    @endif
                </span>
            </p>
        @endif
        @if ($phone && $phoneHref)
            <a dir="ltr" href="tel:{{ $phoneHref }}" class="block w-fit transition-colors hover:text-[#f1ae62]">
                {{ $phone }}
            </a>
        @endif
        @if ($email)
            <a dir="ltr" href="mailto:{{ $email }}" class="block w-fit transition-colors hover:text-[#f1ae62]">
                {{ $email }}
            </a>
        @endif
    </div>

    {{-- روابط التواصل الاجتماعي --}}
    <div class="mt-4 flex items-center justify-center gap-2">
        <a href="#" aria-label="TikTok"
            class="flex h-11 w-11 items-center justify-center rounded-xl border border-cyan-200 bg-[#10131d] text-white shadow-[0_0_12px_rgba(84,231,224,.22)] transition hover:-translate-y-0.5">
            <x-lucide-music-2 class="h-5 w-5" />
        </a>
        @if ($whatsapp && $whatsappHref !== 'https://wa.me/')
            <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
                class="flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-200 bg-[#079b67] text-white transition hover:-translate-y-0.5 hover:bg-[#12b77d]">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                </svg>
            </a>
        @endif
    </div>

    {{-- فتح الموقع على خريطة جوجل --}}
    @if ($mapsLink)
        <a href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer"
            class="mx-auto mt-3 flex h-9 w-fit items-center gap-1.5 rounded-xl border border-[#d89145] bg-[#804315]/60 px-3 text-[12px] font-bold text-[#f0b66d] transition hover:bg-[#a85a1d]/70">
            <x-lucide-map-pin class="h-4 w-4" />
            {{ $translate('messages.footer.viewMap', 'الموقع على خريطة جوجل') }}
        </a>
    @endif
</section>
