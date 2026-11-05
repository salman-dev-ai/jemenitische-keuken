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
            @svg('lucide-message-circle', 'h-5 w-5', ['aria-hidden' => 'true'])
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
            @svg('lucide-chevron-down', 'h-3.5 w-3.5 text-gray-500 transition-transform duration-200', [':class' => "{ 'rotate-180': open }", 'aria-hidden' => 'true'])
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
