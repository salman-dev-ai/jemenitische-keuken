{{--
أزرار التفاعل لسطح المكتب فقط.
واتساب، التوصيل، Thuisbezorgd، الحجز، وقائمة اللغة.
مخفية تحت نقطة lg وتظهر بجانبها.
--}}

@php
    $f = $footerLayout ?? [];
    extract($f, EXTR_SKIP);
    $whatsappHref = (string) ($whatsappHref ?? ($f['whatsapp_href'] ?? ''));
@endphp

<div class="hidden shrink-0 items-center gap-3 lg:flex">

    {{-- اختصار مناطق التوصيل --}}
    <a href="#delivery-zones" @click="activeSection = 'delivery-zones'" aria-label="مناطق التوصيل"
        class="relative z-10 block shrink-0">
        <div class="relative flex h-16 w-20 items-center justify-center overflow-visible lg:h-20 lg:w-24">
            <img src="{{ asset('images/scooter_delivery_3d_with_driver.webp') }}" alt="سكوتر التوصيل مع السائق"
                class="h-full w-full object-contain object-center drop-shadow-md">
        </div>
    </a>

    @if ($whatsappHref !== '')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
            class="inline-flex h-10 animate-pulse items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 text-xs font-bold text-emerald-700 transition hover:-translate-y-0.5 hover:border-emerald-500">
            {{ __('footer.whatsapp') }}
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
            </svg>
        </a>
    @endif

    {{-- الطلب عبر Thuisbezorgd --}}
    <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 p-2 shadow-sm transition-all hover:scale-105 hover:bg-gray-100">
        <img src="{{ asset('images/thuisbezorgd.png') }}" alt="Thuisbezorgd" class="h-full w-full object-contain" />
    </a>

    {{-- الانتقال إلى قسم الحجز --}}
    <a href="#reservation"
        class="inline-flex h-10 shrink-0 animate-pulse items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-5 text-sm font-bold text-white shadow-md shadow-[#E07513]/25 transition-all hover:-translate-y-0.5">
        {{ __('messages.nav.bookTableBtn') }}
    </a>

    {{-- قائمة اللغة المحلية مع إغلاق عند الضغط خارجها --}}
    <div class="relative shrink-0" x-data="{ open: false }">
        <button @click="open = !open" @keydown.escape.window="open = false" type="button"
            class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/50 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
            <span>{{ app()->getLocale() == 'ar' ? 'العربية' : (app()->getLocale() == 'nl' ? 'Nederlands' : 'English') }}</span>
            <svg class="h-3.5 w-3.5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="absolute right-0 z-50 mt-2 w-32 rounded-xl border border-gray-100 bg-white/95 py-1 text-xs shadow-2xl backdrop-blur-md">
            <a wire:navigate href="{{ route('lang.switch', 'ar') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">العربية</a>
            <a wire:navigate href="{{ route('lang.switch', 'nl') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">Nederlands</a>
            <a wire:navigate href="{{ route('lang.switch', 'en') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">English</a>
        </div>
    </div>
</div>
