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

   

    @if ($whatsapp !== '' && $whatsappHref !== '' && $whatsappHref !== 'https://wa.me/')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
            class=" h-9 w-9 shrink-0 animate-pulse items-center justify-center rounded-full bg-[#25D366] text-white shadow-md shadow-[#25D366]/40 transition-all hover:scale-110 flex">
            @svg('lucide-message-circle', 'h-6 w-6', ['aria-hidden' => 'true'])
        </a>
    @endif


    {{-- رابط معلومات المطعم (أوقات العمل + الحساسية + التوصيل) --}}
    <a href="{{ route('restaurant-info') }}" wire:navigate aria-label="{{ __('messages.nav.restaurantInfo') }}"
        title="{{ __('messages.nav.restaurantInfo') }}"
        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 text-[#E07513] shadow-sm transition-all hover:scale-105 hover:bg-white">
        @svg('lucide-circle-help', 'h-5 w-5', ['aria-hidden' => 'true'])
    </a>

    {{-- الطلب عبر Thuisbezorgd --}}
    <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 p-1.5 shadow-sm transition-all hover:scale-105">
        <img src="{{ asset('images/thuisbezorgd.png') }}" alt="Thuisbezorgd" width="200" height="206"
            loading="lazy" decoding="async" class="h-full w-full object-contain" />
    </a>


     {{-- الطلب / Bestellen --}}
    <a href="{{ route('home') }}#menu" @click="activeSection = 'menu'"  title="{{ __('messages.nav.order') }}"
        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-4 text-xs font-bold text-gray-800 shadow-sm transition-all hover:-translate-y-0.5 hover:border-[#E07513]/40 hover:text-[#E07513]">
        @svg('lucide-shopping-bag', 'h-4 w-4', ['aria-hidden' => 'true'])
       
    </a>

   {{-- الحجز / Reserveren — بارز بتدرج لوني --}}
    <a href="{{ route('home') }}#reservation" @click="activeSection = 'reservation'" title="{{ __('messages.nav.reserve') }}"
        class="inline-flex h-10 shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-5 text-sm font-bold text-white shadow-md shadow-[#E07513]/30 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#E07513]/40">
        @svg('lucide-calendar-check' , 'h-4 w-4', ['aria-hidden' => 'true'])
        <span class="hidden sm:inline-block"  >{{ __('messages.nav.reserve') }}</span>
       
     
    </a>


  
        {{-- محوّل اللغة / Language Switcher --}}
    @include('components.layouts.partials.header.language-switcher')


</div>
