{{--
أدوات الهيدر للجوال فقط.
كل زر: أيقونة معروفة + نص صغير تحتها.
--}}

@php
    $f = $footerLayout ?? [];
    extract($f, EXTR_SKIP);
    $whatsapp = (string) ($whatsapp ?? ($f['whatsapp'] ?? ''));
    $whatsappHref = (string) ($whatsappHref ?? ($f['whatsapp_href'] ?? ''));

    $btn = 'flex h-12 min-w-[46px] shrink-0 flex-col items-center justify-center gap-0.5 rounded-xl border px-1 shadow-sm transition-all hover:scale-105';
    $lbl = 'text-[9px] font-bold leading-none whitespace-nowrap';
@endphp

<div class="flex items-center gap-1 sm:gap-2 lg:hidden">

    {{-- واتساب --}}
    @if ($whatsapp !== '' && $whatsappHref !== '' && $whatsappHref !== 'https://wa.me/')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer"
            class="{{ $btn }} border-[#25D366] bg-[#25D366] text-white shadow-[#25D366]/40">
           @svg('icon-whatsapp', 'h-5 w-5', ['aria-hidden' => 'true'])
            <span class="{{ $lbl }}">{{ __('messages.nav.whatsapp') }}</span>
        </a>
    @endif

    {{-- معلومات المطعم --}}
    <!-- <a href="{{ route('restaurant-info') }}" wire:navigate
        class="{{ $btn }} border-gray-200 bg-white/50 text-[#E07513] hover:bg-white">
        @svg('lucide-circle-help', 'h-5 w-5', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.restaurantInfo') }}</span>
    </a> -->

    {{-- التوصيل عبر Thuisbezorgd --}}
    <a href="{{ route('restaurant-info') }}#delivery"  rel="noopener noreferrer"
        class="{{ $btn }} border-gray-200 bg-white/50 text-gray-800">
        <img src="{{ asset('images/thuisbezorgd.png') }}" alt="" width="200" height="206"
            loading="lazy" decoding="async" class="h-5 w-5 object-contain" />
        <span class="{{ $lbl }}">{{ __('messages.nav.delivery') }}</span>
    </a>

    {{-- الطلب --}}
    <a href="{{ route('home') }}#menu" @click="activeSection = 'menu'"
        class="{{ $btn }} border-gray-200 bg-white/70 text-gray-800 hover:text-[#E07513]">
        @svg('lucide-shopping-bag', 'h-5 w-5', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.order') }}</span>
    </a>

    {{-- احجز طاولة — أيقونة الشوكة والسكين --}}
    <a href="{{ route('home') }}#reservation" @click="activeSection = 'reservation'"
        class="{{ $btn }} border-transparent bg-gradient-to-r from-[#E07513] to-[#B85709] text-white shadow-md shadow-[#E07513]/30">
     @svg('icon-dining-table', 'h-6 w-6', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.reserve') }}</span>
    </a>

    {{-- محوّل اللغة --}}
    @include('components.layouts.partials.header.language-switcher')

</div>