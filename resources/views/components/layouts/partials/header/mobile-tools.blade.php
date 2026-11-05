{{--
أدوات الهيدر للجوال فقط.
واتساب، التوصيل، Thuisbezorgd، الحجز، واللغة.
تختفي ابتداءً من عرض lg حيث تظهر نسخة سطح المكتب.
--}}

@php
    $f = $footerLayout ?? [];
    extract($f, EXTR_SKIP);
    $whatsapp = (string) ($whatsapp ?? ($f['whatsapp'] ?? ''));
    $whatsappHref = (string) ($whatsappHref ?? ($f['whatsapp_href'] ?? ''));
@endphp

<div class="flex items-center gap-1.5 sm:gap-2 lg:hidden">

    {{-- اختصار مناطق التوصيل --}}
    <a href="#delivery-zones" @click="activeSection = 'delivery-zones'" aria-label="مناطق التوصيل"
        class="relative z-10 block shrink-0">
        <div
            class="hidden sm:flex relative  h-10 w-10 items-center justify-center overflow-visible sm:h-20 sm:w-20 lg:h-20 lg:w-24">
            <img src="{{ asset('images/scooter_delivery_3d_with_driver.webp') }}" alt="سكوتر التوصيل مع السائق"
                class="h-full w-full object-contain object-center drop-shadow-md">
        </div>
    </a>

    @if ($whatsapp !== '' && $whatsappHref !== '' && $whatsappHref !== 'https://wa.me/')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
            class=" h-9 w-9 shrink-0 animate-pulse items-center justify-center rounded-full bg-[#25D366] text-white shadow-md shadow-[#25D366]/40 transition-all hover:scale-110 flex">
            <x-lucide-message-circle class="h-6 w-6" aria-hidden="true" />
        </a>
    @endif


    {{-- الطلب عبر Thuisbezorgd --}}
    <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 p-1.5 shadow-sm transition-all hover:scale-105">
        <img src="{{ asset('images/thuisbezorgd.png') }}" alt="Thuisbezorgd" class="h-full w-full object-contain" />
    </a>

    {{-- زر الحجز المختصر للجوال --}}
    <a href="#reservation"
        class="inline-flex h-9 shrink-0 animate-pulse items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 text-[11px] font-bold text-white shadow-md shadow-[#E07513]/25 transition-all">
        {{ __('messages.nav.bookTableBtn') }}
    </a>

    {{-- قائمة اللغة المحلية مع إغلاق عند الضغط خارجها --}}
    <div class="relative shrink-0" x-data="{ open: false }">
        <button @click="open = !open" @keydown.escape.window="open = false" type="button"
            class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/50 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
            <span>{{ app()->getLocale() == 'ar' ? 'العربية' : (app()->getLocale() == 'nl' ? 'Nederlands' : 'English') }}</span>
            <x-lucide-chevron-down class="h-3.5 w-3.5 text-gray-500 transition-transform duration-200"
                ::class="{ 'rotate-180': open }" aria-hidden="true" />
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
